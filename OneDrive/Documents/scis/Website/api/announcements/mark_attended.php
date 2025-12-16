<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('announcements', 'can_edit')) { // Assuming 'can_edit' for managing participants
        Response::error("No permission to mark attendance", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->participant_id)) {
        Response::error("Participant ID is required", 400);
    }

    $participant_id = $data->participant_id;

    try {
        $query = "UPDATE event_participants SET 
                    attended = 1,
                    attendance_date = NOW()
                WHERE id = :participant_id";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':participant_id' => $participant_id
        ]);

        if ($stmt->rowCount() > 0) {
            Response::success(null, "Participant marked as attended successfully");
        } else {
            Response::error("Participant not found or already marked attended", 404);
        }

    } catch (Exception $e) {
        Response::error("Failed to mark participant as attended: " . $e->getMessage(), 500);
    }
