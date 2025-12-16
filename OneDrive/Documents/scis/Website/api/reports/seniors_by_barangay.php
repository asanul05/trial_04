<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('reports', 'can_view')) {
        Response::error("No permission to view reports", 403);
    }

    $accessible_barangays = $auth->getAccessibleBarangays();
    $barangay_ids = implode(',', $accessible_barangays);

    $query = "SELECT b.name as barangay, b.district,
            COUNT(DISTINCT s.id) as total_seniors,
            COUNT(DISTINCT CASE WHEN s.gender_id = 1 THEN s.id END) as male_count,
            COUNT(DISTINCT CASE WHEN s.gender_id = 2 THEN s.id END) as female_count,
            AVG(s.age) as average_age,
            COUNT(DISTINCT CASE WHEN s.age BETWEEN 70 AND 79 THEN s.id END) as septuagenarian,
            COUNT(DISTINCT CASE WHEN s.age BETWEEN 80 AND 89 THEN s.id END) as octogenarian,
            COUNT(DISTINCT CASE WHEN s.age BETWEEN 90 AND 99 THEN s.id END) as nonagenarian,
            COUNT(DISTINCT CASE WHEN s.age >= 100 THEN s.id END) as centenarian
            FROM barangays b
            LEFT JOIN senior_citizens s ON b.id = s.barangay_id 
                AND s.is_active = 1 AND s.is_deceased = 0
            WHERE b.id IN ($barangay_ids)
            GROUP BY b.id, b.name, b.district
            ORDER BY b.name";

    $stmt = $db->query($query);
    $report = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($report, "Senior statistics by barangay retrieved");
