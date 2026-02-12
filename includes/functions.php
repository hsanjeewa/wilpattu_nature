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

/**
 * Add booking to cPanel calendar via CalDAV
 * 
 * @param array $bookingData Booking data array
 * @return bool True on success, false on failure
 */
function addToCalendar($bookingData) {
    $email = SMTP_USERNAME;  // booking@wilsafari.com
    $password = SMTP_PASSWORD;  // w1l@5@far1

    // Extract username from email (part before @)
    $username = explode('@', $email)[0];  // 'booking'

    $calendarBaseUrls = [
        'https://mail.wilsafari.com:2080/calendars/' . $email . '/calendar/'
    ];
    if ($username !== $email) {
        $calendarBaseUrls[] = 'https://mail.wilsafari.com:2080/calendars/' . $username . '/calendar/';
    }
    
    // Generate a unique ID for the event
    $uid = bin2hex(random_bytes(16)) . '@wilsafari.com';

    // Format dates for ICS
    // Use 10:00 AM as default start time for safari (Asia/Colombo timezone)
    $created   = gmdate('Ymd\THis\Z'); // Creation time in UTC

    // Get package name if available
    $packageInfo = '';
    if (!empty($bookingData['package_id'])) {
        try {
            $db = Database::getInstance();
            $package = $db->getPackage($bookingData['package_id']);
            if ($package) {
                $packageInfo = "Package: {$package['name']}";
            }
        } catch (Exception $e) {
            // Silently fail - package info is optional
        }
    }

    // Construct the iCalendar (ICS) payload with timezone, mirroring iOS structure
    $startTime = date('Ymd\THis', strtotime($bookingData['preferred_date'] . ' 10:00:00'));
    $endTime = date('Ymd\THis', strtotime($bookingData['preferred_date'] . ' 16:00:00'));

    $ics = [
        'BEGIN:VCALENDAR',
        'CALSCALE:GREGORIAN',
        'PRODID:-//Wilpattu Nature//Booking System//EN',
        'VERSION:2.0',
        'BEGIN:VTIMEZONE',
        'TZID:Asia/Colombo',
        'BEGIN:DAYLIGHT',
        'DTSTART:19420901T000000',
        'RDATE:19420901T000000',
        'TZNAME:GMT+5:30',
        'TZOFFSETFROM:+0600',
        'TZOFFSETTO:+0630',
        'END:DAYLIGHT',
        'BEGIN:STANDARD',
        'DTSTART:20060415T003000',
        'RDATE:20060415T003000',
        'TZNAME:GMT+5:30',
        'TZOFFSETFROM:+0600',
        'TZOFFSETTO:+0530',
        'END:STANDARD',
        'END:VTIMEZONE',
        'BEGIN:VEVENT',
        'CREATED:' . $created,
        "DTEND;TZID=Asia/Colombo:$endTime",
        'DTSTAMP:' . $created,
        "DTSTART;TZID=Asia/Colombo:$startTime",
        'LAST-MODIFIED:' . $created,
        'SEQUENCE:0',
        "SUMMARY:Safari Booking: {$bookingData['full_name']}",
        'TRANSP:OPAQUE',
        "UID:$uid",
        'URL;VALUE=URI:' . SITE_URL,
        "DESCRIPTION:Customer: {$bookingData['full_name']}\\nEmail: {$bookingData['email']}\\nGuests: {$bookingData['num_guests']}\\nDate: {$bookingData['preferred_date']}\\n{$packageInfo}",
        'END:VEVENT',
        'END:VCALENDAR'
    ];
    $payload = implode("\r\n", $ics);

    // First, try to discover the correct calendar path using PROPFIND
    $discoveryUrls = [
        'https://mail.wilsafari.com:2080/calendars/' . $email . '/',
        'https://mail.wilsafari.com:2080/calendars/' . $username . '/'
    ];
    
    $workingBaseUrl = null;
    
    foreach ($discoveryUrls as $discoveryUrl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $discoveryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PROPFIND");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$email:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Depth: 1',
            'Content-Type: application/xml; charset=utf-8'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode === 207) { // 207 Multi-Status means WebDAV is working
            error_log("Calendar discovery successful at: $discoveryUrl");
            $workingBaseUrl = $discoveryUrl;
            break;
        }
    }
    
    // If discovery worked, use that URL, otherwise fall back to trying all patterns
    if ($workingBaseUrl) {
        $calendarBaseUrls = [$workingBaseUrl . '/calendar'];
    }
    
    // Check calendar status using REPORT method (PROPFIND has cPanel bug CPANEL-49508)
    $calendarWorking = false;
    $cPanelBugDetected = false;
    
    // Try REPORT method first (works with cPanel's buggy CalDAV implementation)
    $reportXml = '<?xml version="1.0" encoding="utf-8" ?>
<C:calendar-query xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <D:prop>
    <D:getetag/>
    <C:calendar-data/>
  </D:prop>
  <C:filter>
    <C:comp-filter name="VCALENDAR">
      <C:comp-filter name="VEVENT">
        <C:time-range start="2025-01-01T00:00:00Z" end="2026-12-31T23:59:59Z"/>
      </C:comp-filter>
    </C:comp-filter>
  </C:filter>
</C:calendar-query>';
    
    $testCh = curl_init();
    curl_setopt($testCh, CURLOPT_URL, 'https://mail.wilsafari.com:2080/calendars/' . $email . '/calendar/');
    curl_setopt($testCh, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($testCh, CURLOPT_CUSTOMREQUEST, "REPORT");
    curl_setopt($testCh, CURLOPT_POSTFIELDS, $reportXml);
    curl_setopt($testCh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($testCh, CURLOPT_USERPWD, "$email:$password");
    curl_setopt($testCh, CURLOPT_HTTPHEADER, [
        'Depth: 1',
        'Content-Type: application/xml; charset=utf-8',
        'Content-Length: ' . strlen($reportXml)
    ]);
    curl_setopt($testCh, CURLOPT_TIMEOUT, 5);
    curl_setopt($testCh, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($testCh, CURLOPT_SSL_VERIFYHOST, 0);
    
    $testResponse = curl_exec($testCh);
    $testHttpCode = curl_getinfo($testCh, CURLINFO_HTTP_CODE);
    
    if ($testHttpCode === 207 && $testResponse && strlen($testResponse) > 100) {
        $calendarWorking = true;
        error_log("Calendar REPORT successful (HTTP 207). Events should be visible to external clients.");
    } else {
        // Try PROPFIND as fallback (has cPanel bug CPANEL-49508)
        $testCh = curl_init();
        curl_setopt($testCh, CURLOPT_URL, 'https://mail.wilsafari.com:2080/calendars/' . $email . '/calendar/');
        curl_setopt($testCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($testCh, CURLOPT_CUSTOMREQUEST, "PROPFIND");
        curl_setopt($testCh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($testCh, CURLOPT_USERPWD, "$email:$password");
        curl_setopt($testCh, CURLOPT_HTTPHEADER, [
            'Depth: 1',
            'Content-Type: application/xml; charset=utf-8'
        ]);
        curl_setopt($testCh, CURLOPT_TIMEOUT, 3);
        curl_setopt($testCh, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($testCh, CURLOPT_SSL_VERIFYHOST, 0);
        
        $testResponse = curl_exec($testCh);
        $testHttpCode = curl_getinfo($testCh, CURLINFO_HTTP_CODE);
        
        if ($testHttpCode === 200 && (!$testResponse || strlen($testResponse) < 100)) {
            $cPanelBugDetected = true;
            error_log("CPANEL BUG DETECTED: PROPFIND returns empty (HTTP 200). This is cPanel bug CPANEL-49508.");
            error_log("Events are created successfully but cPanel web interface may not show them.");
            error_log("External clients (iPhone, etc.) can see events. cPanel bug affects web interface only.");
        } elseif ($testHttpCode === 207 || $testHttpCode === 200) {
            $calendarWorking = true;
            error_log("Calendar PROPFIND successful (HTTP $testHttpCode)");
        }
    }
    
    if (!$calendarWorking && !$cPanelBugDetected) {
        error_log("Calendar connection issue. HTTP Code: $testHttpCode");
    }

    // Try each URL pattern until one works
    foreach ($calendarBaseUrls as $baseUrl) {
        // Ensure the URL ends with the UID.ics
        $url = rtrim($baseUrl, '/') . '/' . $uid . '.ics';
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$email:$password");  // Use full email for auth
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/calendar; charset=utf-8',
            'Content-Length: ' . strlen($payload)
        ]);
        // Set timeout and SSL options
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing, should be true in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);     // For testing, should be 2 in production

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        // curl_close() is not needed in PHP 8.0+ - resources are automatically closed

        // Log result for debugging
        if ($httpCode >= 200 && $httpCode < 300) {
            if ($cPanelBugDetected) {
                error_log("Calendar event created (HTTP $httpCode) but cPanel web interface has bug CPANEL-49508.");
                error_log("Event URL: $url - Event exists and is visible to external clients (iPhone, etc.).");
                error_log("cPanel web interface may not show events due to PROPFIND bug.");
            } elseif ($calendarWorking) {
                error_log("Calendar event added successfully. URL: $url, UID: $uid, HTTP Code: $httpCode");
            } else {
                error_log("Calendar event added (HTTP $httpCode) but calendar status unknown.");
                error_log("Event URL: $url - Event created successfully.");
            }
            return true;
        } else {
            error_log("Calendar event failed for URL: $url, HTTP Code: $httpCode, Error: $error");
            // Continue to try next URL pattern
        }
    }
    
    // If we get here, all URL patterns failed
    error_log("All calendar URL patterns failed for booking: " . $bookingData['full_name']);
    error_log("Calendar may need manual initialization via cPanel → Email → Calendars and Contacts Management");
    return false;
}
?>
