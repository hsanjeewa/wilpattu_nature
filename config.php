<?php
/**
 * Wilpattu Nature - Configuration File
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_PATH', __DIR__ . '/database/wilpattu.db');

// Site configuration
define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
define('SITE_NAME', 'Wilpattu Nature');
define('SITE_TAGLINE', 'Sri Lanka\'s Premier Wildlife Experience');

// Contact information
define('PHONE_PRIMARY', '+94 77 207 5924');
define('PHONE_SECONDARY', '+94 77 207 5924');
define('EMAIL_PRIMARY', 'info@wilsfari.com');
define('EMAIL_SECONDARY', 'bookings@wilsafari.com');
define('ADDRESS', 'Wilpattu National Park, North Western Province, Sri Lanka');
define('WHATSAPP_NUMBER', '+94772075924');

// Social media links
define('FACEBOOK_URL', 'https://facebook.com/wilpattunature');
define('INSTAGRAM_URL', 'https://instagram.com/wilpattunature');
define('TWITTER_URL', 'https://twitter.com/wilpattunature');
define('YOUTUBE_URL', 'https://youtube.com/wilpattunature');

// Timezone
date_default_timezone_set('Asia/Colombo');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Token generation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
