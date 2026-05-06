<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'] ?? null;
$group_id = $_GET['group_id'] ?? null;

if ($user_id && $group_id) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT table_id, schedule FROM `s_grp` WHERE id = ?");
        $stmt->execute([$group_id]);
        $group = $stmt->fetch();

        $check = $pdo->prepare("SELECT Role FROM `Group_Members` WHERE G_id = ? AND user_id = ?");
        $check->execute([$group_id, $user_id]);
        
        if ($check->fetchColumn() === 'Leader') {
            preg_match('/^([a-zA-Z]+)/', $group['schedule'], $matches);
            $day = $matches[1] ?? '';

            $pdo->prepare("DELETE FROM `table_schedule` WHERE table_id = ? AND day_of_week = ?")->execute([$group['table_id'], $day]);
            $pdo->prepare("DELETE FROM `join_req` WHERE group_id = ?")->execute([$group_id]);
            $pdo->prepare("DELETE FROM `Group_Members` WHERE G_id = ?")->execute([$group_id]);
            $pdo->prepare("DELETE FROM `s_grp` WHERE id = ?")->execute([$group_id]);

            $pdo->commit();
            header("Location: groups.php?success=deleted");
        } else {
            die("Unauthorized: Only the leader can delete this group.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: groups.php");
}
exit();