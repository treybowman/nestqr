<?php
/**
 * NestQR Configuration File
 * IMPORTANT: Rename this file to config.php and update with your settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'nestqr_db');

// Site Configuration
define('SITE_URL', 'https://nestqr.com'); // Your main domain (no trailing slash)
define('SITE_NAME', 'NestQR');
define('SITE_EMAIL', 'support@nestqr.com');

// Security
define('SESSION_LIFETIME', 7200); // 2 hours in seconds
define('HASH_ALGO', 'sha256');
define('HASH_COST', 12); // bcrypt cost factor

// File Upload Settings
define('MAX_UPLOAD_SIZE', 10485760); // 10MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']);
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// QR Code Settings
define('QR_CODE_SIZE', 600); // pixels
define('QR_CODE_PRINT_SIZE', 3000); // high-res for printing
define('QR_ERROR_CORRECTION', 'H'); // High (30% damage tolerance)
define('QR_LOGO_SIZE_PERCENT', 20); // Center logo as % of QR
define('QR_ICON_SIZE_PERCENT', 13); // Corner icon as % of QR

// Email Settings (SMTP)
define('SMTP_HOST', 'smtp.gmail.com'); // or your SMTP server
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', SITE_EMAIL);
define('SMTP_FROM_NAME', SITE_NAME);

// Image Moderation API (optional - comment out to disable)
// Using Sightengine as example - you can use AWS Rekognition instead
define('MODERATION_ENABLED', true);
define('MODERATION_API', 'sightengine'); // or 'aws_rekognition'
define('MODERATION_API_USER', 'your_api_user');
define('MODERATION_API_SECRET', 'your_api_secret');

// Plan Limits
define('FREE_QR_LIMIT', 10);
define('PRO_QR_LIMIT', 25);
define('UNLIMITED_QR_LIMIT', 9999);

// Active Domains (markets)
// Add new markets here as you expand
$ACTIVE_DOMAINS = [
    'nestqr' => [
        'name' => 'National',
        'url' => 'https://nestqr.com',
        'is_primary' => true
    ],
    'nestatl' => [
        'name' => 'Atlanta',
        'url' => 'https://nestatl.com',
        'is_primary' => false
    ]
    // Add more as needed:
    // 'nestdfw' => [
    //     'name' => 'Dallas',
    //     'url' => 'https://nestdfw.com',
    //     'is_primary' => false
    // ]
];

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/New_York');

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only over HTTPS
ini_set('session.cookie_samesite', 'Lax');
session_start();
