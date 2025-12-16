<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->application_id) || !isset($data->status)) {
        Response::error("Application ID and status are required", 400);
    }

    $application_id = $data->application_id;
    $new_status = $data->status;


    $valid_statuses = [
        'Draft', 'Submitted', 'For Verification', 'Verified', 
        'For Printing', 'Printed', 'Ready for Release', 'Claimed', 'Rejected'
    ];

    if (!in_array($new_status, $valid_statuses)) {
        Response::error("Invalid status", 400);
    }

    try {
        $db->beginTransaction();
        
 
        $query = "SELECT a.*, s.barangay_id 
                FROM applications a
                JOIN senior_citizens s ON a.senior_id = s.id
                WHERE a.id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $application_id);
        $stmt->execute();
        $app = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$app) {
            Response::error("Application not found", 404);
        }
        

        if (!$auth->canAccessBarangay($app['barangay_id'])) {
            Response::error("No access to this application", 403);
        }
        

        $update_fields = ['status' => $new_status];
        $user_id = $auth->getUserId();
        
        switch ($new_status) {
            case 'Submitted':
                $update_fields['submission_date'] = date('Y-m-d H:i:s');
                break;
            case 'Verified':
                $update_fields['verified_by'] = $user_id;
                $update_fields['verification_date'] = date('Y-m-d H:i:s');
                break;
            case 'Printed':
                $update_fields['printed_by'] = $user_id;
                $update_fields['print_date'] = date('Y-m-d H:i:s');
                break;
            case 'Claimed':
                $update_fields['claimed_by'] = $user_id;
                $update_fields['claim_date'] = date('Y-m-d H:i:s');
                break;
        }
        
        $set_clause = [];
        foreach ($update_fields as $key => $value) {
            $set_clause[] = "$key = :$key";
        }
        
        $query = "UPDATE applications SET " . implode(', ', $set_clause) . 
                ", notes = CONCAT(COALESCE(notes, ''), '\n', :status_note)" .
                " WHERE id = :id";
        
        $stmt = $db->prepare($query);
        foreach ($update_fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':status_note', 
            date('Y-m-d H:i:s') . " - Status changed to $new_status by user " . $user_id);
        $stmt->bindParam(':id', $application_id);
        $stmt->execute();
        
        $db->commit();
        
        Response::success([
            'application_id' => $application_id,
            'new_status' => $new_status
        ], "Application status updated");
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to update status: " . $e->getMessage(), 500);
    }

    $auth = new AuthMiddleware($db);
if (!$auth->authenticate()) exit;

if (!$auth->checkPermission('applications', 'can_edit')) {
    Response::error("No permission to update applications", 403);
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->application_id) || !isset($data->status)) {
    Response::error("Application ID and status required", 400);
}

try {
    $db->beginTransaction();

    $query = "SELECT a.*, s.barangay_id 
              FROM applications a
              JOIN senior_citizens s ON a.senior_id = s.id
              WHERE a.id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data->application_id);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        Response::error("Application not found", 404);
    }
    
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$auth->canAccessBarangay($application['barangay_id'])) {
        Response::error("No access to update this application", 403);
    }

    $query = "UPDATE applications SET status = :status";
    $params = [':status' => $data->status, ':id' => $data->application_id];
    
    switch($data->status) {
        case 'Submitted':
            $query .= ", submission_date = NOW(), submitted_by = :user_id";
            $params[':user_id'] = $auth->getUserId();
            break;
        case 'Verified':
            $query .= ", verification_date = NOW(), verified_by = :user_id";
            $params[':user_id'] = $auth->getUserId();
            break;
        case 'Approved':
            $query .= ", approval_date = NOW(), approved_by = :user_id";
            $params[':user_id'] = $auth->getUserId();
            break;
        case 'Printed':
            $query .= ", print_date = NOW(), printed_by = :user_id";
            $params[':user_id'] = $auth->getUserId();
            break;
        case 'Claimed':
            $query .= ", claim_date = NOW(), claimed_by = :user_id";
            $params[':user_id'] = $auth->getUserId();
            break;
    }
    
    if (isset($data->notes)) {
        $query .= ", notes = :notes";
        $params[':notes'] = $data->notes;
    }
    
    $query .= " WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    

    $log_query = "INSERT INTO activity_logs 
                  (user_id, action, module, record_id, description)
                  VALUES (:user_id, 'UPDATE_STATUS', 'applications', :app_id, :desc)";
    $stmt = $db->prepare($log_query);
    $stmt->execute([
        ':user_id' => $auth->getUserId(),
        ':app_id' => $data->application_id,
        ':desc' => "Application status changed to: {$data->status}"
    ]);
    
    $db->commit();
    
    Response::success(null, "Application status updated successfully");
    
} catch (Exception $e) {
    $db->rollBack();
    Response::error("Failed to update application: " . $e->getMessage(), 500);
}