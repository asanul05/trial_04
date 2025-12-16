<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $query = "SELECT id, name FROM complaint_categories ORDER BY name";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($categories, "Complaint categories retrieved successfully");
