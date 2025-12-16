<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';
    require_once '../helpers/validation.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));
    $is_update = isset($data->id) && $data->id > 0;

    $permission = $is_update ? 'can_edit' : 'can_create';
    if (!$auth->checkPermission('complaints', $permission)) {
        Response::error("No permission to " . ($is_update ? "update" : "create") . " complaints", 403);
    }
    
    // Validate required fields
    $required = ['complainant_id', 'violator_name', 'category_id', 'description', 'filed_date'];
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    try {
        if ($is_update) {
            $query = "UPDATE complaints SET 
                        complainant_id = :complainant_id,
                        violator_name = :violator_name,
                        violator_contact = :violator_contact,
                        category_id = :category_id,
                        description = :description,
                        incident_date = :incident_date,
                        incident_location = :incident_location,
                        status_id = :status_id,
                        amount_billable = :amount_billable,
                        filed_by = :filed_by,
                        filed_date = :filed_date,
                        assigned_to = :assigned_to,
                        resolved_date = :resolved_date,
                        resolution_notes = :resolution_notes
                    WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':complainant_id' => $data->complainant_id,
                ':violator_name' => $data->violator_name,
                ':violator_contact' => $data->violator_contact ?? null,
                ':category_id' => $data->category_id,
                ':description' => $data->description,
                ':incident_date' => $data->incident_date ?? null,
                ':incident_location' => $data->incident_location ?? null,
                ':status_id' => $data->status_id ?? 1, // Default to Submitted
                ':amount_billable' => $data->amount_billable ?? null,
                ':filed_by' => $auth->getUserId(), // Filed by current user
                ':filed_date' => $data->filed_date,
                ':assigned_to' => $data->assigned_to ?? null,
                ':resolved_date' => $data->resolved_date ?? null,
                ':resolution_notes' => $data->resolution_notes ?? null,
                ':id' => $data->id
            ]);
            $complaint_id = $data->id;

        } else {
            // Generate complaint number
            $year = date('Y');
            $query = "SELECT MAX(CAST(SUBSTRING(complaint_number, -3) AS UNSIGNED)) as last_num 
                    FROM complaints 
                    WHERE complaint_number LIKE 'COMP-$year-%'";
            $stmt = $db->query($query);
            $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
            $new_num = str_pad($last_num + 1, 3, '0', STR_PAD_LEFT);
            $complaint_number = "COMP-$year-$new_num";

            $query = "INSERT INTO complaints (complaint_number, complainant_id, violator_name, violator_contact, 
                        category_id, description, incident_date, incident_location, status_id, 
                        amount_billable, filed_by, filed_date, assigned_to, resolved_date, resolution_notes)
                    VALUES (:complaint_number, :complainant_id, :violator_name, :violator_contact, 
                        :category_id, :description, :incident_date, :incident_location, :status_id, 
                        :amount_billable, :filed_by, :filed_date, :assigned_to, :resolved_date, :resolution_notes)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':complaint_number' => $complaint_number,
                ':complainant_id' => $data->complainant_id,
                ':violator_name' => $data->violator_name,
                ':violator_contact' => $data->violator_contact ?? null,
                ':category_id' => $data->category_id,
                ':description' => $data->description,
                ':incident_date' => $data->incident_date ?? null,
                ':incident_location' => $data->incident_location ?? null,
                ':status_id' => $data->status_id ?? 1, // Default to Submitted
                ':amount_billable' => $data->amount_billable ?? null,
                ':filed_by' => $auth->getUserId(), // Filed by current user
                ':filed_date' => $data->filed_date,
                ':assigned_to' => $data->assigned_to ?? null,
                ':resolved_date' => $data->resolved_date ?? null,
                ':resolution_notes' => $data->resolution_notes ?? null
            ]);
            $complaint_id = $db->lastInsertId();
        }
        
        Response::success(['complaint_id' => $complaint_id], "Complaint " . ($is_update ? "updated" : "created") . " successfully");

    } catch (Exception $e) {
        Response::error("Failed to save complaint: " . $e->getMessage(), 500);
    }
