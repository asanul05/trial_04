<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('accounts', 'can_delete')) {
        Response::error("No permission to delete user accounts", 403);
    }

    if (!isset($_GET['id'])) {
        Response::error("User ID is required", 400);
    }

    $id = $_GET['id'];

    try {
        // Prevent deleting the currently logged-in user
        if ($id == $auth->getUserId()) {
            Response::error("Cannot delete your own user account", 403);
        }

        $query = "DELETE FROM admin_users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                Response::success(null, "User account deleted successfully");
            } else {
                Response::error("User account not found or already deleted", 404);
            }
        } else {
            Response::error("Failed to delete user account", 500);
        }
    } catch (Exception $e) {
        Response::error("Failed to delete user account: " . $e->getMessage(), 500);
    }
