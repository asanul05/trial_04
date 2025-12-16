<?php
    require_once '../config/database.php';
    require_once '../middleware/auth.php';
    require_once '../helpers/response.php';
    require_once '../helpers/age_calculator.php';

    $database = new Database();
    $db = $database->getConnection();

    $auth = new AuthMiddleware($db);
    if (!$auth->authenticate()) exit;

    $data = json_decode(file_get_contents("php://input"));

    // Validate required fields
    $required = ['application_type_id', 'personal_info'];
    foreach ($required as $field) {
        if (!isset($data->$field)) {
            Response::error("Field $field is required", 400);
        }
    }

    $personal = $data->personal_info;

    // AGE VALIDATION - CRITICAL: Must be 60+
    $birthdate = $personal->birthdate;
    $age = AgeCalculator::calculateAge($birthdate);

    if ($age < 60) {
        Response::error(
            "Applicant must be at least 60 years old to register. Current age: $age years. " .
            "Senior Citizen registration is only available for individuals aged 60 and above per RA 7432.",
            400,
            ['age' => $age, 'minimum_required' => 60]
        );
    }

    try {
        $db->beginTransaction();
        
        // Step 1: Create/Update Contact Information
        $contact_id = null;
        if (isset($data->contact_info)) {
            $contact = $data->contact_info;
            $query = "INSERT INTO contacts 
                    (mobile_number, telephone_number, email, house_number, 
                    street, barangay_id, city, postal_code)
                    VALUES (:mobile, :telephone, :email, :house, :street, 
                            :barangay, :city, :postal)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':mobile' => $contact->mobile_number ?? null,
                ':telephone' => $contact->telephone_number ?? null,
                ':email' => $contact->email ?? null,
                ':house' => $contact->house_number ?? null, // OPTIONAL
                ':street' => $contact->street ?? null,
                ':barangay' => $personal->barangay_id,
                ':city' => 'Zamboanga City',
                ':postal' => '7000'
            ]);
            $contact_id = $db->lastInsertId();
        }
        
        // Step 2: Check if senior citizen already exists
        $senior_id = null;
        
        if (isset($data->senior_id) && $data->senior_id > 0) {
            // Updating existing senior
            $senior_id = $data->senior_id;
        } else {
            // Create new senior citizen record
            // Generate OSCA ID
            $year = date('Y');
            $query = "SELECT MAX(CAST(SUBSTRING(osca_id, -6) AS UNSIGNED)) as last_num 
                    FROM senior_citizens 
                    WHERE osca_id LIKE 'ZC-$year-%'";
            $stmt = $db->query($query);
            $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
            $new_num = str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
            $osca_id = "ZC-$year-$new_num";
            
            $query = "INSERT INTO senior_citizens 
                    (osca_id, first_name, middle_name, last_name, extension,
                    birthdate, gender_id, barangay_id, branch_id, contact_id,
                    educational_attainment_id, monthly_salary, occupation, 
                    other_skills, socioeconomic_status_id, mobility_level_id,
                    registration_date, registration_status_id, registered_by,
                    photo_path, thumbmark_verified)
                    VALUES 
                    (:osca_id, :first_name, :middle_name, :last_name, :extension,
                    :birthdate, :gender_id, :barangay_id, :branch_id, :contact_id,
                    :education, :salary, :occupation, :skills, :socioeconomic,
                    :mobility, NOW(), 1, :registered_by, :photo, :thumbmark)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':osca_id' => $osca_id,
                ':first_name' => $personal->first_name,
                ':middle_name' => $personal->middle_name ?? null,
                ':last_name' => $personal->last_name,
                ':extension' => $personal->extension ?? null,
                ':birthdate' => $birthdate,
                ':gender_id' => $personal->gender_id,
                ':barangay_id' => $personal->barangay_id,
                ':branch_id' => $auth->getBranchId(),
                ':contact_id' => $contact_id,
                ':education' => $personal->educational_attainment_id ?? null,
                ':salary' => $personal->monthly_salary ?? null,
                ':occupation' => $personal->occupation ?? null,
                ':skills' => $personal->other_skills ?? null,
                ':socioeconomic' => $personal->socioeconomic_status_id ?? null,
                ':mobility' => $personal->mobility_level_id ?? null,
                ':registered_by' => $auth->getUserId(),
                ':photo' => $data->photo_path ?? null,
                ':thumbmark' => isset($data->thumbmark_verified) ? 1 : 0
            ]);
            
            $senior_id = $db->lastInsertId();
        }
        
        // Step 3: Create Application
        $year = date('Y');
        $query = "SELECT MAX(CAST(SUBSTRING(application_number, -6) AS UNSIGNED)) as last_num 
                FROM applications 
                WHERE application_number LIKE 'APP-$year-%'";
        $stmt = $db->query($query);
        $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
        $new_num = str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
        $application_number = "APP-$year-$new_num";
        
        $is_draft = isset($data->is_draft) && $data->is_draft === true;
        $status = $is_draft ? 'Draft' : 'Submitted';
        $submission_date = $is_draft ? null : date('Y-m-d H:i:s');
        
        $query = "INSERT INTO applications 
                (application_number, senior_id, application_type_id, status,
                submitted_by, submission_date, notes)
                VALUES 
                (:app_number, :senior_id, :type_id, :status, :submitted_by,
                :submission_date, :notes)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':app_number' => $application_number,
            ':senior_id' => $senior_id,
            ':type_id' => $data->application_type_id,
            ':status' => $status,
            ':submitted_by' => $auth->getUserId(),
            ':submission_date' => $submission_date,
            ':notes' => $data->notes ?? null
        ]);
        
        $application_id = $db->lastInsertId();
        
        // Step 4: Handle Family Members
        if (isset($data->family_members) && is_array($data->family_members)) {
            $family_query = "INSERT INTO family_members 
                            (senior_id, first_name, middle_name, last_name, 
                            extension, relationship, age, monthly_salary, contact_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $family_stmt = $db->prepare($family_query);
            
            foreach ($data->family_members as $member) {
                // Calculate age from birthdate
                $member_age = AgeCalculator::calculateAge($member->birthdate);
                
                // Check for KIA/WIA status
                $is_kia_wia = isset($member->is_kia_wia) && $member->is_kia_wia;
                
                $family_stmt->execute([
                    $senior_id,
                    $member->first_name,
                    $member->middle_name ?? null,
                    $member->last_name,
                    $member->extension ?? null,
                    $member->relationship . ($is_kia_wia ? ' (KIA/WIA)' : ''),
                    $member_age,
                    $member->monthly_salary ?? null,
                    null // contact_id if needed
                ]);
            }
        }
        
        // Step 5: Handle Target Sectors
        if (isset($data->target_sectors) && is_array($data->target_sectors)) {
            $sector_query = "INSERT INTO senior_target_sectors 
                            (senior_id, sector_id, other_specification, enrollment_date, is_active)
                            VALUES (?, ?, ?, CURDATE(), 1)";
            $sector_stmt = $db->prepare($sector_query);
            
            foreach ($data->target_sectors as $sector) {
                $sector_stmt->execute([
                    $senior_id,
                    $sector->sector_id,
                    $sector->other_specification ?? null
                ]);
            }
        }
        
        // Step 6: Handle Document Uploads
        if (isset($data->documents) && is_array($data->documents)) {
            $doc_query = "INSERT INTO application_documents 
                        (application_id, document_type_id, file_path, 
                        original_filename, file_size, uploaded_by)
                        VALUES (?, ?, ?, ?, ?, ?)";
            $doc_stmt = $db->prepare($doc_query);
            
            foreach ($data->documents as $doc) {
                $doc_stmt->execute([
                    $application_id,
                    $doc->document_type_id,
                    $doc->file_path,
                    $doc->original_filename,
                    $doc->file_size ?? null,
                    $auth->getUserId()
                ]);
            }
        }
        
        // Step 7: Calculate and assign eligible benefits based on age
        $benefits = BenefitCalculator::calculateEligibleBenefits(
            $age, 
            $personal->barangay_id
        );
        
        if (!empty($benefits)) {
            $benefit_query = "INSERT INTO senior_eligible_benefits 
                            (senior_id, benefit_id, eligible_from, eligible_until)
                            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR))";
            $benefit_stmt = $db->prepare($benefit_query);
            
            foreach ($benefits as $benefit_id) {
                $benefit_stmt->execute([$senior_id, $benefit_id]);
            }
        }
        
        $db->commit();
        
        Response::success([
            'application_id' => $application_id,
            'application_number' => $application_number,
            'senior_id' => $senior_id,
            'osca_id' => $osca_id ?? null,
            'status' => $status,
            'eligible_benefits' => $benefits
        ], "Application " . ($is_draft ? "saved as draft" : "submitted") . " successfully");
        
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Failed to create application: " . $e->getMessage(), 500);
    }

    if (!$auth->checkPermission('applications', 'can_create')) {
    Response::error("No permission to create applications", 403);
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->senior_id) || !isset($data->application_type_id)) {
    Response::error("Senior ID and application type required", 400);
}

