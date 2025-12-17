<?php
/**
 * Simple file-based cache for API responses
 * Place this in: Website/helpers/cache.php
 */

class SimpleCache {
    private $cache_dir;
    private $cache_time;
    
    public function __construct($cache_time = 300) { // 5 minutes default
        $this->cache_dir = __DIR__ . '/../cache/';
        $this->cache_time = $cache_time;
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }
    
    /**
     * Get cached data
     */
    public function get($key) {
        $file = $this->getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $data = json_decode(file_get_contents($file), true);
        
        if (!$data || !isset($data['expires']) || !isset($data['value'])) {
            return null;
        }
        
        // Check if expired
        if (time() > $data['expires']) {
            $this->delete($key);
            return null;
        }
        
        return $data['value'];
    }
    
    /**
     * Store data in cache
     */
    public function set($key, $value, $ttl = null) {
        $file = $this->getCacheFile($key);
        $ttl = $ttl ?? $this->cache_time;
        
        $data = [
            'expires' => time() + $ttl,
            'value' => $value
        ];
        
        return file_put_contents($file, json_encode($data)) !== false;
    }
    
    /**
     * Delete cached data
     */
    public function delete($key) {
        $file = $this->getCacheFile($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }
    
    /**
     * Clear all cache
     */
    public function clear() {
        $files = glob($this->cache_dir . '*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
    
    /**
     * Clear expired cache files
     */
    public function clearExpired() {
        $files = glob($this->cache_dir . '*.cache');
        $count = 0;
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $data = json_decode(file_get_contents($file), true);
                if ($data && isset($data['expires']) && time() > $data['expires']) {
                    unlink($file);
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Get cache file path
     */
    private function getCacheFile($key) {
        $hash = md5($key);
        return $this->cache_dir . $hash . '.cache';
    }
    
    /**
     * Check if key exists and is not expired
     */
    public function has($key) {
        return $this->get($key) !== null;
    }
}