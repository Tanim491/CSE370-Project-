<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$pdo = Database::getConnection();

$requestId = (int) ($_GET['id'] ?? 0);
$groupId = (int) ($_GET['group_id'] ?? 0);

if ($requestId <= 0) {
    set_flash('error', 'Invalid request.');
    redirect('groups.php');
}

// Update request status
$pdo->prepare("UPDATE Join_req SET status = 'Accepted' WHERE id = ?")
    ->execute([$requestId]);

// Add user to group
$pdo->prepare("
    INSERT INTO M_ship (user_id, group_id)
    SELECT user_id, group_id FROM Join_req WHERE id = ?
")->execute([$requestId]);

set_flash('success', 'Request accepted.');
redirect("manage_requests.php?group_id=$groupId");