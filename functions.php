<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sanitize_input(?string $value): string
{
    return trim((string) $value);
}

function redirect(string $path): void
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function get_flash(string $type): ?string
{
    if (!isset($_SESSION['flash'][$type])) {
        return null;
    }

    $message = $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]);

    return $message;
}

function old(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? e(sanitize_input((string) $_POST[$key])) : e($default);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    if (!isset($_SESSION['csrf_token']) || !$token) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function validate_password_strength(string $password): bool
{
    return strlen($password) >= 8;
}

/**
 * Send reset email using PHPMailer if available.
 * Falls back to mail() when PHPMailer is not installed.
 */
function send_reset_email(string $toEmail, string $resetLink): bool
{
    if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            if (MAIL_HOST !== '') {
                $mail->isSMTP();
                $mail->Host = MAIL_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = MAIL_USERNAME;
                $mail->Password = MAIL_PASSWORD;
                $mail->Port = MAIL_PORT;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' Password Reset';
            $mail->Body = 'Click the link to reset your password: <a href="' . e($resetLink) . '">' . e($resetLink) . '</a>';
            $mail->AltBody = 'Reset your password: ' . $resetLink;
            return $mail->send();
        } catch (Throwable $e) {
            error_log('PHPMailer error: ' . $e->getMessage());
            return false;
        }
    }

    $subject = APP_NAME . ' Password Reset';
    $message = "Reset your password using this link:\n" . $resetLink;
    $headers = 'From: ' . MAIL_FROM . "\r\n";
    return mail($toEmail, $subject, $message, $headers);
}
