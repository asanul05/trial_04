<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = [];

    // Genders
    $stmt = $db->query("SELECT * FROM genders ORDER BY name");
    $data['genders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Educational Attainment
    $stmt = $db->query("SELECT * FROM educational_attainment ORDER BY id");
    $data['educational_attainment'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mobility Levels
    $stmt = $db->query("SELECT * FROM mobility_levels ORDER BY id");
    $data['mobility_levels'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Socioeconomic Statuses
    $stmt = $db->query("SELECT * FROM socioeconomic_statuses ORDER BY id");
    $data['socioeconomic_statuses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Target Sectors
    $stmt = $db->query("SELECT * FROM target_sectors ORDER BY name");
    $data['target_sectors'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Application Types
    $stmt = $db->query("SELECT * FROM application_types ORDER BY name");
    $data['application_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Document Types
    $stmt = $db->query("SELECT * FROM document_types ORDER BY name");
    $data['document_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Complaint Categories
    $stmt = $db->query("SELECT * FROM complaint_categories ORDER BY name");
    $data['complaint_categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Announcement Types
    $stmt = $db->query("SELECT * FROM announcement_types ORDER BY name");
    $data['announcement_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($data, "All reference data retrieved");