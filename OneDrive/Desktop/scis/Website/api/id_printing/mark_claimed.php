<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('id_printing', 'can_edit')) { // Assuming 'can_edit' permission for marking claimed
        Response::error("No permission to mark ID as claimed", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->application_id)) {
        Response::error("Application ID is required", 400);
    }

    $application_id = $data->application_id;

    try {
        $query = "UPDATE applications SET 
                    status = 'Claimed',
                    claimed_by = :claimed_by,
                    claim_date = NOW()
                WHERE id = :application_id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':claimed_by' => $auth->getUserId(),
            ':application_id' => $application_id
        ]);

        if ($stmt->rowCount() > 0) {
            Response::success(null, "Application marked as claimed successfully");
        } else {
            Response::error("Application not found or already claimed", 404);
        }
    } catch (Exception $e) {
        Response::error("Failed to mark application as claimed: " . $e->getMessage(), 500);
    }
