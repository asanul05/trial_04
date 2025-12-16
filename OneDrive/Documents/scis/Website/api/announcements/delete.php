<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('announcements', 'can_delete')) {
        Response::error("No permission to delete announcements", 403);
    }

    if (!isset($_GET['id'])) {
        Response::error("Announcement ID is required", 400);
    }

    $id = $_GET['id'];

    try {
        $query = "DELETE FROM announcements WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            Response::success(null, "Announcement deleted successfully");
        } else {
            Response::error("Failed to delete announcement", 500);
        }
    } catch (Exception $e) {
        Response::error("Failed to delete announcement: " . $e->getMessage(), 500);
    }
