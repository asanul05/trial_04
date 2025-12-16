<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('archives', 'can_view')) {
        Response::error("No permission to view archives", 403);
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $where = "";
    $params = [];

    if (!empty($search)) {
        $where .= " WHERE (u.username LIKE :search OR al.action LIKE :search OR al.module LIKE :search OR al.description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    // Count query
    $count_query = "SELECT COUNT(*) as total 
                    FROM activity_logs al
                    LEFT JOIN admin_users u ON al.user_id = u.id
                    " . $where;
    $stmt = $db->prepare($count_query);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT al.*, u.username 
            FROM activity_logs al
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

    Response::paginated($logs, $page, $limit, $total, "Archive logs retrieved successfully");
