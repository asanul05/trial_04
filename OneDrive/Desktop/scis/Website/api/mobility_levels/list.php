<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $query = "SELECT id, level as name FROM mobility_levels ORDER BY level";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $mobility_levels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($mobility_levels, "Mobility levels retrieved successfully");
