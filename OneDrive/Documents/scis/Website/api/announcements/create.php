<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('announcements', 'can_create')) {
        Response::error("No permission to create announcements", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    // Validate required fields
    $required = ['type_id', 'title', 'description'];
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    try {
        $query = "INSERT INTO announcements 
                (type_id, title, description, event_date, event_time, 
                location, target_audience, created_by, is_published, published_date)
                VALUES 
                (:type_id, :title, :description, :event_date, :event_time,
                :location, :target_audience, :created_by, :is_published, 
                :published_date)";
        
        $stmt = $db->prepare($query);
        
        $is_published = isset($data->is_published) ? $data->is_published : 0;
        $published_date = $is_published ? date('Y-m-d H:i:s') : null;
        
        $stmt->execute([
            ':type_id' => $data->type_id,
            ':title' => $data->title,
            ':description' => $data->description,
            ':event_date' => $data->event_date ?? null,
            ':event_time' => $data->event_time ?? null,
            ':location' => $data->location ?? null,
            ':target_audience' => $data->target_audience ?? null,
            ':created_by' => $auth->getUserId(),
            ':is_published' => $is_published,
            ':published_date' => $published_date
        ]);
        
        $announcement_id = $db->lastInsertId();
        
        // Handle media uploads if present
        if (isset($data->media) && is_array($data->media)) {
            $media_query = "INSERT INTO announcement_media 
                        (announcement_id, file_path, media_type) 
                        VALUES (?, ?, ?)";
            $media_stmt = $db->prepare($media_query);
            
            foreach ($data->media as $media) {
                $media_stmt->execute([
                    $announcement_id,
                    $media->file_path,
                    $media->media_type
                ]);
            }
        }
        
        Response::success(
            ['announcement_id' => $announcement_id],
            "Announcement created successfully"
        );
        
    } catch (Exception $e) {
        Response::error("Failed to create announcement: " . $e->getMessage(), 500);
    }