<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';
    require_once '../helpers/validation.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));
    $is_update = isset($data->id) && $data->id > 0;

    $permission = $is_update ? 'can_edit' : 'can_create';
    if (!$auth->checkPermission('senior_citizens', $permission)) {
        Response::error("No permission to " . ($is_update ? "update" : "create") . " senior citizens", 403);
        exit;
    }
    
    // For updates, we need to get the senior's current information
    if ($is_update) {
        $query = "SELECT s.*, c.id as contact_id 
                FROM senior_citizens s
                LEFT JOIN contacts c ON s.contact_id = c.id
                WHERE s.id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data->id);
        $stmt->execute();
        $senior = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$senior) {
            Response::error("Senior citizen not found", 404);
            exit;
        }
        
        // Check access
        if (!$auth->canAccessBarangay($senior['barangay_id'])) {
            Response::error("No access to update this senior citizen", 403);
        }
    } else {
        // For create, validate required fields
        $required = ['first_name', 'last_name', 'birthdate', 'gender_id', 'barangay_id'];
        foreach ($required as $field) {
            if (!isset($data->$field) || empty($data->$field)) {
                Response::error("Field $field is required", 400);
            }
        }
        // Check barangay access for create
        if (!$auth->canAccessBarangay($data->barangay_id)) {
            Response::error("No access to create seniors in this barangay", 403);
        }
    }


    try {
        $db->beginTransaction();
        
        $update_type = $data->update_type ?? ($is_update ? 'full' : 'create');
        $old_values = [];
        $new_values = [];
        
        if ($is_update) {
            $senior_id = $data->id;

            // ==================================================
            // ADDRESS CHANGE
            // ==================================================
            if ($update_type === 'address' && isset($data->address)) {
                $addr = $data->address;
            
                // Store old address
                $old_values['address'] = [
                    'house_number' => $senior['house_number'] ?? null,
                    'street' => $senior['street'] ?? null,
                    'barangay_id' => $senior['barangay_id'],
                    'barangay_name' => $senior['barangay_name'] ?? null
                ];
                
                // Update contact table if exists
                if ($senior['contact_id']) {
                    $query = "UPDATE contacts SET 
                            house_number = :house,
                            street = :street,
                            barangay_id = :barangay
                            WHERE id = :contact_id";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        ':house' => $addr->house_number ?? null,
                        ':street' => $addr->street ?? null,
                        ':barangay' => $addr->barangay_id,
                        ':contact_id' => $senior['contact_id']
                    ]);
                }
                
                // Update senior_citizens table
                $query = "UPDATE senior_citizens SET 
                        barangay_id = :barangay
                        WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':barangay' => $addr->barangay_id,
                    ':id' => $senior_id
                ]);
                
                $new_values['address'] = [
                    'house_number' => $addr->house_number ?? null,
                    'street' => $addr->street ?? null,
                    'barangay_id' => $addr->barangay_id
                ];
                
                // Re-calculate benefits if moving to different barangay
                if ($senior['barangay_id'] != $addr->barangay_id) {
                    // Remove old barangay-specific benefits
                    $query = "DELETE seb FROM senior_eligible_benefits seb
                            JOIN benefits b ON seb.benefit_id = b.id
                            WHERE seb.senior_id = :senior_id
                            AND b.is_barangay_specific = 1
                            AND b.barangay_id != :new_barangay";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        ':senior_id' => $senior_id,
                        ':new_barangay' => $addr->barangay_id
                    ]);
                    
                    // Add new barangay-specific benefits
                    $query = "INSERT INTO senior_eligible_benefits (senior_id, benefit_id, eligible_from)
                            SELECT :senior_id, id, CURDATE()
                            FROM benefits
                            WHERE is_barangay_specific = 1
                            AND barangay_id = :new_barangay
                            AND is_active = 1";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        ':senior_id' => $senior_id,
                        ':new_barangay' => $addr->barangay_id
                    ]);
                }
            }
            
            // ==================================================
            // DECEASED STATUS UPDATE
            // ==================================================
            else if ($update_type === 'deceased' && isset($data->deceased)) {
                $old_values['status'] = [
                    'is_deceased' => $senior['is_deceased'],
                    'deceased_date' => $senior['deceased_date']
                ];
                
                $query = "UPDATE senior_citizens SET 
                        is_deceased = 1,
                        deceased_date = :deceased_date,
                        is_active = 0
                        WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':deceased_date' => $data->deceased->date,
                    ':id' => $senior_id
                ]);
                
                $new_values['status'] = [
                    'is_deceased' => 1,
                    'deceased_date' => $data->deceased->date
                ];
                
                // Cancel all pending benefit transactions
                $query = "UPDATE benefit_transactions 
                        SET status = 'cancelled',
                            notes = CONCAT(COALESCE(notes, ''), '\nCancelled: Senior citizen deceased')
                        WHERE senior_id = :senior_id 
                        AND status = 'pending'";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':senior_id', $senior_id);
                $stmt->execute();
            }
            
            // ==================================================
            // GENERAL INFORMATION UPDATE
            // ==================================================
            else if ($update_type === 'general' && isset($data->updates)) {
                $upd = $data->updates;
            
                $allowed_fields = [
                    'educational_attainment_id', 'monthly_salary', 'occupation',
                    'other_skills', 'socioeconomic_status_id', 'mobility_level_id'
                ];
                
                $set_clauses = [];
                $params = [':id' => $senior_id];
                
                foreach ($allowed_fields as $field) {
                    if (isset($upd->$field)) {
                        $set_clauses[] = "$field = :$field";
                        $params[":$field"] = $upd->$field;
                        $old_values[$field] = $senior[$field];
                        $new_values[$field] = $upd->$field;
                    }
                }
                
                if (!empty($set_clauses)) {
                    $query = "UPDATE senior_citizens SET " . implode(', ', $set_clauses) . 
                            " WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->execute($params);
                }
            }

            // ==================================================
            // FULL UPDATE
            // ==================================================
            else { // 'full' update
                // Handle contact info
                $contact_id = $senior['contact_id'] ?? null;
                if (isset($data->contact)) {
                    if ($contact_id) {
                        // Update existing contact
                        $query = "UPDATE contacts SET mobile_number = :mobile, telephone_number = :telephone, email = :email, house_number = :house, street = :street, barangay_id = :barangay WHERE id = :id";
                        $stmt = $db->prepare($query);
                        $stmt->execute([
                            ':mobile' => $data->contact->mobile_number ?? null,
                            ':telephone' => $data->contact->telephone_number ?? null,
                            ':email' => $data->contact->email ?? null,
                            ':house' => $data->contact->house_number ?? null,
                            ':street' => $data->contact->street ?? null,
                            ':barangay' => $data->barangay_id,
                            ':id' => $contact_id
                        ]);
                    } else {
                        // Insert new contact
                        $query = "INSERT INTO contacts (mobile_number, telephone_number, email, house_number, street, barangay_id) VALUES (:mobile, :telephone, :email, :house, :street, :barangay)";
                        $stmt = $db->prepare($query);
                        $stmt->execute([
                            ':mobile' => $data->contact->mobile_number ?? null,
                            ':telephone' => $data->contact->telephone_number ?? null,
                            ':email' => $data->contact->email ?? null,
                            ':house' => $data->contact->house_number ?? null,
                            ':street' => $data->contact->street ?? null,
                            ':barangay' => $data->barangay_id
                        ]);
                        $contact_id = $db->lastInsertId();
                    }
                }
                
                // Update senior
                $query = "UPDATE senior_citizens SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, extension = :extension, birthdate = :birthdate, gender_id = :gender_id, barangay_id = :barangay_id, educational_attainment_id = :education, monthly_salary = :salary, occupation = :occupation, other_skills = :skills, socioeconomic_status_id = :socioeconomic, mobility_level_id = :mobility, contact_id = :contact_id WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':first_name' => $data->first_name,
                    ':middle_name' => $data->middle_name ?? null,
                    ':last_name' => $data->last_name,
                    ':extension' => $data->extension ?? null,
                    ':birthdate' => $data->birthdate,
                    ':gender_id' => $data->gender_id,
                    ':barangay_id' => $data->barangay_id,
                    ':education' => $data->educational_attainment_id ?? null,
                    ':salary' => $data->monthly_salary ?? null,
                    ':occupation' => $data->occupation ?? null,
                    ':skills' => $data->other_skills ?? null,
                    ':socioeconomic' => $data->socioeconomic_status_id ?? null,
                    ':mobility' => $data->mobility_level_id ?? null,
                    ':contact_id' => $contact_id,
                    ':id' => $data->id
                ]);
            }
        } else { // Create new senior
            // Generate OSCA ID
            $year = date('Y');
            $query = "SELECT MAX(CAST(SUBSTRING(osca_id, -6) AS UNSIGNED)) as last_num 
                    FROM senior_citizens 
                    WHERE osca_id LIKE 'ZC-$year-%'";
            $stmt = $db->query($query);
            $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
            $new_num = str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
            $osca_id = "ZC-$year-$new_num";
            
            // Insert new contact
            $contact_id = null;
            if (isset($data->contact)) {
                $query = "INSERT INTO contacts (mobile_number, telephone_number, email, 
                        house_number, street, barangay_id) 
                        VALUES (:mobile, :telephone, :email, :house, :street, :barangay)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':mobile' => $data->contact->mobile_number ?? null,
                    ':telephone' => $data->contact->telephone_number ?? null,
                    ':email' => $data->contact->email ?? null,
                    ':house' => $data->contact->house_number ?? null,
                    ':street' => $data->contact->street ?? null,
                    ':barangay' => $data->barangay_id
                ]);
                $contact_id = $db->lastInsertId();
            }

            // Insert new senior
            $query = "INSERT INTO senior_citizens 
                    (osca_id, first_name, middle_name, last_name, extension, birthdate,
                    gender_id, barangay_id, contact_id, educational_attainment_id,
                    monthly_salary, occupation, other_skills, socioeconomic_status_id,
                    mobility_level_id, registration_date, registration_status_id,
                    registered_by, branch_id)
                    VALUES 
                    (:osca_id, :first_name, :middle_name, :last_name, :extension, :birthdate,
                    :gender_id, :barangay_id, :contact_id, :education, :salary, :occupation,
                    :skills, :socioeconomic, :mobility, NOW(), 1, :user_id, :branch_id)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':osca_id' => $osca_id,
                ':first_name' => $data->first_name,
                ':middle_name' => $data->middle_name ?? null,
                ':last_name' => $data->last_name,
                ':extension' => $data->extension ?? null,
                ':birthdate' => $data->birthdate,
                ':gender_id' => $data->gender_id,
                ':barangay_id' => $data->barangay_id,
                ':contact_id' => $contact_id,
                ':education' => $data->educational_attainment_id ?? null,
                ':salary' => $data->monthly_salary ?? null,
                ':occupation' => $data->occupation ?? null,
                ':skills' => $data->other_skills ?? null,
                ':socioeconomic' => $data->socioeconomic_status_id ?? null,
                ':mobility' => $data->mobility_level_id ?? null,
                ':user_id' => $auth->getUserId(),
                ':branch_id' => $auth->getBranchId()
            ]);
            $senior_id = $db->lastInsertId();
        }
        
        // ==================================================
        // LOG ACTIVITY
        // ==================================================
        if ($is_update) {
            $query = "INSERT INTO activity_logs (user_id, action, module, record_id, description, old_values, new_values) VALUES (:user_id, 'UPDATE', 'senior_citizens', :senior_id, :description, :old_values, :new_values)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':user_id' => $auth->getUserId(),
                ':senior_id' => $senior_id,
                ':description' => "Updated senior citizen: " . ucfirst($update_type) . " change",
                ':old_values' => json_encode($old_values),
                ':new_values' => json_encode($new_values)
            ]);
        }

        $db->commit();
        
        Response::success(
            ['senior_id' => $senior_id], 
            "Senior citizen " . ($is_update ? "updated" : "created") . " successfully"
        );
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to save senior citizen: " . $e->getMessage(), 500);
    }