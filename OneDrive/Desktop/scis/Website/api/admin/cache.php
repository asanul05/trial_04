<?php
/**
 * Cache Management API
 * Place this in: Website/api/admin/cache.php
 * 
 * Endpoints:
 * - GET /api/admin/cache.php?action=stats - Get cache statistics
 * - POST /api/admin/cache.php?action=clear - Clear all cache
 * - POST /api/admin/cache.php?action=clear_expired - Clear expired cache only
 * - DELETE /api/admin/cache.php?key=senior_details_123 - Clear specific cache
 */

require_once '../../config/database.php';
require_once '../../middleware/auth.php';
require_once '../../helpers/response.php';
require_once '../../helpers/cache.php';

$database = new Database();
$db = $database->getConnection();

$auth = new AuthMiddleware($db);
if (!$auth->authenticate()) exit;

// Only main admins can manage cache
if ($auth->getRoleId() !== 1) {
    Response::error(
        message: "Only main administrators can manage cache",
        code: 403
    );
}


$action = isset($_GET['action']) ? $_GET['action'] : '';
$cache = new SimpleCache();

try {
    switch ($action) {
        case 'stats':
            // Get cache statistics
            $cache_dir = __DIR__ . '/../../cache/';
            $files = glob($cache_dir . '*.cache');
            
            $stats = [
                'total_files' => count($files),
                'total_size' => 0,
                'expired_count' => 0,
                'valid_count' => 0
            ];
            
            foreach ($files as $file) {
                $stats['total_size'] += filesize($file);
                
                $data = json_decode(file_get_contents($file), true);
                if ($data && isset($data['expires'])) {
                    if (time() > $data['expires']) {
                        $stats['expired_count']++;
                    } else {
                        $stats['valid_count']++;
                    }
                }
            }
            
            $stats['total_size_formatted'] = formatBytes($stats['total_size']);
            
            Response::success($stats, "Cache statistics retrieved");
            break;
            
        case 'clear':
            // Clear all cache
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::error("Method not allowed", 405);
            }
            
            $cache->clear();
            Response::success(['cleared' => true], "All cache cleared successfully");
            break;
            
        case 'clear_expired':
            // Clear expired cache only
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::error("Method not allowed", 405);
            }
            
            $count = $cache->clearExpired();
            Response::success([
                'cleared' => true,
                'count' => $count
            ], "Cleared {$count} expired cache files");
            break;
            
        case 'delete':
            // Delete specific cache key
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::error("Method not allowed", 405);
            }
            
            $key = isset($_GET['key']) ? $_GET['key'] : '';
            if (empty($key)) {
                Response::error("Cache key required", 400);
            }
            
            $cache->delete($key);
            Response::success(['deleted' => true], "Cache key deleted");
            break;
            
        default:
            Response::error("Invalid action", 400);
    }
    
} catch (Exception $e) {
    error_log("Cache management error: " . $e->getMessage());
    Response::error("An error occurred", 500);
}

/**
 * Format bytes to human readable size
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}