<?php    
require_once '../config/database.php';
require_once '../middleware/auth.php';
require_once '../helpers/response.php';
require_once '../helpers/cache.php';

$database = new Database();
$db = $database->getConnection();

$auth = new AuthMiddleware($db);
if (!$auth->authenticate()) exit;

if (!$auth->checkPermission('senior_citizens', 'can_view')) {
    Response::error("No permission to view senior citizens", 403);
}

$senior_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$force_refresh = isset($_GET['refresh']) && $_GET['refresh'] === 'true';

if (!$senior_id) {
    Response::error("Senior ID required", 400);
}

try {
    // Initialize cache with 5 minute TTL
    $cache = new SimpleCache(300);
    $cache_key = "senior_details_{$senior_id}";
    
    // Check if we should use cache
    if (!$force_refresh && $cached_data = $cache->get($cache_key)) {
        $cached_data['meta']['cached'] = true;
        $cached_data['meta']['cache_time'] = date('Y-m-d H:i:s');
        Response::success($cached_data, "Senior details retrieved from cache");
        exit;
    }
    
    $start_time = microtime(true);
    
    // Main query - optimized with proper JOINs
    $query = "SELECT 
        s.id, s.osca_id, s.first_name, s.middle_name, s.last_name, s.extension,
        s.birthdate, s.age, s.gender_id, s.contact_id, s.educational_attainment_id,
        s.monthly_salary, s.occupation, s.other_skills, s.socioeconomic_status_id,
        s.mobility_level_id, s.barangay_id, s.branch_id, s.registration_date,
        s.registration_status_id, s.registered_by, s.photo_path, s.thumbmark_verified,
        s.is_active, s.is_deceased, s.deceased_date, s.created_at, s.updated_at,
        g.name as gender,
        b.name as barangay, b.district, b.code as barangay_code,
        br.name as branch, br.code as branch_code,
        rs.name as status, rs.color_code as status_color,
        edu.level as education,
        mob.level as mobility,
        soc.category as socioeconomic,
        c.mobile_number, c.telephone_number, c.email, 
        c.house_number, c.street, c.postal_code
    FROM senior_citizens s
    FORCE INDEX (PRIMARY)
    INNER JOIN genders g ON s.gender_id = g.id
    INNER JOIN barangays b ON s.barangay_id = b.id
    INNER JOIN registration_statuses rs ON s.registration_status_id = rs.id
    LEFT JOIN branches br ON s.branch_id = br.id
    LEFT JOIN educational_attainment edu ON s.educational_attainment_id = edu.id
    LEFT JOIN mobility_levels mob ON s.mobility_level_id = mob.id
    LEFT JOIN socioeconomic_statuses soc ON s.socioeconomic_status_id = soc.id
    LEFT JOIN contacts c ON s.contact_id = c.id
    WHERE s.id = :id
    LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        Response::error("Senior citizen not found", 404);
    }

    $senior = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check access
    if (!$auth->canAccessBarangay($senior['barangay_id'])) {
        Response::error("No access to this senior citizen's data", 403);
    }

    // Parallel data fetching using array of queries
    $related_data = [];
    
    // Family members
    $query = "SELECT 
        fm.id, fm.first_name, fm.middle_name, fm.last_name, fm.extension,
        fm.relationship, fm.age, fm.monthly_salary,
        c.mobile_number, c.telephone_number, c.email
    FROM family_members fm
    LEFT JOIN contacts c ON fm.contact_id = c.id
    WHERE fm.senior_id = :id
    ORDER BY fm.relationship";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id, PDO::PARAM_INT);
    $stmt->execute();
    $related_data['family_members'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Target sectors
    $query = "SELECT 
        ts.id, ts.code, ts.name, ts.description,
        sts.other_specification, sts.enrollment_date, sts.is_active
    FROM senior_target_sectors sts
    INNER JOIN target_sectors ts ON sts.sector_id = ts.id
    WHERE sts.senior_id = :id AND sts.is_active = 1
    ORDER BY ts.name";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id, PDO::PARAM_INT);
    $stmt->execute();
    $related_data['target_sectors'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Applications
    $query = "SELECT 
        a.id, a.application_number, a.application_type_id, a.status,
        a.submission_date, a.verification_date, a.approval_date,
        a.print_date, a.claim_date, a.notes, a.created_at,
        at.name as type, at.description as type_description
    FROM applications a
    INNER JOIN application_types at ON a.application_type_id = at.id
    WHERE a.senior_id = :id
    ORDER BY a.created_at DESC
    LIMIT 20";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id, PDO::PARAM_INT);
    $stmt->execute();
    $related_data['applications'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Senior IDs
    $query = "SELECT 
        si.id, si.id_number, si.qr_code, si.issue_date, si.expiry_date,
        si.print_date, si.release_date, si.notes,
        ids.name as status_name, ids.description as status_description
    FROM senior_ids si
    INNER JOIN id_statuses ids ON si.status_id = ids.id
    WHERE si.senior_id = :id
    ORDER BY si.issue_date DESC
    LIMIT 10";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id, PDO::PARAM_INT);
    $stmt->execute();
    $related_data['ids'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query_time = microtime(true) - $start_time;

    // Prepare response data
    $response_data = [
        'senior' => $senior,
        'family_members' => $related_data['family_members'],
        'target_sectors' => $related_data['target_sectors'],
        'applications' => $related_data['applications'],
        'ids' => $related_data['ids'],
        'meta' => [
            'cached' => false,
            'query_time' => round($query_time * 1000, 2) . 'ms',
            'queries_executed' => 5,
            'cache_expires' => date('Y-m-d H:i:s', time() + 300)
        ]
    ];

    // Cache the response
    $cache->set($cache_key, $response_data);

    // Return response
    Response::success($response_data, "Senior details retrieved");

} catch (PDOException $e) {
    error_log("Database error in details.php: " . $e->getMessage());
    Response::error("Database error occurred", 500);
} catch (Exception $e) {
    error_log("Error in details.php: " . $e->getMessage());
    Response::error("An error occurred", 500);
}