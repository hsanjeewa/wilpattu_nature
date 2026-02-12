<?php
// Manually require PHPMailer files first
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send booking notification email via SMTP
 * 
 * @param array $bookingData Booking data array
 * @return bool True on success, false on failure
 */
function sendBookingEmail($bookingData) {
    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                      // Send using SMTP
        $mail->Host       = SMTP_HOST;                        // SMTP server
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = SMTP_USERNAME;                    // SMTP username
        $mail->Password   = SMTP_PASSWORD;                    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Enable SSL/TLS encryption
        $mail->Port       = SMTP_PORT;                        // TCP port (465 for SSL/TLS)
        
        // Enable debugging (0 = off, 1 = client messages, 2 = client and server messages)
        $mail->SMTPDebug = 0;
        
        // Recipients
        // Use user's email/name as FROM address (as requested)
        $mail->setFrom($bookingData['email'], $bookingData['full_name']);
        // Set Sender as system email for SPF/DKIM compliance (Return-Path)
        $mail->Sender = SMTP_FROM_EMAIL;
        $mail->addAddress(BOOKING_RECIPIENT);                 // Primary booking recipient
        // Set reply-to to user's email for reliable response handling
        $mail->addReplyTo($bookingData['email'], $bookingData['full_name']);
        
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'New Safari Booking Request - ' . $bookingData['full_name'];
        
        // HTML body
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1E3A2F; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #1E3A2F; }
                .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>New Safari Booking Request</h2>
                </div>
                <div class="content">
                    <div class="field">
                        <span class="label">Name:</span> ' . htmlspecialchars($bookingData['full_name']) . '
                    </div>
                    <div class="field">
                        <span class="label">Email:</span> ' . htmlspecialchars($bookingData['email']) . '
                    </div>
                    <div class="field">
                        <span class="label">Preferred Date:</span> ' . htmlspecialchars($bookingData['preferred_date']) . '
                    </div>
                    <div class="field">
                        <span class="label">Number of Guests:</span> ' . htmlspecialchars($bookingData['num_guests']) . '
                    </div>';
        
        if (!empty($bookingData['package_id'])) {
            $mail->Body .= '
                    <div class="field">
                        <span class="label">Package ID:</span> ' . htmlspecialchars($bookingData['package_id']) . '
                    </div>';
        }
        
        if (!empty($bookingData['message'])) {
            $mail->Body .= '
                    <div class="field">
                        <span class="label">Message:</span><br>
                        ' . nl2br(htmlspecialchars($bookingData['message'])) . '
                    </div>';
        }
        
        $mail->Body .= '
                </div>
                <div class="footer">
                    <p>This booking request was submitted via the Wilpattu Nature website.</p>
                    <p>Submitted on: ' . date('F j, Y \a\t g:i A') . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Plain text alternative body
        $mail->AltBody = "New booking request received:\n\n" .
            "Name: " . $bookingData['full_name'] . "\n" .
            "Email: " . $bookingData['email'] . "\n" .
            "Preferred Date: " . $bookingData['preferred_date'] . "\n" .
            "Number of Guests: " . $bookingData['num_guests'] . "\n" .
            (!empty($bookingData['package_id']) ? "Package ID: " . $bookingData['package_id'] . "\n" : "") .
            (!empty($bookingData['message']) ? "Message: " . $bookingData['message'] . "\n" : "") .
            "\nSubmitted on: " . date('F j, Y \a\t g:i A');
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        // Log error (in production, log to file instead of error_log)
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Send a simple email via SMTP
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email body (HTML)
 * @param string $altBody Plain text alternative
 * @return bool True on success, false on failure
 */
function sendEmail($to, $subject, $body, $altBody = '') {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = SMTP_PORT;
        $mail->SMTPDebug  = 0;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>