<?php
/**
 * Browser-based Validation Test
 * Access via: yourdomain.com/tests/
 */

require_once __DIR__ . '/../config.php';

$tests = [];
$passed = 0;
$failed = 0;
$warnings = 0;

function addTest($name, $condition, $error = '', $isWarning = false) {
    global $tests, $passed, $failed, $warnings;
    
    $tests[] = [
        'name' => $name,
        'status' => $condition ? 'pass' : ($isWarning ? 'warning' : 'fail'),
        'message' => $error
    ];
    
    if ($condition) {
        $passed++;
    } elseif ($isWarning) {
        $warnings++;
    } else {
        $failed++;
    }
}

// Run tests

// PHP Version
addTest('PHP Version >= 8.0', PHP_VERSION_ID >= 80000, 'Current: ' . PHP_VERSION);

// Extensions
$extensions = ['sqlite3', 'json', 'session'];
foreach ($extensions as $ext) {
    addTest("Extension: $ext", extension_loaded($ext));
}

// Directories
$dirs = ['database', 'includes', 'pages', 'api', 'assets/images'];
foreach ($dirs as $dir) {
    addTest("Directory: $dir", is_dir(__DIR__ . '/../' . $dir));
}

// Critical files
$files = [
    'index.php', 'config.php', '.htaccess',
    'includes/db.php', 'includes/functions.php',
    'includes/header.php', 'includes/footer.php',
    'api/booking.php'
];
foreach ($files as $file) {
    addTest("File: $file", file_exists(__DIR__ . '/../' . $file));
}

// Database
if (class_exists('Database')) {
    try {
        $db = Database::getInstance();
        addTest('Database Connection', true);
        
        $packages = $db->getPackages();
        addTest('Packages Data', count($packages) >= 5, 'Found: ' . count($packages));
        
        $gallery = $db->getGalleryImages();
        addTest('Gallery Data', count($gallery) >= 7, 'Found: ' . count($gallery));
    } catch (Exception $e) {
        addTest('Database Connection', false, $e->getMessage());
    }
} else {
    addTest('Database Class', false, 'Database class not found');
}

// Images
$images = [
    'assets/images/logo.png',
    'assets/images/hero/hero-elephant.jpg'
];
foreach ($images as $img) {
    addTest("Image: $img", file_exists(__DIR__ . '/../' . $img));
}

// Writable check
addTest('Database Writable', is_writable(dirname(DB_PATH)) || is_writable(DB_PATH), 'May need manual setup', true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation Tests - Wilpattu Nature</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .test-pass { @apply bg-green-100 text-green-800 border-green-300; }
        .test-fail { @apply bg-red-100 text-red-800 border-red-300; }
        .test-warning { @apply bg-yellow-100 text-yellow-800 border-yellow-300; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Wilpattu Nature - Validation Tests</h1>
        <p class="text-gray-600 mb-8">Testing website installation and configuration</p>
        
        <!-- Summary -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-green-500 text-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold"><?php echo $passed; ?></div>
                <div class="text-sm opacity-90">Passed</div>
            </div>
            <div class="bg-yellow-500 text-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold"><?php echo $warnings; ?></div>
                <div class="text-sm opacity-90">Warnings</div>
            </div>
            <div class="bg-red-500 text-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold"><?php echo $failed; ?></div>
                <div class="text-sm opacity-90">Failed</div>
            </div>
        </div>
        
        <!-- Status -->
        <?php if ($failed === 0): ?>
            <div class="bg-green-100 border border-green-400 text-green-800 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <div class="font-bold">All tests passed!</div>
                    <div class="text-sm">Your website is ready for deployment.</div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-100 border border-red-400 text-red-800 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="font-bold">Some tests failed</div>
                    <div class="text-sm">Please fix the issues below before deploying.</div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Test Results</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($tests as $test): ?>
                    <div class="px-6 py-4 flex items-center justify-between <?php 
                        echo $test['status'] === 'pass' ? 'bg-green-50' : ($test['status'] === 'warning' ? 'bg-yellow-50' : 'bg-red-50'); 
                    ?>">
                        <div class="flex items-center gap-3">
                            <?php if ($test['status'] === 'pass'): ?>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            <?php elseif ($test['status'] === 'warning'): ?>
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            <?php endif; ?>
                            <span class="<?php echo $test['status'] === 'fail' ? 'text-red-800' : 'text-gray-800'; ?>">
                                <?php echo htmlspecialchars($test['name']); ?>
                            </span>
                        </div>
                        <?php if ($test['message']): ?>
                            <span class="text-sm <?php echo $test['status'] === 'fail' ? 'text-red-600' : 'text-gray-500'; ?>">
                                <?php echo htmlspecialchars($test['message']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-8 flex gap-4">
            <a href="../index.php" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                View Website
            </a>
            <a href="../index.php?page=gallery" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                View Gallery
            </a>
            <button onclick="location.reload()" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Run Tests Again
            </button>
        </div>
        
        <!-- Troubleshooting -->
        <?php if ($failed > 0): ?>
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="font-semibold text-yellow-800 mb-3">Troubleshooting Tips</h3>
                <ul class="space-y-2 text-sm text-yellow-700">
                    <li>• Make sure all files are uploaded correctly</li>
                    <li>• Check that PHP 8.0+ is installed</li>
                    <li>• Enable SQLite3 extension in php.ini</li>
                    <li>• Set database directory permissions to 755</li>
                    <li>• Enable mod_rewrite for Apache</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