try {
    $db->beginTransaction();
    
    // Verify senior exists and user has access
    $query = "SELECT barangay_id FROM senior_citizens WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data->senior_id);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        Response::error("Senior citizen not found", 404);
    }
    
    $senior = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$auth->canAccessBarangay($senior['barangay_id'])) {
        Response::error("No access to create application for this senior", 403);
    }
    
    // Generate application number
    $year = date('Y');
    $query = "SELECT MAX(CAST(SUBSTRING(application_number, -4) AS UNSIGNED)) as last_num 
              FROM applications 
              WHERE application_number LIKE 'APP-$year-%'";
    $stmt = $db->query($query);
    $last_num = $stmt->fetch(PDO::FETCH_ASSOC)['last_num'] ?? 0;
    $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
    $app_number = "APP-$year-$new_num";
    
    // Create application
    $status = isset($data->status) ? $data->status : 'Draft';
    $query = "INSERT INTO applications 
              (application_number, senior_id, application_type_id, status, 
               submitted_by, notes, created_at)
              VALUES (:app_num, :senior_id, :type_id, :status, :user_id, :notes, NOW())";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':app_num' => $app_number,
        ':senior_id' => $data->senior_id,
        ':type_id' => $data->application_type_id,
        ':status' => $status,
        ':user_id' => $auth->getUserId(),
        ':notes' => $data->notes ?? null
    ]);
    
    $application_id = $db->lastInsertId();
    
    // If submitting (not draft), update submission date
    if ($status !== 'Draft') {
        $query = "UPDATE applications 
                  SET submission_date = NOW() 
                  WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $application_id);
        $stmt->execute();
    }
    
    $db->commit();
    
    Response::success([
        'application_id' => $application_id,
        'application_number' => $app_number
    ], "Application created successfully", 201);
    
} catch (Exception $e) {
    $db->rollBack();
    Response::error("Failed to create application: " . $e->getMessage(), 500);
}