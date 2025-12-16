<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $query = "SELECT id, category as name FROM socioeconomic_statuses ORDER BY category";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $socioeconomic_statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($socioeconomic_statuses, "Socioeconomic statuses retrieved successfully");
