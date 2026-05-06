<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;
$success = '';
$error = '';

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit();
}

// --- HANDLE USER TYPE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_type = $_POST['user_type'];
    try {
        // Ensure you added the user_type column to your users table in phpMyAdmin!
        $stmt = $pdo->prepare("UPDATE users SET user_type = ? WHERE id = ?");
        $stmt->execute([$new_type, $user_id]);
        
        $_SESSION['user_type'] = $new_type;
        $success = "Profile updated successfully! Your access level is now: $new_type.";
    } catch (Exception $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}

// --- FETCH CURRENT USER DATA ---
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    die("Error fetching profile.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Study Group Finder</title>
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
    <div class="card" style="max-width: 500px; margin: 40px auto; padding: 30px;">
        <h2>User Profile</h2>
        
        <?php if ($success): ?><div style="color: #155724; background: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 4px;"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div style="color: #721c24; background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 4px;"><?= $error ?></div><?php endif; ?>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #007bff; margin-bottom: 20px;">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Current Access:</strong> <?= htmlspecialchars($user['user_type'] ?? 'Resource') ?></p>
        </div>

        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label for="user_type" style="font-weight: bold; display:block; margin-bottom:5px;">Change Access Level</label>
                <select name="user_type" id="user_type" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Resource" <?= (($user['user_type'] ?? 'Resource') == 'Resource') ? 'selected' : '' ?>>1. Resource Only</option>
                    <option value="Group" <?= (($user['user_type'] ?? 'Resource') == 'Group') ? 'selected' : '' ?>>2. Resource & Group Membership</option>
                    <option value="Contributor" <?= (($user['user_type'] ?? 'Resource') == 'Contributor') ? 'selected' : '' ?>>3. Resource, Group & Contribution</option>
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">*Contributor level allows you to upload materials.</small>
            </div>

            <button type="submit" name="update_profile" class="btn" style="width: 100%; background: #007bff;">Update Profile Settings</button>
        </form>
    </div>
</div>

</body>
</html>