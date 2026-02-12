<?php

/**
 * Booking API Endpoint
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/email.php';

// Set JSON response header
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate CSRF token
if (!isset($input['csrf_token']) || !verifyCsrfToken($input['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Validate required fields
$errors = [];

if (empty($input['name']) || strlen(trim($input['name'])) < 2) {
    $errors['name'] = 'Full name is required (minimum 2 characters)';
}

if (empty($input['email']) || !isValidEmail($input['email'])) {
    $errors['email'] = 'Valid email address is required';
}

if (empty($input['date'])) {
    $errors['date'] = 'Preferred date is required';
} else {
    $selectedDate = new DateTime($input['date']);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    if ($selectedDate < $today) {
        $errors['date'] = 'Please select a future date';
    }
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

// Prepare booking data
$bookingData = [
    'full_name' => htmlspecialchars(trim($input['name']), ENT_QUOTES, 'UTF-8'),
    'email' => htmlspecialchars(trim($input['email']), ENT_QUOTES, 'UTF-8'),
    'preferred_date' => $input['date'],
    'num_guests' => max(1, min(20, intval($input['guests'] ?? 1))),
    'message' => !empty($input['message']) ? htmlspecialchars(trim($input['message']), ENT_QUOTES, 'UTF-8') : null,
    'package_id' => !empty($input['package_id']) ? intval($input['package_id']) : null
];

// Save booking to database
try {
    $db = Database::getInstance();
    $result = $db->saveBooking($bookingData);

    if ($result) {
        sendBookingNotification($bookingData);
        $calendarData = $bookingData;
        register_shutdown_function(function () use ($calendarData) {
            try {
                addToCalendar($calendarData);
            } catch (Throwable $e) {
                error_log("Calendar update failed (deferred): " . $e->getMessage());
            }
        });

        echo json_encode([
            'success' => true,
            'message' => 'Booking request submitted successfully'
        ]);
    } else {
        throw new Exception('Failed to save booking');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your booking. Please try again.'
    ]);
}

/**
 * Send email notification via SMTP
 */
function sendBookingNotification($data)
{
    return sendBookingEmail($data);
}
