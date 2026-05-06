<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard/resources.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Invalid CSRF token.');
    redirect('dashboard/resources.php');
}

$userId = (int) $_SESSION['user_id'];
$groupId = (int) ($_POST['group_id'] ?? 0);

if ($groupId <= 0 || !isset($_FILES['resource_file'])) {
    set_flash('error', 'Invalid upload request.');
    redirect('dashboard/resources.php');
}

if ($_FILES['resource_file']['error'] !== UPLOAD_ERR_OK) {
    set_flash('error', 'File upload failed.');
    redirect('dashboard/resources.php?group_id=' . $groupId);
}

$allowedExtensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
$allowedMimes = [
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/png',
];

$originalName = (string) $_FILES['resource_file']['name'];
$tmpName = (string) $_FILES['resource_file']['tmp_name'];
$size = (int) $_FILES['resource_file']['size'];
$extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions, true)) {
    set_flash('error', 'Invalid file extension.');
    redirect('dashboard/resources.php?group_id=' . $groupId);
}

if ($size > MAX_UPLOAD_BYTES) {
    set_flash('error', 'File is larger than 5MB.');
    redirect('dashboard/resources.php?group_id=' . $groupId);
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
if ($finfo) {
    finfo_close($finfo);
}

if (!in_array($mime, $allowedMimes, true)) {
    set_flash('error', 'Invalid file type.');
    redirect('dashboard/resources.php?group_id=' . $groupId);
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$safeBase = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($originalName)) ?: 'file';
$newFileName = uniqid('res_', true) . '_' . $safeBase;
$destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $newFileName;

if (!move_uploaded_file($tmpName, $destination)) {
    set_flash('error', 'Unable to save uploaded file.');
    redirect('dashboard/resources.php?group_id=' . $groupId);
}

$relativePath = 'uploads/' . $newFileName;
$pdo = Database::getConnection();
$stmt = $pdo->prepare('INSERT INTO Resources (file_name, file_path, user_id, group_id) VALUES (?, ?, ?, ?)');
$stmt->execute([$originalName, $relativePath, $userId, $groupId]);

set_flash('success', 'Resource uploaded successfully.');
redirect('dashboard/resources.php?group_id=' . $groupId);
