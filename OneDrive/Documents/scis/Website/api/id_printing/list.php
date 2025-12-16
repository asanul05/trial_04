<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('id_printing', 'can_view')) {
        Response::error("No permission to view ID printing list", 403);
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : ''; // e.g., 'For Printing', 'Printed', 'Claimed'

    $where_clauses = ["1=1"];
    $params = [];

    // Filter by branch/barangay access
    if ($auth->getRoleId() == 2) { // Branch Admin
        $where_clauses[] = "sc.branch_id = :branch_id";
        $params[':branch_id'] = $auth->getBranchId();
    } elseif ($auth->getRoleId() == 3) { // Barangay Admin
        $where_clauses[] = "sc.barangay_id = :barangay_id";
        $params[':barangay_id'] = $auth->getBarangayId();
    }

    if (!empty($search)) {
        $where_clauses[] = "(sc.first_name LIKE :search OR sc.last_name LIKE :search OR a.application_number LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    if (!empty($status)) {
        $where_clauses[] = "a.status = :status";
        $params[':status'] = $status;
    } else {
        // Default to showing only applications that are 'Approved' or 'For Printing'
        $where_clauses[] = "a.status IN ('Approved', 'Verified', 'Printed', 'Claimed')";
    }

    $where = "WHERE " . implode(" AND ", $where_clauses);

    // Count query
    $count_query = "SELECT COUNT(*) as total 
                    FROM applications a
                    LEFT JOIN senior_citizens sc ON a.senior_id = sc.id
                    LEFT JOIN application_types atype ON a.application_type_id = atype.id
                    " . $where;
    $stmt = $db->prepare($count_query);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT a.id as application_id, a.application_number, a.status as application_status, a.submitted_by, a.submission_date,
                    sc.osca_id, sc.first_name, sc.last_name, sc.middle_name, sc.extension, sc.birthdate,
                    atype.name as application_type_name
            FROM applications a
            LEFT JOIN senior_citizens sc ON a.senior_id = sc.id
            LEFT JOIN application_types atype ON a.application_type_id = atype.id
            " . $where . "
            ORDER BY a.submission_date DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();

    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($applications, $page, $limit, $total, "ID Printing list retrieved successfully");