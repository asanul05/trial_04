<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('announcements', 'can_edit')) { // Assuming 'can_edit' for managing participants
        Response::error("No permission to register seniors to events", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->announcement_id) || !isset($data->senior_id)) {
        Response::error("Announcement ID and Senior ID are required", 400);
    }

    $announcement_id = $data->announcement_id;
    $senior_id = $data->senior_id;

    try {
        // Check if already registered
        $query = "SELECT id FROM event_participants WHERE announcement_id = :announcement_id AND senior_id = :senior_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':announcement_id', $announcement_id);
        $stmt->bindParam(':senior_id', $senior_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            Response::error("Senior citizen already registered for this event", 400);
        }

        $query = "INSERT INTO event_participants (announcement_id, senior_id, registered_date)
                VALUES (:announcement_id, :senior_id, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':announcement_id' => $announcement_id,
            ':senior_id' => $senior_id
        ]);

        Response::success(null, "Senior citizen registered for event successfully");

    } catch (Exception $e) {
        Response::error("Failed to register senior citizen for event: " . $e->getMessage(), 500);
    }
