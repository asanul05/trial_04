<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function shutdown_handler() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Fatal Error: ' . $error['message'], 'file' => $error['file'], 'line' => $error['line']]);
    }
}

register_shutdown_function('shutdown_handler');

    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();
    if (!$db) {
        Response::error("Database connection failed.", 500);
    }

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    // if (!$auth->checkPermission('accounts', 'can_view')) {
    //     Response::error("No permission to view user accounts", 403);
    // }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $where_clauses = ["1=1"];
    $params = [];

    // Role-based filtering
    if ($auth->getRoleId() == 2) { // Branch Admin
        $where_clauses[] = "u.branch_id = :auth_branch_id";
        $params[':auth_branch_id'] = $auth->getBranchId();
    } elseif ($auth->getRoleId() == 3) { // Barangay Admin
        $where_clauses[] = "u.barangay_id = :auth_barangay_id";
        $params[':auth_barangay_id'] = $auth->getBarangayId();
    }

    if (!empty($search)) {
        $where_clauses[] = "(u.username LIKE :search OR u.first_name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $where = "WHERE " . implode(" AND ", $where_clauses);

    // Count query
    $count_query = "SELECT COUNT(*) as total
                    FROM admin_users u
                    LEFT JOIN user_roles r ON u.role_id = r.id
                    " . $where;
    $stmt = $db->prepare($count_query);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT u.id, u.employee_id, u.username, u.first_name, u.middle_name, u.last_name, 
                    u.extension, u.position, u.email, u.is_active, r.name as role_name
            FROM admin_users u
            LEFT JOIN user_roles r ON u.role_id = r.id
            " . $where . "
            ORDER BY u.created_at DESC
            LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($users, $page, $limit, $total, "User accounts retrieved successfully");