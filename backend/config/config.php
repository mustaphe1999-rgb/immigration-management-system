<?php
/**
 * Immigration Management System
 * Database Configuration
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change this to your password
define('DB_NAME', 'immigration_db');
define('DB_PORT', 3306);

// Application Settings
define('APP_NAME', 'Immigration Management System');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production
define('APP_DEBUG', true);

// Security Settings
define('JWT_SECRET', 'your-secret-key-change-this'); // Change this!
define('SESSION_TIMEOUT', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 8);

// File Upload Settings
define('UPLOAD_DIR', '../uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Email Configuration (optional)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'your-email@gmail.com');
define('MAIL_PASS', 'your-password');
define('MAIL_FROM', 'noreply@immigration-system.com');

// API Settings
define('API_BASE_URL', 'http://localhost/immigration-system/api/');
define('API_VERSION', 'v1');

// Timezone
date_default_timezone_set('UTC');

// Database Connection Function
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        
        // Set charset to UTF-8
        $conn->set_charset('utf8mb4');
        
        return $conn;
    } catch (Exception $e) {
        die('Database connection error: ' . $e->getMessage());
    }
}

// Error Handling
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// CORS Headers (for API)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

?>