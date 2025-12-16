<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('access_logs', 'can_view')) {
        Response::error("No permission to view access logs", 403);
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $where = "";
    $params = [];

    if (!empty($search)) {
        $where .= " WHERE (u.username LIKE :search OR al.action LIKE :search OR al.ip_address LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $count_query = "SELECT COUNT(*) as total 
                    FROM access_logs al
                    LEFT JOIN admin_users u ON al.user_id = u.id
                    " . $where;
    $stmt = $db->prepare($count_query);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];


    $query = "SELECT al.*, u.username 
            FROM access_logs al
            LEFT JOIN admin_users u ON al.user_id = u.id
            " . $where . "
            ORDER BY al.timestamp DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($logs, $page, $limit, $total, "Access logs retrieved successfully");
