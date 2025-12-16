<?php    
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('complaints', 'can_create')) {
        Response::error("No permission to file complaints", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    // Validate required fields
    $required = ['complainant_id', 'violator_name', 'category_id', 'description'];
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }
    
    try {
        $db->beginTransaction();
        
        // Verify complainant and access
        $query = "SELECT barangay_id FROM senior_citizens WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data->complainant_id);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            Response::error("Complainant not found", 404);
        }
        
        $senior = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$auth->canAccessBarangay($senior['barangay_id'])) {
            Response::error("No access to file complaint for this senior", 403);
        }
        
        // Generate complaint number
        $year = date('Y');
        $query = "SELECT MAX(CAST(SUBSTRING(complaint_number, -4) AS UNSIGNED)) as last_num 
                FROM complaints 
                WHERE complaint_number LIKE 'COMP-$year-%'";
        $stmt = $db->query($query);
        $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
        $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        $complaint_num = "COMP-$year-$new_num";
        
        // Create complaint
        $query = "INSERT INTO complaints 
                (complaint_number, complainant_id, violator_name, violator_contact,
                category_id, description, incident_date, incident_location,
                status_id, filed_by, filed_date)
                VALUES 
                (:comp_num, :complainant, :violator, :contact, :category, :desc,
                :incident_date, :location, 1, :user_id, CURDATE())";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':comp_num' => $complaint_num,
            ':complainant' => $data->complainant_id,
            ':violator' => $data->violator_name,
            ':contact' => $data->violator_contact ?? null,
            ':category' => $data->category_id,
            ':desc' => $data->description,
            ':incident_date' => $data->incident_date ?? null,
            ':location' => $data->incident_location ?? null,
            ':user_id' => $auth->getUserId()
        ]);
        
        $complaint_id = $db->lastInsertId();
        
        $db->commit();
        
        Response::success([
            'complaint_id' => $complaint_id,
            'complaint_number' => $complaint_num
        ], "Complaint filed successfully", 201);
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to file complaint: " . $e->getMessage(), 500);
    }

    // Verify complainant exists and is accessible
    $query = "SELECT barangay_id FROM senior_citizens WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data->complainant_id);
    $stmt->execute();
    $senior = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$senior) {
        Response::error("Senior citizen not found", 404);
    }

    if (!$auth->canAccessBarangay($senior['barangay_id'])) {
        Response::error("No access to this senior citizen", 403);
    }

    try {
        // Generate complaint number
        $year = date('Y');
        $query = "SELECT MAX(CAST(SUBSTRING(complaint_number, -6) AS UNSIGNED)) as last_num 
                FROM complaints 
                WHERE complaint_number LIKE 'COMP-$year-%'";
        $stmt = $db->query($query);
        $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
        $new_num = str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
        $complaint_number = "COMP-$year-$new_num";
        
        // Insert complaint
        $query = "INSERT INTO complaints 
                (complaint_number, complainant_id, violator_name, violator_contact,
                category_id, description, incident_date, incident_location,
                status_id, amount_billable, filed_by, filed_date)
                VALUES 
                (:complaint_number, :complainant_id, :violator_name, :violator_contact,
                :category_id, :description, :incident_date, :incident_location,
                1, :amount_billable, :filed_by, CURDATE())";
        
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
            ':amount_billable' => $data->amount_billable ?? null,
            ':filed_by' => $auth->getUserId()
        ]);
        
        $complaint_id = $db->lastInsertId();
        
        // Handle document uploads if present
        if (isset($data->documents) && is_array($data->documents)) {
            $doc_query = "INSERT INTO complaint_documents 
                        (complaint_id, file_path, original_filename, file_type, uploaded_by) 
                        VALUES (?, ?, ?, ?, ?)";
            $doc_stmt = $db->prepare($doc_query);
            
            foreach ($data->documents as $doc) {
                $doc_stmt->execute([
                    $complaint_id,
                    $doc->file_path,
                    $doc->original_filename,
                    $doc->file_type,
                    $auth->getUserId()
                ]);
            }
        }
        
        Response::success(
            [
                'complaint_id' => $complaint_id,
                'complaint_number' => $complaint_number
            ],
            "Complaint filed successfully"
        );
        
    } catch (Exception $e) {
        Response::error("Failed to file complaint: " . $e->getMessage(), 500);
    }