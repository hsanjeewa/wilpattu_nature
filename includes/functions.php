<?php
/**
 * Wilpattu Nature - Helper Functions
 */

require_once __DIR__ . '/db.php';

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get asset URL
 */
function asset($path) {
    return 'assets/' . ltrim($path, '/');
}

/**
 * Get image URL
 */
function image($path) {
    return asset('images/' . ltrim($path, '/'));
}

/**
 * Format price
 */
function formatPrice($price) {
    return '$' . number_format($price, 0);
}

/**
 * Generate CSRF token field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . e($_SESSION['csrf_token']) . '">';
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Send JSON response
 */
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Get current page
 */
function currentPage() {
    return $_GET['page'] ?? 'home';
}

/**
 * Check if current page
 */
function isPage($page) {
    return currentPage() === $page;
}

/**
 * Get navigation items
 */
function getNavigation() {
    return [
        ['label' => 'Home', 'url' => 'index.php', 'page' => 'home'],
        ['label' => 'About', 'url' => 'index.php?page=home#about', 'page' => 'about'],
        ['label' => 'Safaris', 'url' => 'index.php?page=home#safaris', 'page' => 'safaris'],
        ['label' => 'Photos', 'url' => 'index.php?page=home#gallery', 'page' => 'gallery'],
        ['label' => 'Contact', 'url' => 'index.php?page=home#contact', 'page' => 'contact'],
        ['label' => 'Gallery', 'url' => 'index.php?page=gallery', 'page' => 'gallery-page'],
    ];
}

/**
 * Get feature cards data
 */
function getFeatures() {
    return [
        [
            'icon' => 'compass',
            'title' => 'Experienced Team',
            'description' => 'Over 7 years of expertise guiding safaris through Wilpattu\'s wilderness.'
        ],
        [
            'icon' => 'clipboard-list',
            'title' => 'All-Inclusive Packages',
            'description' => 'Everything included: transport, meals, entrance fees, and refreshments.'
        ],
        [
            'icon' => 'truck',
            'title' => 'Low-Angle Safari Jeeps',
            'description' => 'Specially designed vehicles for optimal wildlife photography.'
        ],
        [
            'icon' => 'user-check',
            'title' => 'Expert Naturalists',
            'description' => 'Knowledgeable guides who know every trail and creature in the park.'
        ],
        [
            'icon' => 'credit-card',
            'title' => 'Flexible Payments',
            'description' => 'Online payments and card options for your convenience.'
        ],
        [
            'icon' => 'shield-check',
            'title' => 'Government Approved',
            'description' => 'Fully licensed and insured for your safety and peace of mind.'
        ]
    ];
}

/**
 * Get safari inclusions
 */
function getSafariInclusions() {
    return [
        'Water bottle for each person',
        'Coffee or King Coconut',
        'Biscuits and snacks',
        'Face Masks',
        'Breakfast or Lunch as per request',
        'Experienced safari guides/naturalists'
    ];
}

/**
 * Get safari timings
 */
function getSafariTimings() {
    return [
        ['label' => 'Morning Safari', 'time' => '6:00 AM - 10:00 AM'],
        ['label' => 'Full Day Safari', 'time' => '6:00 AM - 6:00 PM'],
        ['label' => 'Park Open', 'time' => 'Daily']
    ];
}

/**
 * Get footer links
 */
function getFooterLinks() {
    return [
        'quick' => [
            ['label' => 'Home', 'url' => 'index.php'],
            ['label' => 'About Us', 'url' => 'index.php?page=home#about'],
            ['label' => 'Safari Packages', 'url' => 'index.php?page=home#safaris'],
            ['label' => 'Gallery', 'url' => 'index.php?page=gallery'],
            ['label' => 'Contact', 'url' => 'index.php?page=home#contact'],
        ],
        'safaris' => [
            ['label' => 'Full Day Safari', 'url' => 'index.php?page=home#safaris'],
            ['label' => 'Leopard Safari', 'url' => 'index.php?page=home#safaris'],
            ['label' => 'Photography Safari', 'url' => 'index.php?page=home#safaris'],
            ['label' => 'Overnight Stay', 'url' => 'index.php?page=home#safaris'],
            ['label' => 'Private Tours', 'url' => 'index.php?page=home#safaris'],
        ]
    ];
}
?>
