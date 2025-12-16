<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    // Get only accessible barangays for the user
    $accessible_barangays = $auth->getAccessibleBarangays();
    $barangay_ids = implode(',', $accessible_barangays);

    $query = "SELECT id, code, name, district, city 
            FROM barangays 
            WHERE id IN ($barangay_ids)
            ORDER BY name";
    $stmt = $db->query($query);
    $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($barangays, "Barangays retrieved");
