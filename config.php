<?php
/**
 * Configuration File for Nivis Lab E-Commerce
 * Local Development Setup
 */

// ===== SERVER CONFIGURATION =====
define('API_BASE_URL', 'http://localhost/nivis_lab');
define('GRAPHQL_ENDPOINT', API_BASE_URL . '/graphql-api.php');
define('ADMIN_URL', API_BASE_URL . '/admin.php');

// ===== DATABASE CONFIGURATION (Future Use) =====
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nivis_lab');

// ===== SESSION CONFIGURATION =====
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_COOKIE_SECURE', false); // Set to true in production
define('SESSION_COOKIE_HTTPONLY', true);

// ===== PRODUCT CONFIGURATION =====
define('CURRENCY', 'INR');
define('CURRENCY_SYMBOL', '₹');

// ===== DEBUG MODE =====
define('DEBUG_MODE', true); // Set to false in production
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');

// ===== CORS CONFIGURATION =====
define('ALLOWED_ORIGINS', [
    'http://localhost',
    'http://localhost:3000',
    'http://localhost:8080',
    'http://127.0.0.1',
]);

// ===== HELPER FUNCTIONS =====

/**
 * Check if origin is allowed for CORS
 */
function isOriginAllowed($origin) {
    return in_array($origin, ALLOWED_ORIGINS);
}

/**
 * Log errors
 */
function logError($message, $context = []) {
    if (LOG_ERRORS) {
        $log_dir = dirname(ERROR_LOG_FILE);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $context_str = !empty($context) ? ' | ' . json_encode($context) : '';
        $log_message = "[$timestamp] $message$context_str\n";
        
        file_put_contents(ERROR_LOG_FILE, $log_message, FILE_APPEND);
    }
}

/**
 * Send JSON response
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Send error response
 */
function sendErrorResponse($message, $statusCode = 400) {
    sendJsonResponse([
        'success' => false,
        'error' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
    ], $statusCode);
}

// ===== SETUP CORS HEADERS =====
if (isOriginAllowed($_SERVER['HTTP_ORIGIN'] ?? '')) {
    header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

?>
