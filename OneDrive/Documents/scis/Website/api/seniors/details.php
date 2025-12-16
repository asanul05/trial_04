<?php    
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    if (!$auth->checkPermission('senior_citizens', 'can_view')) {
        Response::error("No permission to view senior citizens", 403);
    }

    $senior_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if (!$senior_id) {
        Response::error("Senior ID required", 400);
    }

    // Get senior details
    $query = "SELECT s.*, g.name as gender, b.name as barangay, b.district,
            br.name as branch, rs.name as status, edu.level as education,
            mob.level as mobility, soc.category as socioeconomic,
            c.mobile_number, c.telephone_number, c.email, 
            c.house_number, c.street, c.postal_code
            FROM senior_citizens s
            LEFT JOIN genders g ON s.gender_id = g.id
            LEFT JOIN barangays b ON s.barangay_id = b.id
            LEFT JOIN branches br ON s.branch_id = br.id
            LEFT JOIN registration_statuses rs ON s.registration_status_id = rs.id
            LEFT JOIN educational_attainment edu ON s.educational_attainment_id = edu.id
            LEFT JOIN mobility_levels mob ON s.mobility_level_id = mob.id
            LEFT JOIN socioeconomic_statuses soc ON s.socioeconomic_status_id = soc.id
            LEFT JOIN contacts c ON s.contact_id = c.id
            WHERE s.id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        Response::error("Senior citizen not found", 404);
    }

    $senior = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check access
    if (!$auth->canAccessBarangay($senior['barangay_id'])) {
        Response::error("No access to this senior citizen's data", 403);
    }

    // Get family members
    $query = "SELECT fm.*, c.mobile_number, c.telephone_number
            FROM family_members fm
            LEFT JOIN contacts c ON fm.contact_id = c.id
            WHERE fm.senior_id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id);
    $stmt->execute();
    $family = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get target sectors
    $query = "SELECT ts.code, ts.name, sts.other_specification, sts.enrollment_date
            FROM senior_target_sectors sts
            JOIN target_sectors ts ON sts.sector_id = ts.id
            WHERE sts.senior_id = :id AND sts.is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id);
    $stmt->execute();
    $sectors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get applications
    $query = "SELECT a.*, at.name as type
            FROM applications a
            JOIN application_types at ON a.application_type_id = at.id
            WHERE a.senior_id = :id
            ORDER BY a.created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id);
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get IDs
    $query = "SELECT si.*, ids.name as status_name
            FROM senior_ids si
            JOIN id_statuses ids ON si.status_id = ids.id
            WHERE si.senior_id = :id
            ORDER BY si.issue_date DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $senior_id);
    $stmt->execute();
    $ids = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success([
        'senior' => $senior,
        'family_members' => $family,
        'target_sectors' => $sectors,
        'applications' => $applications,
        'ids' => $ids
    ], "Senior details retrieved");