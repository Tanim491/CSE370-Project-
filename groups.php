<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';



$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Error: User session not found. Please log in again.");
}

$query = "
    SELECT 
        g.*, 
        u.name AS creator_name,
        gm_leader.user_id AS leader_id,
        (SELECT COUNT(*) FROM `Group_Members` WHERE `G_id` = g.id AND `user_id` = ?) AS is_member,
        (SELECT `status` FROM `join_req` WHERE `group_id` = g.id AND `user_id` = ? LIMIT 1) AS request_status
    FROM `S_Grp` g
    LEFT JOIN `Group_Members` gm_leader ON g.id = gm_leader.G_id AND gm_leader.Role = 'Leader'
    LEFT JOIN `users` u ON gm_leader.user_id = u.id
    ORDER BY g.id DESC
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $user_id]);
    $groups = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Study Groups</title>
    <link rel="stylesheet" href="../css/styles.css">
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0;">
        <h2>Find Study Groups</h2>
        <a href="create_group.php" class="btn" style="background-color: #28a745;">+ Create New Group</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        <?php foreach ($groups as $group): ?>
            <div class="card">
                <h3><?= htmlspecialchars($group['name']); ?></h3>
                <p><strong>Subject:</strong> <?= htmlspecialchars($group['subject']); ?></p>
                <p><strong>Table:</strong> <span style="color: #007bff; font-weight: bold;"><?= htmlspecialchars($group['table_id'] ?? 'N/A'); ?></span></p>
                <p><strong>Schedule:</strong> <?= htmlspecialchars($group['schedule']); ?></p>
                <p><strong>Creator:</strong> <?= htmlspecialchars($group['creator_name'] ?? 'System'); ?></p>

                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <div style="display: flex; gap: 10px;">
                        <?php if ($group['is_member'] > 0): ?>
                            <button class="btn" disabled style="flex: 1; background-color: #28a745; color: white;">Joined</button>
                        <?php elseif ($group['request_status'] !== null): ?>
                            <button class="btn" disabled style="flex: 1; background-color: #ffc107; color: black;"><?= htmlspecialchars($group['request_status']); ?></button>
                        <?php else: ?>
                            <a href="process_request.php?group_id=<?= $group['id']; ?>" class="btn" style="flex: 1; text-align: center; background-color: #6f42c1; color: white; text-decoration: none;">Request to Join</a>
                        <?php endif; ?>
                        
                        <a href="resources.php?subject=<?= urlencode($group['subject']); ?>" class="btn" style="flex: 1; text-align: center; background-color: #17a2b8; color: white; text-decoration: none;">📚 Resources</a>
                    </div>

                    <?php if ($user_id == $group['leader_id']): ?>
                        <a href="delete_group.php?group_id=<?= $group['id']; ?>" class="btn" style="background-color: #dc3545; color: white; text-align: center; text-decoration: none;" onclick="return confirm('Delete this group?');">🗑️ Delete Group</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>