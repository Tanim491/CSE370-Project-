<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard/dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    }

    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $semester = sanitize_input($_POST['semester'] ?? '');
    $skillLevel = sanitize_input($_POST['skill_level'] ?? '');
    $department = sanitize_input($_POST['department'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '' || $semester === '' || $skillLevel === '' || $department === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!validate_password_strength($password)) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $pdo = Database::getConnection();
        $checkStmt = $pdo->prepare('SELECT id FROM Users WHERE email = ? LIMIT 1');
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            $errors[] = 'Email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO Users (name, email, password, semester, skill_level, department) VALUES (?, ?, ?, ?, ?, ?)');
            $insert->execute([$name, $email, $hash, $semester, $skillLevel, $department]);
            set_flash('success', 'Registration successful. Please login.');
            redirect('auth/login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL); ?>/css/styles.css">
</head>
<body>
<div class="container">
    <div class="card form-card">
        <h2>Create Account</h2>
        <?php foreach ($errors as $error): ?>
            <div class="flash flash-error"><?= e($error); ?></div>
        <?php endforeach; ?>
        <form method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <label>Name</label>
            <input type="text" name="name" required value="<?= old('name'); ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?= old('email'); ?>">

            <label>Password</label>
            <input type="password" name="password" required minlength="8">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required minlength="8">

            <label>Semester</label>
            <input type="text" name="semester" required value="<?= old('semester'); ?>">

            <label>Skill Level</label>
            <select name="skill_level" required>
                <option value="">Select Skill Level</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>

            <label>Department</label>
            <input type="text" name="department" required value="<?= old('department'); ?>">
            <label>Access Level (User Type):</label>
            <select name="user_type" required>
                <option value="Resource">Resource Only</option>
                <option value="Group">Resource and Group Membership</option>
                <option value="Contributor">Resource, Group, and Contribution</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="<?= e(APP_URL); ?>/auth/login.php">Login</a></p>
    </div>
</div>
</body>
</html>
