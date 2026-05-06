<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;
$group_id = $_GET['group_id'] ?? null;

if ($user_id && $group_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO `join_req` (user_id, group_id, status) VALUES (?, ?, 'Pending')");
        $stmt->execute([$user_id, $group_id]);
        header("Location: groups.php?msg=requested");
    } catch (PDOException $e) {
        header("Location: groups.php?error=already_requested");
    }
} else {
    header("Location: groups.php");
}
exit();