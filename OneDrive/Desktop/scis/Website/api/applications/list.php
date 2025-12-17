<?php
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $type = isset($_GET['type']) ? (int)$_GET['type'] : null;

    $accessible_barangays = $auth->getAccessibleBarangays();
    
    $where_parts = [];
    $params_to_bind = []; // To store parameters for binding

    if (!empty($accessible_barangays)) {
        // Create '?' placeholders for each accessible barangay
        $placeholders = implode(',', array_fill(0, count($accessible_barangays), '?'));
        $where_parts[] = "s.barangay_id IN ($placeholders)";
        $params_to_bind = array_merge($params_to_bind, $accessible_barangays);
    } else {
        // If no accessible barangays, ensure no results are returned
        $where_parts[] = "1 = 0";
    }

    if ($search) {
        $where_parts[] = "(s.first_name LIKE ?
                            OR s.last_name LIKE ?
                            OR a.application_number LIKE ?
                            OR s.osca_id LIKE ?)";
        $search_param = "%$search%";
        $params_to_bind[] = $search_param;
        $params_to_bind[] = $search_param;
        $params_to_bind[] = $search_param;
        $params_to_bind[] = $search_param;
    }

    if ($status) {
        $where_parts[] = "a.status = ?";
        $params_to_bind[] = $status;
    }

    if ($type) {
        $where_parts[] = "a.application_type_id = ?";
        $params_to_bind[] = $type;
    }

    $where = "WHERE " . implode(' AND ', $where_parts);

    try {
        // Count query
        $count_query = "SELECT COUNT(*) as total 
                        FROM applications a
                        JOIN senior_citizens s ON a.senior_id = s.id
                        $where";
        $stmt = $db->prepare($count_query);
        $stmt->execute($params_to_bind); // Execute with the array of parameters
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch (PDOException $e) {
        Response::error("Database query failed for applications count: " . $e->getMessage(), 500);
    }

 try {
    // Data query
    $query = "SELECT a.*, 
            CONCAT(s.first_name, ' ', s.last_name) as senior_name,
            s.osca_id, s.barangay_id,
            b.name as barangay_name,
            at.name as application_type,
            CONCAT(submitted.first_name, ' ', submitted.last_name) as submitted_by_name,
            CONCAT(verified.first_name, ' ', verified.last_name) as verified_by_name
            FROM applications a
            JOIN senior_citizens s ON a.senior_id = s.id
            JOIN barangays b ON s.barangay_id = b.id
            JOIN application_types at ON a.application_type_id = at.id
            LEFT JOIN admin_users submitted ON a.submitted_by = submitted.id
            LEFT JOIN admin_users verified ON a.verified_by = verified.id
            $where
            ORDER BY 
                CASE 
                WHEN a.status = 'Draft' THEN 1
                WHEN a.status = 'Submitted' THEN 2
                WHEN a.status = 'For Verification' THEN 3
                WHEN a.status = 'Verified' THEN 4
                WHEN a.status = 'For Printing' THEN 5
                ELSE 6
                END,
                a.submission_date DESC,
                a.created_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $db->prepare($query);
    
    $param_index = 1;
    // Bind parameters from the WHERE clause
    foreach ($params_to_bind as $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($param_index++, $value, $type);
    }
    
    // Bind LIMIT and OFFSET explicitly as integers
    $stmt->bindValue($param_index++, $limit, PDO::PARAM_INT);
    $stmt->bindValue($param_index++, $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($applications, $page, $limit, $total, "Applications retrieved");
    
} catch (PDOException $e) {
    Response::error("Database query failed for applications: " . $e->getMessage(), 500);
}
    Response::paginated($applications, $page, $limit, $total, "Applications retrieved");
