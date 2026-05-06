<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$success = '';
$error = '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Error: User session not found. Please log in again.");
}

// Fetch Courses
$course_list = [];
try {
    $stmt = $pdo->query("SELECT course_id, course_name FROM `courses` ORDER BY course_id ASC");
    $course_list = $stmt->fetchAll();
} catch (Exception $e) {}

// Table Availability Logic
$available_tables = [];
$selected_day = $_POST['day_of_week'] ?? 'Saturday';
$selected_start = $_POST['start_time'] ?? '';
$selected_end = "";

if (!empty($selected_start)) {
    $selected_end = date("H:i", strtotime($selected_start . " +90 minutes"));
    try {
        $table_query = "
            SELECT table_id FROM `tables` 
            WHERE table_id NOT IN (
                SELECT table_id FROM `table_schedule` 
                WHERE day_of_week = ? AND start_time < ? AND end_time > ?
            ) ORDER BY table_id ASC
        ";
        $stmt = $pdo->prepare($table_query);
        $stmt->execute([$selected_day, $selected_end, $selected_start]);
        $available_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($available_tables)) {
            $error = "No tables available for this time slot.";
        }
    } catch (Exception $e) { $error = "DB Error."; }
} else {
    $available_tables = $pdo->query("SELECT table_id FROM `tables` ORDER BY table_id ASC")->fetchAll(PDO::FETCH_COLUMN);
}

// Handle Group Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_group'])) {
    $name = trim($_POST['g_name']);
    $course_id = $_POST['course_id'];
    $table_id = $_POST['table_id'] ?? '';

    if (!empty($name) && !empty($course_id) && !empty($table_id)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO `s_grp` (name, subject, department, schedule, table_id, max_members) VALUES (?, (SELECT course_name FROM `courses` WHERE course_id = ?), 'CSE', ?, ?, 3)");
            $full_sched = $selected_day . " (" . $selected_start . " - " . $selected_end . ")";
            $stmt->execute([$name, $course_id, $full_sched, $table_id]);
            $new_group_id = $pdo->lastInsertId();

            $pdo->prepare("INSERT INTO `Group_Members` (G_id, user_id, Role) VALUES (?, ?, 'Leader')")->execute([$new_group_id, $user_id]);
            $pdo->prepare("INSERT INTO `table_schedule` (table_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)")->execute([$table_id, $selected_day, $selected_start, $selected_end]);

            $pdo->commit();
            $success = "Group Created! Table $table_id reserved.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Creation failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Group</title>
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
        <h2>Create a New Study Group</h2>
        <a href="groups.php" class="btn" style="background:#6c757d; color:white; text-decoration:none;">← Back</a>
    </div>

    <?php if ($error): ?><div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-radius:4px;"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border-radius:4px;"><?= $success ?></div><?php endif; ?>

    <form method="post" class="card" style="padding: 25px;">
        <label style="font-weight:bold;">Group Name</label>
        <input type="text" name="g_name" required value="<?= htmlspecialchars($_POST['g_name'] ?? '') ?>" style="width:100%; padding:10px; margin-bottom:15px;">

        <label style="font-weight:bold;">Course</label>
        <select name="course_id" required style="width:100%; padding:10px; margin-bottom:15px;">
            <option value="">-- Select Course --</option>
            <?php foreach ($course_list as $course): ?>
                <option value="<?= $course['course_id'] ?>" <?= (($_POST['course_id'] ?? '') == $course['course_id']) ? 'selected' : '' ?>><?= $course['course_id'] ?> - <?= $course['course_name'] ?></option>
            <?php endforeach; ?>
        </select>

        <h4 style="margin: 15px 0;">Schedule (1.5H Slot)</h4>
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <div style="flex: 1;"><select name="day_of_week" style="width:100%; padding:10px;"><?php foreach(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'] as $d): ?><option value="<?= $d ?>" <?= ($selected_day == $d) ? 'selected' : '' ?>><?= $d ?></option><?php endforeach; ?></select></div>
            <div style="flex: 1;"><input type="time" name="start_time" required value="<?= htmlspecialchars($selected_start) ?>" style="width:100%; padding:10px;"></div>
            <div style="flex: 1;"><input type="text" readonly value="<?= htmlspecialchars($selected_end) ?>" placeholder="+90m" style="width:100%; padding:10px; background:#f4f4f4;"></div>
        </div>

        <button type="submit" name="check_avail" class="btn" style="background:#6c757d; margin-bottom:20px;">Check Tables</button>

        <select name="table_id" required style="width:100%; padding:10px; border:1px solid #007bff; margin-bottom:25px;">
            <option value="">-- Choose a Table --</option>
            <?php foreach ($available_tables as $id): ?><option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option><?php endforeach; ?>
        </select>

        <button type="submit" name="create_group" class="btn" style="width:100%; background:#007bff; padding:12px;">Create Group</button>
    </form>
</div>
</body>
</html>