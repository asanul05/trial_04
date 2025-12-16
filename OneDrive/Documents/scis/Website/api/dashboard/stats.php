<?php    
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $accessible_barangays = $auth->getAccessibleBarangays();
    $barangay_ids = implode(',', $accessible_barangays);

    // Total Active Senior Citizens
    $query = "SELECT COUNT(*) as total FROM senior_citizens 
            WHERE is_active = 1 AND is_deceased = 0 
            AND barangay_id IN ($barangay_ids)";
    $stmt = $db->query($query);
    $total_seniors = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Pending Applications
    $query = "SELECT COUNT(*) as total FROM applications a
            JOIN senior_citizens s ON a.senior_id = s.id
            WHERE a.status = 'Pending' 
            AND s.barangay_id IN ($barangay_ids)";
    $stmt = $db->query($query);
    $pending_apps = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // IDs Ready for Claiming
    $query = "SELECT COUNT(*) as total FROM senior_ids si
            JOIN senior_citizens s ON si.senior_id = s.id
            WHERE si.status_id = 1 AND si.release_date IS NULL
            AND s.barangay_id IN ($barangay_ids)";
    $stmt = $db->query($query);
    $claimable_ids = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Released IDs
    $query = "SELECT COUNT(*) as total FROM senior_ids si
            JOIN senior_citizens s ON si.senior_id = s.id
            WHERE si.release_date IS NOT NULL
            AND s.barangay_id IN ($barangay_ids)";
    $stmt = $db->query($query);
    $released_ids = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Age Distribution
    $query = "SELECT 
            CASE 
                WHEN age BETWEEN 60 AND 69 THEN 'Sexagenarian (60-69)'
                WHEN age BETWEEN 70 AND 79 THEN 'Septuagenarian (70-79)'
                WHEN age BETWEEN 80 AND 89 THEN 'Octogenarian (80-89)'
                WHEN age BETWEEN 90 AND 99 THEN 'Nonagenarian (90-99)'
                WHEN age >= 100 THEN 'Centenarian (100+)'
            END as age_group,
            COUNT(*) as count
            FROM senior_citizens
            WHERE is_active = 1 AND is_deceased = 0
            AND barangay_id IN ($barangay_ids)
            GROUP BY age_group";
    $stmt = $db->query($query);
    $age_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recent Registrations (Last 7 days)
    $query = "SELECT COUNT(*) as count, DATE(registration_date) as date
            FROM senior_citizens
            WHERE registration_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            AND barangay_id IN ($barangay_ids)
            GROUP BY DATE(registration_date)
            ORDER BY date DESC";
    $stmt = $db->query($query);
    $recent_registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success([
        'summary' => [
            'total_active_seniors' => (int)$total_seniors,
            'pending_applications' => (int)$pending_apps,
            'claimable_ids' => (int)$claimable_ids,
            'released_ids' => (int)$released_ids
        ],
        'age_distribution' => $age_distribution,
        'recent_registrations' => $recent_registrations
    ], "Dashboard statistics retrieved");
