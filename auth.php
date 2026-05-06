<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secureCookie = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0;
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Please login first.');
        redirect('auth/login.php');
    }
}

function get_current_user_data(): ?array
{
    if (!is_logged_in()) {
        return null;
    }

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['user_id']]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function is_admin(int $userId): bool
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT role FROM M_ship WHERE user_id = ? AND role = 'Admin' LIMIT 1");
    $stmt->execute([$userId]);
    return (bool) $stmt->fetch();
}

function require_admin(): void
{
    require_login();
    $userId = (int) $_SESSION['user_id'];
    if (!is_admin($userId)) {
        set_flash('error', 'Admin access required.');
        redirect('dashboard/dashboard.php');
    }
}
