<?php
/**
 * Wilpattu Nature - Validation Tests
 * 
 * Run this file to validate the website installation:
 * php tests/validate.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

class Validator {
    private $errors = [];
    private $warnings = [];
    private $passed = [];
    
    public function test($name, $condition, $errorMsg, $isWarning = false) {
        if ($condition) {
            $this->passed[] = $name;
            echo "✓ PASS: $name\n";
            return true;
        } else {
            if ($isWarning) {
                $this->warnings[] = "$name: $errorMsg";
                echo "⚠ WARNING: $name - $errorMsg\n";
            } else {
                $this->errors[] = "$name: $errorMsg";
                echo "✗ FAIL: $name - $errorMsg\n";
            }
            return false;
        }
    }
    
    public function getResults() {
        return [
            'passed' => count($this->passed),
            'warnings' => count($this->warnings),
            'errors' => count($this->errors),
            'total' => count($this->passed) + count($this->warnings) + count($this->errors)
        ];
    }
    
    public function hasErrors() {
        return count($this->errors) > 0;
    }
}

$validator = new Validator();

echo "========================================\n";
echo "  Wilpattu Nature - Validation Tests\n";
echo "========================================\n\n";

// Test 1: PHP Version
echo "--- PHP Environment ---\n";
$validator->test(
    'PHP Version >= 8.0',
    PHP_VERSION_ID >= 80000,
    'PHP version ' . PHP_VERSION . ' is too old. Requires PHP 8.0+'
);

// Test 2: Required Extensions
echo "\n--- Required Extensions ---\n";
$requiredExtensions = ['sqlite3', 'pdo', 'json', 'session'];
foreach ($requiredExtensions as $ext) {
    $validator->test(
        "Extension: $ext",
        extension_loaded($ext),
        "Extension $ext is not loaded"
    );
}

// Test 3: Directory Structure
echo "\n--- Directory Structure ---\n";
$requiredDirs = [
    'database',
    'includes',
    'pages',
    'pages/partials',
    'api',
    'assets',
    'assets/css',
    'assets/js',
    'assets/images',
    'assets/images/hero',
    'assets/images/packages',
    'assets/images/gallery'
];

foreach ($requiredDirs as $dir) {
    $validator->test(
        "Directory: $dir",
        is_dir(__DIR__ . '/../' . $dir),
        "Directory $dir does not exist"
    );
}

// Test 4: Required Files
echo "\n--- Required Files ---\n";
$requiredFiles = [
    'index.php',
    'config.php',
    '.htaccess',
    'includes/db.php',
    'includes/functions.php',
    'includes/header.php',
    'includes/footer.php',
    'api/booking.php',
    'pages/home.php',
    'pages/gallery.php',
    'pages/partials/hero.php',
    'pages/partials/why-choose-us.php',
    'pages/partials/packages.php',
    'pages/partials/safari-ops.php',
    'pages/partials/wildlife-wonders.php',
    'pages/partials/contact.php'
];

foreach ($requiredFiles as $file) {
    $validator->test(
        "File: $file",
        file_exists(__DIR__ . '/../' . $file),
        "File $file does not exist"
    );
}

// Test 5: Database
echo "\n--- Database ---\n";
try {
    $db = Database::getInstance();
    $validator->test(
        'Database Connection',
        true,
        'Failed to connect to database'
    );
    
    // Check tables
    $tables = ['packages', 'gallery_images', 'bookings', 'settings'];
    foreach ($tables as $table) {
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        $validator->test(
            "Table: $table",
            $result->fetchArray() !== false,
            "Table $table does not exist"
        );
    }
    
    // Check packages data
    $packages = $db->getPackages();
    $validator->test(
        'Packages Data',
        count($packages) >= 5,
        'Expected at least 5 packages, found ' . count($packages)
    );
    
    // Check gallery data
    $gallery = $db->getGalleryImages();
    $validator->test(
        'Gallery Images Data',
        count($gallery) >= 7,
        'Expected at least 7 gallery images, found ' . count($gallery)
    );
    
} catch (Exception $e) {
    $validator->test('Database Connection', false, $e->getMessage());
}

// Test 6: Images
echo "\n--- Images ---\n";
$requiredImageOptions = [
    ['assets/images/logo.png'],
    [
        'assets/images/hero/hero-elephant.jpg',
        'assets/images/hero/hero-elephant.jpeg',
        'assets/images/hero/hero-elephant.webp'
    ],
    [
        'assets/images/packages/package-half-day.jpg',
        'assets/images/packages/package-half-day.jpeg',
        'assets/images/packages/package-half-day.webp'
    ],
    [
        'assets/images/packages/package-full-day.jpg',
        'assets/images/packages/package-full-day.jpeg',
        'assets/images/packages/package-full-day.webp'
    ],
    [
        'assets/images/packages/package-extended.jpg',
        'assets/images/packages/package-extended.jpeg',
        'assets/images/packages/package-extended.webp'
    ],
    [
        'assets/images/packages/package-night.jpg',
        'assets/images/packages/package-night.jpeg',
        'assets/images/packages/package-night.webp'
    ],
    [
        'assets/images/packages/package-ruins.jpg',
        'assets/images/packages/package-ruins.jpeg',
        'assets/images/packages/package-ruins.webp'
    ]
];

foreach ($requiredImageOptions as $options) {
    $label = $options[0];
    $exists = false;
    foreach ($options as $imageOption) {
        if (file_exists(__DIR__ . '/../' . $imageOption)) {
            $exists = true;
            break;
        }
    }
    $validator->test(
        "Image: $label",
        $exists,
        'None of the expected image variants exist: ' . implode(', ', $options)
    );
}

// Count gallery images (including subdirectories)
$galleryDir = __DIR__ . '/../assets/images/gallery';
if (is_dir($galleryDir)) {
    $galleryImages = array_merge(
        glob($galleryDir . '/*.jpg'),
        glob($galleryDir . '/*.jpeg'),
        glob($galleryDir . '/*/*.jpg'),
        glob($galleryDir . '/*/*.jpeg')
    );
    $validator->test(
        'Gallery Images Count',
        count($galleryImages) >= 7,
        'Expected at least 7 gallery images, found ' . count($galleryImages)
    );
}

