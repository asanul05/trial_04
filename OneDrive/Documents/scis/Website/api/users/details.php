<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('accounts', 'can_view')) {
        Response::error("No permission to view user accounts", 403);
    }

    if (!isset($_GET['id'])) {
        Response::error("User ID is required", 400);
    }

    $id = $_GET['id'];

    $query = "SELECT u.id, u.employee_id, u.username, u.first_name, u.middle_name, u.last_name, 
                    u.extension, u.position, u.gender_id, u.mobile_number, u.email, 
                    u.role_id, u.branch_id, u.barangay_id, u.is_active, r.name as role_name
            FROM admin_users u
            LEFT JOIN user_roles r ON u.role_id = r.id
            WHERE u.id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Ensure user has access to this record based on their role level
        if ($auth->getRoleId() == 2 && $auth->getBranchId() != $user['branch_id']) {
            Response::error("Access denied to this user record", 403);
        }
        if ($auth->getRoleId() == 3 && $auth->getBarangayId() != $user['barangay_id']) {
            Response::error("Access denied to this user record", 403);
        }

        Response::success($user, "User details retrieved");
    } else {
        Response::error("User not found", 404);
    }
