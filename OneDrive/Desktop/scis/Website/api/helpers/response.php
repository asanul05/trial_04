<?php    
    class Response {
        public static function success($data = null, $message = "Success", $code = 200) {
            http_response_code($code);
            echo json_encode([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit();
        }
        
        public static function error($message = "Error", $code = 400, $errors = null) {
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit();
        }
        
        public static function paginated($data, $page, $limit, $total, $message = "Success") {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'pagination' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => (int)$total,
                    'pages' => ceil($total / $limit)
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit();
        }
    }

    // CORS Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    // Handle OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }