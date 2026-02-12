<?php
/**
 * Simple .env file loader
 * Loads environment variables from a .env file without external dependencies
 * 
 * Usage: require_once __DIR__ . '/dotenv.php';
 *        loadEnv(__DIR__ . '/../.env');
 * 
 * @param string $path Path to .env file
 * @return bool True if file was loaded, false otherwise
 */
function loadEnv(string $path): bool {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return false;
    }
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip comments and empty lines
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Skip lines without =
        if (strpos($line, '=') === false) {
            continue;
        }
        
        // Parse key=value pairs
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove surrounding quotes if present
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
            $value = substr($value, 1, -1);
        }
        
        // Only set if not already defined in environment
        if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
    
    return true;
}

/**
 * Get environment variable with optional default
 * 
 * @param string $key Environment variable name
 * @param string|null $default Default value if not set
 * @return string|null The value or default
 */
function env(string $key, ?string $default = null): ?string {
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}
?>
