<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$pdo = Database::getConnection();

$groupId = (int) ($_GET['group_id'] ?? 0);

if ($groupId <= 0) {
    set_flash('error', 'Invalid group.');
    redirect('groups.php');
}

// Fetch pending requests
$stmt = $pdo->prepare("
    SELECT jr.id, jr.user_id, u.name 
    FROM Join_req jr
    JOIN users u ON jr.user_id = u.id
    WHERE jr.group_id = ? AND jr.status = 'Pending'
");
$stmt->execute([$groupId]);

$requests = $stmt->fetchAll();
?>

<h2>Join Requests</h2>

<?php if (!$requests): ?>
    <p>No pending requests.</p>
<?php endif; ?>

<?php foreach ($requests as $req): ?>
    <p>
        <?= htmlspecialchars($req['name']) ?>

        <a href="accept_request.php?id=<?= $req['id'] ?>&group_id=<?= $groupId ?>">Accept</a>
        |
        <a href="reject_request.php?id=<?= $req['id'] ?>&group_id=<?= $groupId ?>">Reject</a>
    </p>
<?php endforeach; ?>


<!-- Example table row for a request -->
<tr>
    <td>User Name</td>
    <td>
        <a href="process_request.php?id=12&action=accept" class="btn-accept">Accept</a>
        <a href="process_request.php?id=12&action=reject" class="btn-reject">Reject</a>
    </td>
</tr>