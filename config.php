<?php
/**
 * Wilpattu Nature - Configuration File
 */

require_once __DIR__ . '/includes/dotenv.php';
loadEnv(__DIR__ . '/.env');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_PATH', __DIR__ . '/database/wilpattu.db');

// Site configuration
if (PHP_SAPI === 'cli' || empty($_SERVER['HTTP_HOST'])) {
    // Fallback for CLI/cron contexts where HTTP_HOST is not defined
    define('SITE_URL', 'https://wilsafari.com');
} else {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) == 443;
    $scheme = $isSecure ? 'https://' : 'http://';
    $path = dirname($_SERVER['PHP_SELF'] ?? '/') ?: '';
    $normalizedPath = rtrim(str_replace('\\', '/', $path), '/');
    define('SITE_URL', rtrim($scheme . $_SERVER['HTTP_HOST'] . ($normalizedPath ? '/' . ltrim($normalizedPath, '/') : ''), '/'));
}
define('SITE_NAME', 'Wilpattu Nature');
define('SITE_TAGLINE', 'Sri Lanka\'s Premier Wildlife Experience');

// Contact information
define('PHONE_PRIMARY', '+94 77 207 5924');
define('PHONE_SECONDARY', '+94 77 207 5924');
define('EMAIL_PRIMARY', 'info@wilsfari.com');
define('EMAIL_SECONDARY', 'bookings@wilsafari.com');
define('ADDRESS', 'Wilpattu National Park, North Western Province, Sri Lanka');
define('WHATSAPP_NUMBER', '+94772075924');

// SMTP Configuration for email notifications
define('SMTP_HOST', env('SMTP_HOST', 'mail.wilsafari.com'));
define('SMTP_PORT', (int) env('SMTP_PORT', '465'));
define('SMTP_USERNAME', env('SMTP_USERNAME', ''));
define('SMTP_PASSWORD', env('SMTP_PASSWORD', ''));
define('SMTP_FROM_EMAIL', env('SMTP_FROM_EMAIL', 'booking@wilsafari.com'));
define('SMTP_FROM_NAME', env('SMTP_FROM_NAME', 'Wilpattu Nature'));
define('BOOKING_RECIPIENT', env('BOOKING_RECIPIENT', 'booking@wilsafari.com'));

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
