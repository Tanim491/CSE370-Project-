<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$pdo = Database::getConnection();
$currentUser = get_current_user_data();
$currentUserId = (int) $_SESSION['user_id'];

$department = sanitize_input($_GET['department'] ?? ($currentUser['department'] ?? ''));
$skill = sanitize_input($_GET['skill_level'] ?? ($currentUser['skill_level'] ?? ''));

$query = 'SELECT id, name, email, department, skill_level, semester
          FROM Users
          WHERE id != ?';
$params = [$currentUserId];

if ($department !== '') {
    $query .= ' AND department = ?';
    $params[] = $department;
}
if ($skill !== '') {
    $query .= ' AND skill_level = ?';
    $params[] = $skill;
}
$query .= ' ORDER BY name ASC LIMIT 30';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$matches = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Invalid CSRF token.');
        redirect('dashboard/matcher.php');
    }

    $partnerId = (int) ($_POST['partner_id'] ?? 0);
    if ($partnerId <= 0 || $partnerId === $currentUserId) {
        set_flash('error', 'Invalid partner selected.');
        redirect('dashboard/matcher.php');
    }

    $check = $pdo->prepare('SELECT id FROM S_Partner WHERE user_id = ? AND partner_user_id = ? LIMIT 1');
    $check->execute([$currentUserId, $partnerId]);
    if ($check->fetch()) {
        set_flash('error', 'You already matched this user.');
        redirect('dashboard/matcher.php');
    }

    $insert = $pdo->prepare('INSERT INTO S_Partner (user_id, partner_user_id, created_at) VALUES (?, ?, NOW())');
    $insert->execute([$currentUserId, $partnerId]);

    set_flash('success', 'Study partner matched successfully.');
    redirect('dashboard/matcher.php');
}

$success = get_flash('success');
$error = get_flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matcher - <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL); ?>/css/styles.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-wrap">
        <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/dashboard/dashboard.php" style="text-decoration: none; color: inherit;">
            <strong><?= function_exists('e') && defined('APP_NAME') ? e(APP_NAME) : 'Study Group Finder'; ?></strong>
        </a>
        <div class="nav-links">
            <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/dashboard/dashboard.php">Dashboard</a>
            <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/dashboard/groups.php">Groups</a>
            <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/dashboard/resources.php">Resources</a>
            <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/dashboard/matcher.php">Matcher</a>
            <a href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/auth/logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container">
    <h2>Study Partner Matcher</h2>
    <?php if ($success): ?><div class="flash flash-success"><?= e($success); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="flash flash-error"><?= e($error); ?></div><?php endif; ?>

    <form method="get" class="card">
        <label>Department</label>
        <input type="text" name="department" value="<?= e($department); ?>">
        <label>Skill Level</label>
        <select name="skill_level">
            <option value="">Any</option>
            <option value="Beginner" <?= $skill === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
            <option value="Intermediate" <?= $skill === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
            <option value="Advanced" <?= $skill === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <div class="grid">
        <?php foreach ($matches as $match): ?>
            <div class="card">
                <h3><?= e($match['name']); ?></h3>
                <p><?= e($match['department']); ?> | <?= e($match['skill_level']); ?> | Semester <?= e($match['semester']); ?></p>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                    <input type="hidden" name="partner_id" value="<?= (int) $match['id']; ?>">
                    <button type="submit">Match</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
