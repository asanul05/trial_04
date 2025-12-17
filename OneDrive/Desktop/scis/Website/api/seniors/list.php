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

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $barangay = isset($_GET['barangay']) ? (int)$_GET['barangay'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $age_group = isset($_GET['age_group']) ? $_GET['age_group'] : null;

    $where_clauses = ["1=1"];
    $params_to_bind = [];

    $accessible_barangays = $auth->getAccessibleBarangays();
    
    // Handle the case where accessible_barangays might be empty or role requires all
    if ($auth->getRoleId() == 1) { // Main Admin sees all
        // No barangay filtering needed
    } elseif (!empty($accessible_barangays)) { // Filter by accessible barangays for other roles
        $placeholders = implode(',', array_fill(0, count($accessible_barangays), '?'));
        $where_clauses[] = "s.barangay_id IN ($placeholders)";
        $params_to_bind = array_merge($params_to_bind, $accessible_barangays);
    } else { // No accessible barangays for this role, so no results
        Response::paginated([], $page, $limit, 0, "No senior citizens accessible");
    }

    if ($search) {
        $where_clauses[] = "(s.first_name LIKE :search 
                            OR s.last_name LIKE :search 
                            OR s.osca_id LIKE :search)";
        $params_to_bind[':search'] = '%' . $search . '%';
    }

    if ($barangay) {
        if (!$auth->canAccessBarangay($barangay)) {
            Response::error("No access to this barangay", 403);
        }
        $where_clauses[] = "s.barangay_id = :barangay";
        $params_to_bind[':barangay'] = $barangay;
    }

    if ($status) {
        $where_clauses[] = "rs.name = :status";
        $params_to_bind[':status'] = $status;
    }

    if ($age_group) {
        switch($age_group) {
            case 'septuagenarian':
                $where_clauses[] = "s.age BETWEEN 70 AND 79";
                break;
            case 'octogenarian':
                $where_clauses[] = "s.age BETWEEN 80 AND 89";
                break;
            case 'nonagenarian':
                $where_clauses[] = "s.age BETWEEN 90 AND 99";
                break;
            case 'centenarian':
                $where_clauses[] = "s.age >= 100";
                break;
        }
    }

    $where = "WHERE " . implode(" AND ", $where_clauses);

    $where = "WHERE " . implode(" AND ", $where_clauses);

    // Count query
    $count_query = "SELECT COUNT(*) as total FROM senior_citizens s
                    JOIN registration_statuses rs ON s.registration_status_id = rs.id
                    $where";
    $stmt = $db->prepare($count_query);
    $param_index = 1;
    foreach ($params_to_bind as $key => $value) {
        if (is_int($key)) { // Positional parameter (e.g., from barangay_id IN (...))
            $stmt->bindValue($param_index++, $value, PDO::PARAM_INT);
        } else { // Named parameter (e.g., :search, :barangay, :status)
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Data query
    $query = "SELECT s.id, s.osca_id, s.first_name, s.middle_name, s.last_name, 
            s.extension, s.age, s.birthdate, g.name as gender, b.name as barangay,
            br.name as branch, rs.name as status, s.registration_date,
            c.mobile_number, c.email
            FROM senior_citizens s
            LEFT JOIN genders g ON s.gender_id = g.id
            LEFT JOIN barangays b ON s.barangay_id = b.id
            LEFT JOIN branches br ON s.branch_id = br.id
            LEFT JOIN registration_statuses rs ON s.registration_status_id = rs.id
            LEFT JOIN contacts c ON s.contact_id = c.id
            $where
            ORDER BY s.created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $param_index = 1;
    foreach ($params_to_bind as $key => $value) {
        if (is_int($key)) { // Positional parameter (e.g., from barangay_id IN (...))
            $stmt->bindValue($param_index++, $value, PDO::PARAM_INT);
        } else { // Named parameter (e.g., :search, :barangay, :status)
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $seniors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::paginated($seniors, $page, $limit, $total, "Seniors retrieved successfully");