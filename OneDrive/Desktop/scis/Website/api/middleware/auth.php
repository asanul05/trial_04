<?php    
    session_start();
    require_once dirname(__DIR__) . '/helpers/response.php'; // Ensure Response helper is available

    class AuthMiddleware {
        private $db;
        private $user_id;
        private int $role_id;
        private $branch_id;
        private $barangay_id;
        
        public function __construct($db) {
            $this->db = $db;
        }
        
        public function authenticate() {
            if (!isset($_SESSION['user'])) {
                return $this->unauthorized("Unauthorized access: User not logged in.");
            }
            
            $user_session = $_SESSION['user'];

            if (!isset($user_session['id']) || !isset($user_session['role_id'])) {
                return $this->unauthorized("Unauthorized access: User session is incomplete.");
            }

            $this->user_id = $user_session['id'];
            $this->role_id = $user_session['role_id'];
            $this->branch_id = $user_session['branch_id'] ?? null;
            $this->barangay_id = $user_session['barangay_id'] ?? null;
            
            // Log access
            try {
                $this->logAccess();
            } catch (PDOException $e) {
                Response::error("Authentication log failed: " . $e->getMessage(), 500);
            }
            
            return true;
        }
        
        public function checkPermission($module, $action) {
            try {
                $query = "SELECT " . $action . " as has_permission 
                        FROM user_permissions 
                        WHERE role_id = :role_id AND module = :module";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':role_id', $this->role_id);
                $stmt->bindParam(':module', $module);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row && $row['has_permission'];
            } catch (PDOException $e) {
                Response::error("Permission check failed: " . $e->getMessage(), 500);
                exit();
            }
        }
        
        public function canAccessBarangay($barangay_id) {
            // Main admin can access all
            if ($this->role_id == 1) {
                return true;
            }
            
            // Barangay admin can only access their barangay
            if ($this->role_id == 3) {
                return $this->barangay_id == $barangay_id;
            }
            
            // Branch admin can access barangays under their branch
            if ($this->role_id == 2) {
                try {
                    $query = "SELECT COUNT(*) as count FROM branch_barangays 
                            WHERE branch_id = :branch_id AND barangay_id = :barangay_id 
                            AND is_active = 1";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':branch_id', $this->branch_id);
                    $stmt->bindParam(':barangay_id', $barangay_id);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $row['count'] > 0;
                } catch (PDOException $e) {
                    Response::error("Barangay access check failed: " . $e->getMessage(), 500);
                    exit();
                }
            }
            
            return false;
        }
        
        public function getAccessibleBarangays() {
            try {
                // Main admin gets all barangays
                if ($this->role_id == 1) {
                    $query = "SELECT id FROM barangays";
                    $stmt = $this->db->prepare($query);
                }
                // Branch admin gets barangays under their branch
                else if ($this->role_id == 2) {
                    $query = "SELECT barangay_id as id FROM branch_barangays 
                            WHERE branch_id = :branch_id AND is_active = 1";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':branch_id', $this->branch_id);
                }
                // Barangay admin gets only their barangay
                else if ($this->role_id == 3) {
                    $query = "SELECT :barangay_id as id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':barangay_id', $this->barangay_id);
                } else {
                    return []; // No accessible barangays for unknown role
                }
                
                $stmt->execute();
                $barangays = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $barangays[] = $row['id'];
                }
                return $barangays;
            } catch (PDOException $e) {
                Response::error("Failed to get accessible barangays: " . $e->getMessage(), 500);
                exit();
            }
        }
        
        private function logAccess() {
            try {
                $query = "INSERT INTO access_logs 
                        (user_id, action, ip_address, user_agent, browser, device) 
                        VALUES (:user_id, 'API_ACCESS', :ip, :user_agent, :browser, :device)";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $this->user_id);
                
                $ip = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $browser = $this->getBrowser($user_agent);
                $device = $this->getDevice($user_agent);
                
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':user_agent', $user_agent);
                $stmt->bindParam(':browser', $browser);
                $stmt->bindParam(':device', $device);
                
                $stmt->execute();
            } catch (PDOException $e) {
                // Log the error and return a JSON response
                Response::error("Failed to log API access: " . $e->getMessage(), 500);
                exit();
            }
        }
        
        private function getBrowser($user_agent) {
            if (strpos($user_agent, 'Chrome') !== false) return 'Chrome';
            if (strpos($user_agent, 'Firefox') !== false) return 'Firefox';
            if (strpos($user_agent, 'Safari') !== false) return 'Safari';
            if (strpos($user_agent, 'Edge') !== false) return 'Edge';
            return 'Other';
        }
        
        private function getDevice($user_agent) {
            if (strpos($user_agent, 'Mobile') !== false) return 'Mobile';
            if (strpos($user_agent, 'Tablet') !== false) return 'Tablet';
            return 'Desktop';
        }
        
        private function unauthorized($message) {
            Response::error($message, 401);
            exit();
        }
        
        public function getUserId() { return $this->user_id; }
        public function getRoleId() { return $this->role_id; }
        public function getBranchId() { return $this->branch_id; }
        public function getBarangayId() { return $this->barangay_id; }
    }