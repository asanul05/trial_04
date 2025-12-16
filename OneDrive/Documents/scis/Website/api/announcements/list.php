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

    $type = isset($_GET['type']) ? $_GET['type'] : null;
    $is_published = isset($_GET['published']) ? (int)$_GET['published'] : 1;
    $upcoming_only = isset($_GET['upcoming']) ? (bool)$_GET['upcoming'] : false;

    $where = "WHERE a.is_published = :is_published";

    if ($type) {
        $where .= " AND at.name = :type";
    }

    if ($upcoming_only) {
        $where .= " AND a.event_date >= CURDATE()";
    }

    // Count query
    $count_query = "SELECT COUNT(*) as total 
                    FROM announcements a
                    JOIN announcement_types at ON a.type_id = at.id
                    $where";
    $stmt = $db->prepare($count_query);
    $stmt->bindParam(':is_published', $is_published);
    if ($type) $stmt->bindParam(':type', $type);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT a.*, at.name as type_name, 
            CONCAT(u.first_name, ' ', u.last_name) as created_by_name,
            COUNT(ep.id) as participant_count
            FROM announcements a
            JOIN announcement_types at ON a.type_id = at.id
            JOIN admin_users u ON a.created_by = u.id
            LEFT JOIN event_participants ep ON a.id = ep.announcement_id
            $where
            GROUP BY a.id
            ORDER BY a.event_date DESC, a.created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':is_published', $is_published);
    if ($type) $stmt->bindParam(':type', $type);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($announcements, $page, $limit, $total, "Announcements retrieved");

