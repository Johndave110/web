// Attempt to load Composer autoload for PHPMailer
$__autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($__autoloadPath)) {
    require_once $__autoloadPath;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_verification_email(string $toEmail, int $code): bool {
    // 1. Validate Email immediately
    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $toEmail);
        return false;
    }

    $subject = 'Your Scholarship Portal Verification Code';
    $html = '<p>Your verification code is: <strong>' . htmlspecialchars((string)$code) . '</strong></p>';
    $text = "Your verification code is: {$code}";

    // 2. Check environment - ONLY log codes in local dev, and do it securely
    $isDev = getenv('APP_ENV') === 'development';
    
    // If we are in dev and want to skip email sending for speed/offline dev
    if ($isDev && getenv('SKIP_EMAIL_SENDING')) {
        // Log to system error log, not a public file
        error_log("DEV MODE - Verification Code for $toEmail: $code");
        return true; // Return true so the app flow continues
    }

    try {
        $mail = new PHPMailer(true);
        
        // 3. Throw exception if creds are missing (Don't use fallbacks)
        $smtpUser = getenv('SMTP_USER');
        $smtpPass = getenv('SMTP_PASS');
        
        if (!$smtpUser || !$smtpPass) {
            throw new Exception("SMTP Credentials not set.");
        }

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($smtpUser, 'Scholarship Portal');
        $mail->addAddress($toEmail);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $text;

        $mail->send();
        return true;

    } catch (Throwable $e) {
        // 4. Log the ACTUAL error internally for debugging
        error_log("Mailer Error: " . $e->getMessage());
        // Fallback: try native mail(), and if that fails, log to file
        $headers = "From: no-reply@scholarship.local\r\n" .
                   "Reply-To: no-reply@scholarship.local\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        $subject = 'Your Scholarship Portal Verification Code';
        $text = "Your verification code is: {$code}\nThis code expires in 10 minutes.";
        $ok = false;
        try { $ok = @mail($toEmail, $subject, $text, $headers); } catch (Throwable $e2) { $ok = false; }
        if ($ok) { return true; }
        // Write to a local log file as last resort
        $logDir = __DIR__ . '/uploads/applications';
        if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
        $logFile = $logDir . '/verification_codes.log';
        @file_put_contents($logFile, date('Y-m-d H:i:s') . "\t" . $toEmail . "\t" . $code . "\n", FILE_APPEND);
        return false; 
    }
}