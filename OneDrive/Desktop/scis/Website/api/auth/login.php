<?php
    session_start();
    require_once '../config/database.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->username) || !isset($data->password)) {
        Response::error("Username and password required", 400);
    }

    $query = "SELECT u.*, r.name as role_name, r.level as role_level 
            FROM admin_users u 
            JOIN user_roles r ON u.role_id = r.id 
            WHERE u.username = :username AND u.is_active = 1";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $data->username);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        Response::error("Invalid credentials", 401);
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($data->password, $user['password_hash'])) {
        Response::error("Invalid credentials", 401);
    }

    // Update last login
    $update = "UPDATE admin_users SET last_login = NOW() WHERE id = :id";
    $stmt = $db->prepare($update);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();

    // Store user in session
    $_SESSION['user'] = [
        'id' => $user['id'],
        'role_id' => $user['role_id'],
        'username' => $user['username'],
        'full_name' => $user['first_name'] . ' ' . $user['last_name'],
        'role' => $user['role_name'],
        'role_level' => $user['role_level'],
        'branch_id' => $user['branch_id'],
        'barangay_id' => $user['barangay_id'],
        'position' => $user['position']
    ];

    Response::success(['user' => $_SESSION['user']], "Login successful");