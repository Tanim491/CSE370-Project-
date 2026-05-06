<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

$token = sanitize_input($_GET['token'] ?? $_POST['token'] ?? '');
$error = null;
$success = null;
$isTokenValid = false;

if ($token !== '') {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare('SELECT id, email, expires_at FROM Password_resets WHERE token = ? ORDER BY id DESC LIMIT 1');
    $stmt->execute([$token]);
    $tokenRow = $stmt->fetch();

    if ($tokenRow && strtotime($tokenRow['expires_at']) > time()) {
        $isTokenValid = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid CSRF token.';
    } elseif (!$isTokenValid) {
        $error = 'Token is invalid or expired.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        if (!validate_password_strength($password)) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $updateUser = $pdo->prepare('UPDATE Users SET password = ? WHERE email = ?');
            $updateUser->execute([$hash, $tokenRow['email']]);

            $deleteToken = $pdo->prepare('DELETE FROM Password_resets WHERE email = ?');
            $deleteToken->execute([$tokenRow['email']]);

            $success = 'Password reset successful. You can now login.';
            $isTokenValid = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL); ?>/css/styles.css">
</head>
<body>
<div class="container">
    <div class="card form-card">
        <h2>Reset Password</h2>
        <?php if ($success): ?><div class="flash flash-success"><?= e($success); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="flash flash-error"><?= e($error); ?></div><?php endif; ?>

        <?php if ($isTokenValid): ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                <input type="hidden" name="token" value="<?= e($token); ?>">
                <label>New Password</label>
                <input type="password" name="password" required minlength="8">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required minlength="8">
                <button type="submit">Update Password</button>
            </form>
        <?php else: ?>
            <p>Reset token is missing, invalid, or expired.</p>
        <?php endif; ?>

        <p><a href="<?= e(APP_URL); ?>/auth/login.php">Back to login</a></p>
    </div>
</div>
</body>
</html>
