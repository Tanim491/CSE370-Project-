<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard/dashboard.php');
}

$error = null;
$success = get_flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid CSRF token.';
    } else {
        $email = sanitize_input($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $error = 'Invalid login credentials.';
        } else {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT id, password FROM Users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                redirect('dashboard/dashboard.php');
            }

            $error = 'Invalid login credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL); ?>/css/styles.css">
</head>
<body>
<div class="container">
    <div class="card form-card">
        <h2>Login</h2>
        <?php if ($success): ?><div class="flash flash-success"><?= e($success); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="flash flash-error"><?= e($error); ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <label>Email</label>
            <input type="email" name="email" required value="<?= old('email'); ?>">

            <label>Password</label>
            <input type="password" name="password" required minlength="8">

            <button type="submit">Login</button>
        </form>
        <p><a href="<?= e(APP_URL); ?>/auth/forgot_password.php">Forgot Password?</a></p>
        <p>Need an account? <a href="<?= e(APP_URL); ?>/auth/register.php">Register</a></p>
    </div>
</div>
</body>
</html>
