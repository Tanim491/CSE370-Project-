<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $subject = $_POST['subject'];
    $file = $_FILES['resource_file'];

    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = time() . '_' . basename($file['name']);
    $target_file = $upload_dir . $filename;
    $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

    if (in_array($file_ext, $allowed)) {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO resources (title, subject, file_path, contributor_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $subject, $target_file, $user_id]);
                header("Location: resources.php?subject=" . urlencode($subject) . "&msg=success");
            } catch (Exception $e) {
                die("Database Error: " . $e->getMessage());
            }
        } else {
            die("Error uploading file.");
        }
    } else {
        die("Invalid file type. Only PDF and Images allowed.");
    }
}
exit();