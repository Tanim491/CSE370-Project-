<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard/groups.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Invalid CSRF token.');
    redirect('dashboard/groups.php');
}

$groupId = (int) ($_POST['group_id'] ?? 0);
$userId = (int) $_SESSION['user_id'];

if ($groupId <= 0) {
    set_flash('error', 'Invalid group selected.');
    redirect('dashboard/groups.php');
}

$pdo = Database::getConnection();

$memberCheck = $pdo->prepare('SELECT id FROM M_ship WHERE user_id = ? AND group_id = ? LIMIT 1');
$memberCheck->execute([$userId, $groupId]);
if ($memberCheck->fetch()) {
    set_flash('error', 'You are already a member of this group.');
    redirect('dashboard/groups.php');
}

$requestCheck = $pdo->prepare("SELECT id FROM Join_req WHERE user_id = ? AND group_id = ? AND status = 'Pending' LIMIT 1");
$requestCheck->execute([$userId, $groupId]);
if ($requestCheck->fetch()) {
    set_flash('error', 'You already have a pending request for this group.');
    redirect('dashboard/groups.php');
}

$insert = $pdo->prepare("INSERT INTO Join_req (user_id, group_id, status) VALUES (?, ?, 'Pending')");
$insert->execute([$userId, $groupId]);



set_flash('success', 'Join request submitted successfully.');
redirect('dashboard/groups.php');
