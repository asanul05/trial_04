<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));
    $is_update = isset($data->id) && $data->id > 0;

    $permission = $is_update ? 'can_edit' : 'can_create';
    if (!$auth->checkPermission('announcements', $permission)) {
        Response::error("No permission to " . ($is_update ? "update" : "create") . " announcements", 403);
    }

    $required = ['type_id', 'title', 'description'];
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    try {
        $db->beginTransaction();
        
        if ($is_update) {
            $query = "UPDATE announcements SET 
                    type_id = :type_id,
                    title = :title,
                    description = :description,
                    event_date = :event_date,
                    event_time = :event_time,
                    location = :location,
                    target_audience = :audience,
                    is_published = :published
                    WHERE id = :id";
            $params = [
                ':type_id' => $data->type_id,
                ':title' => $data->title,
                ':description' => $data->description,
                ':event_date' => $data->event_date ?? null,
                ':event_time' => $data->event_time ?? null,
                ':location' => $data->location ?? null,
                ':audience' => $data->target_audience ?? null,
                ':published' => $data->is_published ?? false,
                ':id' => $data->id
            ];
            $announcement_id = $data->id;
        } else {
            $query = "INSERT INTO announcements 
                    (type_id, title, description, event_date, event_time, 
                    location, target_audience, created_by, is_published, published_date)
                    VALUES 
                    (:type_id, :title, :description, :event_date, :event_time,
                    :location, :audience, :user_id, :published, 
                    CASE WHEN :published = 1 THEN NOW() ELSE NULL END)";
            $params = [
                ':type_id' => $data->type_id,
                ':title' => $data->title,
                ':description' => $data->description,
                ':event_date' => $data->event_date ?? null,
                ':event_time' => $data->event_time ?? null,
                ':location' => $data->location ?? null,
                ':audience' => $data->target_audience ?? null,
                ':user_id' => $auth->getUserId(),
                ':published' => $data->is_published ?? false
            ];
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        if (!$is_update) {
            $announcement_id = $db->lastInsertId();
        }
        
        $db->commit();
        
        Response::success([
            'announcement_id' => $announcement_id
        ], "Announcement " . ($is_update ? "updated" : "created") . " successfully");
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to save announcement: " . $e->getMessage(), 500);
    }