<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $query = "SELECT id, name FROM genders ORDER BY name";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $genders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($genders, "Genders retrieved successfully");
