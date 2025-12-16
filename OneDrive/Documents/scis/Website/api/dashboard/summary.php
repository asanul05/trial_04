<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $summary = [];

    // Get total active seniors
    $query = "SELECT COUNT(*) as total FROM senior_citizens WHERE is_active = 1";
    $stmt = $db->query($query);
    $summary['active_seniors'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get pending applications
    $query = "SELECT COUNT(*) as total FROM applications WHERE status = 'Pending'";
    $stmt = $db->query($query);
    $summary['pending_applications'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get claimable IDs
    $query = "SELECT COUNT(*) as total FROM applications WHERE status = 'Printed'";
    $stmt = $db->query($query);
    $summary['claimable_ids'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get released IDs
    $query = "SELECT COUNT(*) as total FROM senior_ids WHERE status_id = 1"; // Assuming 1 is 'Active/Released'
    $stmt = $db->query($query);
    $summary['released_ids'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get upcoming events
    $query = "SELECT a.*, at.name as type_name, CONCAT(u.first_name, ' ', u.last_name) as created_by_name
            FROM announcements a
            JOIN announcement_types at ON a.type_id = at.id
            JOIN admin_users u ON a.created_by = u.id
            WHERE a.is_published = 1 AND a.event_date >= CURDATE()
            ORDER BY a.event_date ASC, a.created_at ASC
            LIMIT 5";
    $stmt = $db->query($query);
    $summary['upcoming_events'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($summary, "Dashboard summary retrieved");
