<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

$error = null;
$success = null;
$debugResetLink = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid CSRF token.';
    } else {
        $email = sanitize_input($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Enter a valid email.';
        } else {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT id FROM Users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(50));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $insert = $pdo->prepare('INSERT INTO Password_resets (email, token, expires_at) VALUES (?, ?, ?)');
                $insert->execute([$email, $token, $expiresAt]);

                $resetLink = APP_URL . '/auth/reset_password.php?token=' . urlencode($token);
                $sent = send_reset_email($email, $resetLink);

                if (!$sent && APP_ENV === 'development') {
                    $debugResetLink = $resetLink;
                }
            }

            $success = 'If this email exists, a reset link has been sent.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL); ?>/css/styles.css">
</head>
<body>
<div class="container">
    <div class="card form-card">
        <h2>Forgot Password</h2>
        <?php if ($success): ?><div class="flash flash-success"><?= e($success); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="flash flash-error"><?= e($error); ?></div><?php endif; ?>
        <?php if ($debugResetLink): ?>
            <div class="flash flash-success">
                Dev mode link: <a href="<?= e($debugResetLink); ?>">Reset Password</a>
            </div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <label>Email</label>
            <input type="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="<?= e(APP_URL); ?>/auth/login.php">Back to login</a></p>
    </div>
</div>
</body>
</html>
