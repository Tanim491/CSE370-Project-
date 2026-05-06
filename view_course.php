<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$error = '';
$course = null;

// 1. Get the Course ID from the URL (e.g., view_course.php?id=CSE370)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $course_id = $_GET['id'];
    $pdo = Database::getConnection();

    try {
        // Fetch the specific course details
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch();

        if (!$course) {
            $error = "Course not found.";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
} else {
    $error = "No course selected.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $course ? htmlspecialchars($course['course_id']) : 'Course Error' ?> - <?= function_exists('e') && defined('APP_NAME') ? e(APP_NAME) : 'Study Group Finder'; ?></title>
    <link rel="stylesheet" href="<?= function_exists('e') && defined('APP_URL') ? e(APP_URL) : '..'; ?>/css/styles.css">
    <style>
        .file-card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; background-color: #fafafa; display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .file-info h4 { margin: 0 0 5px 0; color: #333; font-size: 1.1em; }
        .file-info p { margin: 0; color: #666; font-size: 0.9em; }
        .action-buttons { display: flex; gap: 10px; }
        .btn-view { background-color: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        .btn-download { background-color: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar">
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
    
    <div style="margin-bottom: 20px;">
        <a href="resources.php" style="color: #007bff; text-decoration: none;">← Back to All Resources</a>
    </div>

    <?php if ($error): ?>
        <div class="flash flash-error"><?= htmlspecialchars($error); ?></div>
    <?php elseif ($course): ?>
        
        <!-- Course Header -->
        <div style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px;">
            <h1 style="margin: 0; color: #2c3e50;"><?= htmlspecialchars($course['course_id']); ?></h1>
            <h3 style="margin: 5px 0 0 0; color: #6c757d; font-weight: normal;"><?= htmlspecialchars($course['course_name']); ?></h3>
        </div>

        <!-- Files Section -->
        <h2>Available Files</h2>
        
        <?php if (!empty($course['resource_link'])): ?>
            <!-- Grab the file extension (like .pdf or .doc) to display to the user -->
            <?php 
                $file_path = $course['resource_link'];
                $file_name = basename($file_path);
                $file_ext = strtoupper(pathinfo($file_path, PATHINFO_EXTENSION));
            ?>
            <div class="file-card">
                <div class="file-info">
                    <h4>Primary Study Material</h4>
                    <p>Format: <strong><?= htmlspecialchars($file_ext); ?></strong> | Uploaded File: <?= htmlspecialchars($file_name); ?></p>
                </div>
                <div class="action-buttons">
                    <!-- target="_blank" opens the file in a new browser tab to view it -->
                    <a href="<?= htmlspecialchars($file_path); ?>" target="_blank" class="btn-view">👁️ View</a>
                    
                    <!-- 'download' attribute forces the browser to save it to their computer -->
                    <a href="<?= htmlspecialchars($file_path); ?>" download class="btn-download">⬇️ Download</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 40px; color: #666;">
                <p style="font-size: 1.2em; margin-bottom: 10px;">No files have been uploaded for this course yet.</p>
                <p>Go back to the Resources page to upload materials!</p>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
</body>
</html>