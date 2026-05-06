<?php
declare(strict_types=1);

/**
 * Global configuration.
 * Uses environment variables when available for safer deployments.
 */

define('APP_NAME', 'Study Group Finder System');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost/cse370');
define('APP_ENV', getenv('APP_ENV') ?: 'development');

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'study_group_finder');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

define('UPLOAD_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'uploads');
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024); // 5 MB

/**
 * Optional SMTP/PHPMailer settings.
 * Keep empty to use PHP mail() fallback.
 */
define('MAIL_HOST', getenv('MAIL_HOST') ?: '');
define('MAIL_PORT', (int) (getenv('MAIL_PORT') ?: 587));
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: '');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: '');
define('MAIL_FROM', getenv('MAIL_FROM') ?: 'no-reply@example.com');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: APP_NAME);
