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

    // if (!$auth->checkPermission('archives', 'can_view')) {
    //     Response::error("No permission to view archives", 403);
    // }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $where_clauses = ["1=1"];
    $params_to_bind = [];

    if (!empty($search)) {
        $where_clauses[] = "(s.first_name LIKE :search OR s.last_name LIKE :search OR s.osca_id LIKE :search)";
        $params_to_bind[':search'] = '%' . $search . '%';
    }

    $where = "WHERE " . implode(" AND ", $where_clauses);

    // Count query
    $count_query = "SELECT COUNT(*) as total FROM senior_citizens s
                    " . $where;
    $stmt = $db->prepare($count_query);
    foreach ($params_to_bind as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT s.id, s.osca_id, s.first_name, s.middle_name, s.last_name, 
                    s.extension, s.birthdate, s.age, g.name as gender, b.name as barangay,
                    br.name as branch, rs.name as status, s.registration_date
            FROM senior_citizens s
            LEFT JOIN genders g ON s.gender_id = g.id
            LEFT JOIN barangays b ON s.barangay_id = b.id
            LEFT JOIN branches br ON s.branch_id = br.id
            LEFT JOIN registration_statuses rs ON s.registration_status_id = rs.id
            " . $where . "
            ORDER BY s.created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    foreach ($params_to_bind as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $seniors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($seniors, $page, $limit, $total, "Archived senior citizens retrieved successfully");
