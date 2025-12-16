<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('announcements', 'can_view')) {
        Response::error("No permission to view event participants", 403);
    }

    if (!isset($_GET['announcement_id'])) {
        Response::error("Announcement ID is required", 400);
    }

    $announcement_id = $_GET['announcement_id'];

    $query = "SELECT ep.*, sc.first_name as senior_first_name, sc.last_name as senior_last_name, sc.osca_id
            FROM event_participants ep
            JOIN senior_citizens sc ON ep.senior_id = sc.id
            WHERE ep.announcement_id = :announcement_id
            ORDER BY ep.registered_date DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':announcement_id', $announcement_id);
    $stmt->execute();

    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($participants, "Event participants retrieved successfully");
