<?php
require_once __DIR__ . '/backend_config.php';

if (!defined('RAZORPAY_KEY_ID')) {
    define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_TGv3vuhTwRfJRd');
}
if (!defined('RAZORPAY_KEY_SECRET')) {
    define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'gY3fJC2Gg1UmsidG5TDgxOOm');
}
if (!defined('RAZORPAY_API_BASE_URL')) {
    define('RAZORPAY_API_BASE_URL', getenv('RAZORPAY_API_BASE_URL') ?: EVERSHOP_API_BASE_URL);
}
if (!defined('RAZORPAY_SYNC_TOKEN')) {
    define('RAZORPAY_SYNC_TOKEN', getenv('RAZORPAY_SYNC_TOKEN') ?: 'nivis-lab-razorpay-sync-dev');
}
if (!defined('RAZORPAY_CURRENCY')) {
    define('RAZORPAY_CURRENCY', 'INR');
}
if (!defined('RAZORPAY_COMPANY_NAME')) {
    define('RAZORPAY_COMPANY_NAME', 'Nivis Labs');
}
?>
