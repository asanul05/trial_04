<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';
    require_once '../helpers/validation.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('applications', 'can_create')) {
        Response::error("No permission to create applications", 403);
    }
    
    $data = json_decode(file_get_contents("php://input"));

    $required = ['senior_id', 'application_type_id'];
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    // Generate application number
    $year = date('Y');
    $query = "SELECT MAX(CAST(SUBSTRING(application_number, -3) AS UNSIGNED)) as last_num 
            FROM applications 
            WHERE application_number LIKE 'APP-$year-%'";
    $stmt = $db->query($query);
    $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
    $new_num = str_pad($last_num + 1, 3, '0', STR_PAD_LEFT);
    $application_number = "APP-$year-$new_num";

    try {
        $query = "INSERT INTO applications (application_number, senior_id, application_type_id, status, submitted_by, submission_date, notes)
                VALUES (:application_number, :senior_id, :application_type_id, 'Submitted', :submitted_by, NOW(), :notes)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':application_number' => $application_number,
            ':senior_id' => $data->senior_id,
            ':application_type_id' => $data->application_type_id,
            ':submitted_by' => $auth->getUserId(),
            ':notes' => $data->notes ?? null
        ]);

        $application_id = $db->lastInsertId();

        Response::success(['application_id' => $application_id, 'application_number' => $application_number], "Application created successfully");

    } catch (Exception $e) {
        Response::error("Failed to create application: " . $e->getMessage(), 500);
    }
