<?php
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
    $barangay_ids = implode(',', $accessible_barangays);

    $where = "WHERE s.barangay_id IN ($barangay_ids)";

    if ($search) {
        $where .= " AND (s.first_name LIKE :search 
                    OR s.last_name LIKE :search 
                    OR a.application_number LIKE :search
                    OR s.osca_id LIKE :search)";
    }

    if ($status) {
        $where .= " AND a.status = :status";
    }

    if ($type) {
        $where .= " AND a.application_type_id = :type";
    }

    // Count query
    $count_query = "SELECT COUNT(*) as total 
                    FROM applications a
                    JOIN senior_citizens s ON a.senior_id = s.id
                    $where";
    $stmt = $db->prepare($count_query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->bindParam(':search', $search_param);
    }
    if ($status) $stmt->bindParam(':status', $status);
    if ($type) $stmt->bindParam(':type', $type);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT a.*, 
            CONCAT(s.first_name, ' ', s.last_name) as senior_name,
            s.osca_id, s.age, s.barangay_id,
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
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->bindParam(':search', $search_param);
    }
    if ($status) $stmt->bindParam(':status', $status);
    if ($type) $stmt->bindParam(':type', $type);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($applications, $page, $limit, $total, "Applications retrieved");
