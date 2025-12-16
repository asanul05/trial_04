<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!isset($_GET['id'])) {
        Response::error("Announcement ID is required", 400);
    }

    $id = $_GET['id'];

    $query = "SELECT a.*, at.name as type_name 
            FROM announcements a
            JOIN announcement_types at ON a.type_id = at.id
            WHERE a.id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($announcement) {
        Response::success($announcement, "Announcement details retrieved");
    } else {
        Response::error("Announcement not found", 404);
    }
