<?php
/**
 * Wilpattu Nature - Main Entry Point / Router
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

// Initialize database if needed
if (!file_exists(DB_PATH)) {
    $db = Database::getInstance();
    $db->init();
}

// Get page parameter
$page = $_GET['page'] ?? 'home';

// Route to appropriate page
switch ($page) {
    case 'home':
        include __DIR__ . '/pages/home.php';
        break;
        
    case 'gallery':
        include __DIR__ . '/pages/gallery.php';
        break;
        
    default:
        // 404 or redirect to home
        header('HTTP/1.0 404 Not Found');
        include __DIR__ . '/pages/home.php';
        break;
}
?>
