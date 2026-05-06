<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch Profile Data
$user_data = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();
} catch (Exception $e) {}

// Fetch My Groups
$my_groups = [];
try {
    $my_groups_query = "
        SELECT g.*, gm.Role 
        FROM `s_grp` g
        JOIN `Group_Members` gm ON g.id = gm.G_id
        WHERE gm.user_id = ?
    ";
    $stmt = $pdo->prepare($my_groups_query);
    $stmt->execute([$user_id]);
    $my_groups = $stmt->fetchAll();
} catch (Exception $e) {}

// Fetch Upcoming Sessions (Approved requests)
$upcoming_sessions = [];
try {
    $sessions_query = "
        SELECT g.name, g.schedule, g.table_id, jr.status
        FROM `join_req` jr
        JOIN `s_grp` g ON jr.group_id = g.id
        WHERE jr.user_id = ? AND jr.status = 'Approved'
    ";
    $stmt = $pdo->prepare($sessions_query);
    $stmt->execute([$user_id]);
    $upcoming_sessions = $stmt->fetchAll();
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Study Group Finder</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { margin-top: 0; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px; }
        .list-item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .list-item:last-child { border-bottom: none; }
        .badge { padding: 2px 8px; border-radius: 12px; font-size: 0.8em; background: #e9ecef; }
        .badge-leader { background: #d1ecf1; color: #0c5460; }
    </style>
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
    <h2>Welcome back, <?= htmlspecialchars($user_data['name'] ?? 'Student') ?>!</h2>
    <div class="dashboard-grid">
        <div class="card">
            <h3>Profile</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($user_data['name'] ?? 'N/A') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email'] ?? 'N/A') ?></p>
            <p><strong>Department:</strong> <?= htmlspecialchars($user_data['department'] ?? 'CSE') ?></p>
        </div>
        <div class="card">
            <h3>My Groups</h3>
            <?php if (!empty($my_groups)): ?>
                <?php foreach ($my_groups as $group): ?>
                    <div class="list-item">
                        <strong><?= htmlspecialchars($group['name']) ?></strong> 
                        <span class="badge <?= $group['Role'] === 'Leader' ? 'badge-leader' : '' ?>"><?= htmlspecialchars($group['Role']) ?></span><br>
                        <small><?= htmlspecialchars($group['subject']) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have not joined any groups yet.</p>
                <a href="groups.php" class="btn" style="background:#007bff; color:white; padding:5px 15px; border-radius:4px; text-decoration:none;">Discover Groups</a>
            <?php endif; ?>
        </div>
        <div class="card">
            <h3>Upcoming Sessions</h3>
            <?php if (!empty($upcoming_sessions)): ?>
                <?php foreach ($upcoming_sessions as $session): ?>
                    <div class="list-item">
                        <strong><?= htmlspecialchars($session['name']) ?></strong><br>
                        <small>📅 <?= htmlspecialchars($session['schedule']) ?></small><br>
                        <small>📍 Table: <strong><?= htmlspecialchars($session['table_id']) ?></strong></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No upcoming approved sessions.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>