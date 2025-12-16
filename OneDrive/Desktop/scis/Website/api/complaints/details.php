<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('complaints', 'can_view')) {
        Response::error("No permission to view complaints", 403);
    }

    if (!isset($_GET['id'])) {
        Response::error("Complaint ID is required", 400);
    }

    $id = $_GET['id'];

    $query = "SELECT c.*, s.first_name as complainant_first_name, s.last_name as complainant_last_name,
                    cc.name as category_name, cs.name as status_name, cs.color_code as status_color
            FROM complaints c
            LEFT JOIN senior_citizens s ON c.complainant_id = s.id
            LEFT JOIN complaint_categories cc ON c.category_id = cc.id
            LEFT JOIN complaint_statuses cs ON c.status_id = cs.id
            WHERE c.id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $complaint = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($complaint) {
        Response::success($complaint, "Complaint details retrieved");
    } else {
        Response::error("Complaint not found", 404);
    }