// Test 7: File Permissions
echo "\n--- File Permissions ---\n";
$validator->test(
    'Database Directory Writable',
    is_writable(__DIR__ . '/../database') || is_writable(dirname(DB_PATH)),
    'Database directory is not writable',
    true // Warning only
);

// Test 8: Configuration
echo "\n--- Configuration ---\n";
$validator->test(
    'Site Name Defined',
    defined('SITE_NAME') && !empty(SITE_NAME),
    'SITE_NAME is not defined'
);

$validator->test(
    'Contact Info Defined',
    defined('PHONE_PRIMARY') && defined('EMAIL_PRIMARY'),
    'Contact information is not defined'
);

// Test 9: Functions
echo "\n--- Functions ---\n";
$requiredFunctions = [
    'e', 'asset', 'image', 'formatPrice', 'csrfField', 
    'verifyCsrfToken', 'isValidEmail', 'getNavigation',
    'getFeatures', 'getSafariInclusions', 'getSafariTimings'
];

foreach ($requiredFunctions as $func) {
    $validator->test(
        "Function: $func()",
        function_exists($func),
        "Function $func() does not exist"
    );
}

// Summary
echo "\n========================================\n";
echo "  Test Results Summary\n";
echo "========================================\n";

$results = $validator->getResults();
echo "Total Tests: {$results['total']}\n";
echo "Passed: {$results['passed']} ✓\n";
echo "Warnings: {$results['warnings']} ⚠\n";
echo "Errors: {$results['errors']} ✗\n";

if ($validator->hasErrors()) {
    echo "\n❌ VALIDATION FAILED - Please fix the errors above.\n";
    exit(1);
} else {
    echo "\n✅ VALIDATION PASSED - Website is ready for deployment!\n";
    exit(0);
}
