<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('complaints', 'can_delete')) {
        Response::error("No permission to delete complaints", 403);
    }

    if (!isset($_GET['id'])) {
        Response::error("Complaint ID is required", 400);
    }

    $id = $_GET['id'];

    try {
        $query = "DELETE FROM complaints WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                Response::success(null, "Complaint deleted successfully");
            } else {
                Response::error("Complaint not found or already deleted", 404);
            }
        } else {
            Response::error("Failed to delete complaint", 500);
        }
    } catch (Exception $e) {
        Response::error("Failed to delete complaint: " . $e->getMessage(), 500);
    }
