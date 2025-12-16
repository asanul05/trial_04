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
    if (!$auth->checkPermission('accounts', $permission)) {
        Response::error("No permission to " . ($is_update ? "update" : "create") . " user accounts", 403);
    }
    
    // Validate required fields
    $required = ['employee_id', 'username', 'first_name', 'last_name', 'role_id', 'is_active'];
    if (!$is_update) { // Password is required for new users
        $required[] = 'password';
    }
    foreach ($required as $field) {
        if (!isset($data->$field) || empty($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    // Check if username or employee_id already exists (excluding current user if updating)
    $unique_fields = ['username', 'employee_id'];
    foreach ($unique_fields as $field) {
        $query = "SELECT id FROM admin_users WHERE $field = :value";
        if ($is_update) {
            $query .= " AND id != :id";
        }
        $stmt = $db->prepare($query);
        $stmt->bindParam(':value', $data->$field);
        if ($is_update) {
            $stmt->bindParam(':id', $data->id);
        }
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            Response::error(ucfirst(str_replace('_', ' ', $field)) . " already exists", 400);
        }
    }

    // Password hashing for new users or if password is provided for update
    $password_hash = null;
    if (!$is_update || (isset($data->password) && !empty($data->password))) {
        if (!isset($data->password) || empty($data->password)) {
            Response::error("Password is required for new users or if updating password", 400);
        }
        $password_hash = password_hash($data->password, PASSWORD_DEFAULT);
    }

    try {
        if ($is_update) {
            $query = "UPDATE admin_users SET 
                        employee_id = :employee_id,
                        username = :username,
                        first_name = :first_name,
                        middle_name = :middle_name,
                        last_name = :last_name,
                        extension = :extension,
                        position = :position,
                        gender_id = :gender_id,
                        mobile_number = :mobile_number,
                        email = :email,
                        role_id = :role_id,
                        branch_id = :branch_id,
                        barangay_id = :barangay_id,
                        is_active = :is_active";
            if ($password_hash) {
                $query .= ", password_hash = :password_hash";
            }
            $query .= " WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $params = [
                ':employee_id' => $data->employee_id,
                ':username' => $data->username,
                ':first_name' => $data->first_name,
                ':middle_name' => $data->middle_name ?? null,
                ':last_name' => $data->last_name,
                ':extension' => $data->extension ?? null,
                ':position' => $data->position ?? null,
                ':gender_id' => $data->gender_id ?? null,
                ':mobile_number' => $data->mobile_number ?? null,
                ':email' => $data->email ?? null,
                ':role_id' => $data->role_id,
                ':branch_id' => $data->branch_id ?? null,
                ':barangay_id' => $data->barangay_id ?? null,
                ':is_active' => $data->is_active,
                ':id' => $data->id
            ];
            if ($password_hash) {
                $params[':password_hash'] = $password_hash;
            }
            $stmt->execute($params);
            $user_id = $data->id;

        } else {
            $query = "INSERT INTO admin_users (employee_id, username, password_hash, first_name, 
                        middle_name, last_name, extension, position, gender_id, mobile_number, 
                        email, role_id, branch_id, barangay_id, is_active)
                    VALUES (:employee_id, :username, :password_hash, :first_name, 
                        :middle_name, :last_name, :extension, :position, :gender_id, :mobile_number, 
                        :email, :role_id, :branch_id, :barangay_id, :is_active)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':employee_id' => $data->employee_id,
                ':username' => $data->username,
                ':password_hash' => $password_hash,
                ':first_name' => $data->first_name,
                ':middle_name' => $data->middle_name ?? null,
                ':last_name' => $data->last_name,
                ':extension' => $data->extension ?? null,
                ':position' => $data->position ?? null,
                ':gender_id' => $data->gender_id ?? null,
                ':mobile_number' => $data->mobile_number ?? null,
                ':email' => $data->email ?? null,
                ':role_id' => $data->role_id,
                ':branch_id' => $data->branch_id ?? null,
                ':barangay_id' => $data->barangay_id ?? null,
                ':is_active' => $data->is_active
            ]);
            $user_id = $db->lastInsertId();
        }
        
        Response::success(['user_id' => $user_id], "User account " . ($is_update ? "updated" : "created") . " successfully");

    } catch (Exception $e) {
        Response::error("Failed to save user account: " . $e->getMessage(), 500);
    }
