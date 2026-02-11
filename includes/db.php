<?php
/**
 * Wilpattu Nature - Database Class
 */

require_once __DIR__ . '/../config.php';

class Database {
    private $db;
    private static $instance = null;
    
    public function __construct() {
        $this->db = new SQLite3(DB_PATH);
        $this->db->busyTimeout(5000);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize database with schema
     */
    public function init() {
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $this->db->exec($schema);
    }
    
    /**
     * Get all packages
     */
    public function getPackages() {
        $result = $this->db->query("SELECT * FROM packages ORDER BY sort_order ASC");
        $packages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $row['features'] = json_decode($row['features'], true);
            $packages[] = $row;
        }
        return $packages;
    }
    
    /**
     * Get package by ID
     */
    public function getPackage($id) {
        $stmt = $this->db->prepare("SELECT * FROM packages WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            $row['features'] = json_decode($row['features'], true);
        }
        return $row;
    }
    
    /**
     * Get gallery images
     */
    public function getGalleryImages($featured = false, $limit = null) {
        $sql = "SELECT * FROM gallery_images";
        if ($featured) {
            $sql .= " WHERE is_featured = 1";
        }
        $sql .= " ORDER BY sort_order ASC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->db->query($sql);
        $images = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $images[] = $row;
        }
        return $images;
    }
    
    /**
     * Save booking
     */
    public function saveBooking($data) {
        $stmt = $this->db->prepare("INSERT INTO bookings (full_name, email, preferred_date, num_guests, message, package_id) VALUES (:name, :email, :date, :guests, :message, :package)");
        
        $stmt->bindValue(':name', $data['full_name'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
        $stmt->bindValue(':date', $data['preferred_date'], SQLITE3_TEXT);
        $stmt->bindValue(':guests', $data['num_guests'], SQLITE3_INTEGER);
        $stmt->bindValue(':message', $data['message'], SQLITE3_TEXT);
        $stmt->bindValue(':package', $data['package_id'] ?? null, SQLITE3_INTEGER);
        
        return $stmt->execute();
    }
    
    /**
     * Get site setting
     */
    public function getSetting($key) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE key = :key");
        $stmt->bindValue(':key', $key, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row ? $row['value'] : null;
    }
    
    /**
     * Get all settings
     */
    public function getAllSettings() {
        $result = $this->db->query("SELECT * FROM settings");
        $settings = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
    
    /**
     * Close database connection
     */
    public function close() {
        $this->db->close();
    }
}
?>
