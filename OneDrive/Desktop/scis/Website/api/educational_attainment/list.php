<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $query = "SELECT id, level as name FROM educational_attainment ORDER BY level";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $educational_attainment = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($educational_attainment, "Educational attainment levels retrieved successfully");
