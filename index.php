<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard/dashboard.php');
}

redirect('auth/login.php');
