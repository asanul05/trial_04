<?php    
    session_start();

    class AuthMiddleware {
        private $db;
        private $user_id;
        private $role_id;
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

            $this->user_id = $user_session['id'];
            $this->role_id = $user_session['role_id'];
            $this->branch_id = $user_session['branch_id'] ?? null;
            $this->barangay_id = $user_session['barangay_id'] ?? null;
            
            $this->logAccess();
            
            return true;
        }
        
        public function checkPermission($module, $action) {
            $query = "SELECT " . $action . " as has_permission 
                    FROM user_permissions 
                    WHERE role_id = :role_id AND module = :module";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':role_id', $this->role_id);
            $stmt->bindParam(':module', $module);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row && $row['has_permission'];
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
                $query = "SELECT COUNT(*) as count FROM branch_barangays 
                        WHERE branch_id = :branch_id AND barangay_id = :barangay_id 
                        AND is_active = 1";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':branch_id', $this->branch_id);
                $stmt->bindParam(':barangay_id', $barangay_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['count'] > 0;
            }
            
            return false;
        }
        
        public function getAccessibleBarangays() {
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
            }
            
            $stmt->execute();
            $barangays = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $barangays[] = $row['id'];
            }
            return $barangays;
        }
        
        private function logAccess() {
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
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => $message]);
            exit();
        }
        
        public function getUserId() { return $this->user_id; }
        public function getRoleId() { return $this->role_id; }
        public function getBranchId() { return $this->branch_id; }
        public function getBarangayId() { return $this->barangay_id; }
    }