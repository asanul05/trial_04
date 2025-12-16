<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('id_printing', 'can_print')) {
        Response::error("No permission to print IDs", 403);
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->application_id)) {
        Response::error("Application ID required", 400);
    }

    try {
        $db->beginTransaction();
        
        // Get application and senior details
        $query = "SELECT a.*, s.barangay_id, s.osca_id 
                FROM applications a
                JOIN senior_citizens s ON a.senior_id = s.id
                WHERE a.id = :id AND a.status = 'Approved'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data->application_id);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            Response::error("Application not found or not approved", 404);
        }
        
        $application = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$auth->canAccessBarangay($application['barangay_id'])) {
            Response::error("No access to print ID for this application", 403);
        }
        
        // Check if ID already exists
        $check = "SELECT id FROM senior_ids WHERE application_id = :id";
        $stmt = $db->prepare($check);
        $stmt->bindParam(':id', $data->application_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            Response::error("ID already generated for this application", 400);
        }
        
        // Generate QR code data
        $qr_data = json_encode([
            'osca_id' => $application['osca_id'],
            'senior_id' => $application['senior_id'],
            'issue_date' => date('Y-m-d')
        ]);
        
        // Calculate expiry (3 years from issue)
        $expiry_date = date('Y-m-d', strtotime('+3 years'));
        
        // Create ID record
        $query = "INSERT INTO senior_ids 
                (senior_id, application_id, id_number, qr_code, 
                issue_date, expiry_date, status_id, printed_by, print_date)
                VALUES 
                (:senior_id, :app_id, :id_num, :qr, :issue, :expiry, 1, :user_id, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':senior_id' => $application['senior_id'],
            ':app_id' => $data->application_id,
            ':id_num' => $application['osca_id'],
            ':qr' => $qr_data,
            ':issue' => date('Y-m-d'),
            ':expiry' => $expiry_date,
            ':user_id' => $auth->getUserId()
        ]);
        
        $id_record_id = $db->lastInsertId();
        
        // Update application status
        $update = "UPDATE applications 
                SET status = 'Printed', print_date = NOW(), printed_by = :user_id
                WHERE id = :id";
        $stmt = $db->prepare($update);
        $stmt->execute([
            ':user_id' => $auth->getUserId(),
            ':id' => $data->application_id
        ]);
        
        $db->commit();
        
        Response::success([
            'id_record_id' => $id_record_id,
            'id_number' => $application['osca_id'],
            'issue_date' => date('Y-m-d'),
            'expiry_date' => $expiry_date
        ], "ID generated successfully");
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to generate ID: " . $e->getMessage(), 500);
    }