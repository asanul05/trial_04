<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('complaints', 'can_view')) {
        Response::error("No permission to view complaints", 403);
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status_id = isset($_GET['status_id']) ? (int)$_GET['status_id'] : null;
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

    $where_clauses = ["1=1"];
    $params = [];

    // Role-based filtering
    if ($auth->getRoleId() == 2) { // Branch Admin
        $where_clauses[] = "s.branch_id = :auth_branch_id";
        $params[':auth_branch_id'] = $auth->getBranchId();
    } elseif ($auth->getRoleId() == 3) { // Barangay Admin
        $where_clauses[] = "s.barangay_id = :auth_barangay_id";
        $params[':auth_barangay_id'] = $auth->getBarangayId();
    }

    if (!empty($search)) {
        $where_clauses[] = "(s.first_name LIKE :search OR s.last_name LIKE :search OR c.violator_name LIKE :search OR c.complaint_number LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    if ($status_id) {
        $where_clauses[] = "c.status_id = :status_id";
        $params[':status_id'] = $status_id;
    }
    if ($category_id) {
        $where_clauses[] = "c.category_id = :category_id";
        $params[':category_id'] = $category_id;
    }

    $where = "WHERE " . implode(" AND ", $where_clauses);

    // Count query
    $count_query = "SELECT COUNT(*) as total 
                    FROM complaints c
                    LEFT JOIN senior_citizens s ON c.complainant_id = s.id
                    LEFT JOIN complaint_categories cc ON c.category_id = cc.id
                    LEFT JOIN complaint_statuses cs ON c.status_id = cs.id
                    " . $where;
    $stmt = $db->prepare($count_query);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT c.*, s.first_name as complainant_first_name, s.last_name as complainant_last_name,
                    cc.name as category_name, cs.name as status_name, cs.color_code as status_color
            FROM complaints c
            LEFT JOIN senior_citizens s ON c.complainant_id = s.id
            LEFT JOIN complaint_categories cc ON c.category_id = cc.id
            LEFT JOIN complaint_statuses cs ON c.status_id = cs.id
            " . $where . "
            ORDER BY c.filed_date DESC, c.id DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();

    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($complaints, $page, $limit, $total, "Complaints retrieved successfully");
