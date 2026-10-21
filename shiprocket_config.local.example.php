<?php
// Copy to shiprocket_config.local.php on the PHP server; do not commit real values.
if (!defined('SHIPROCKET_API_EMAIL')) {
    define('SHIPROCKET_API_EMAIL', 'your-api-user@example.com');
}
if (!defined('SHIPROCKET_API_PASSWORD')) {
    define('SHIPROCKET_API_PASSWORD', 'your-api-user-password');
}
define('SHIPROCKET_LOCAL_PICKUP_LOCATION', 'Home'); // Exact name from your account.
// Optional hosting override: a persistent folder writable by the PHP user.
// define('SHIPROCKET_LOCAL_STORAGE_DIR', '/home/ACCOUNT/private/shiprocket');
