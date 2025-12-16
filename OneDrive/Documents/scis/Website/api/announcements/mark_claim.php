<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->announcement_id) || !isset($data->senior_id)) {
        Response::error("Announcement ID and Senior ID required", 400);
    }

    try {
        $db->beginTransaction();
        
        // Check if participant record exists
        $query = "SELECT id FROM event_participants 
                WHERE announcement_id = :ann_id AND senior_id = :senior_id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':ann_id' => $data->announcement_id,
            ':senior_id' => $data->senior_id
        ]);
        
        if ($stmt->rowCount() == 0) {
            // Create participant record
            $query = "INSERT INTO event_participants 
                    (announcement_id, senior_id, attended, attendance_date,
                    claimed_benefit, claim_date, claimed_by_admin)
                    VALUES (:ann_id, :senior_id, 1, NOW(), 1, NOW(), :user_id)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':ann_id' => $data->announcement_id,
                ':senior_id' => $data->senior_id,
                ':user_id' => $auth->getUserId()
            ]);
        } else {
            // Update existing record
            $query = "UPDATE event_participants SET 
                    claimed_benefit = 1,
                    claim_date = NOW(),
                    claimed_by_admin = :user_id
                    WHERE announcement_id = :ann_id AND senior_id = :senior_id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':user_id' => $auth->getUserId(),
                ':ann_id' => $data->announcement_id,
                ':senior_id' => $data->senior_id
            ]);
        }
        
        $db->commit();
        
        Response::success(null, "Benefit claimed successfully");
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to mark claim: " . $e->getMessage(), 500);
    }