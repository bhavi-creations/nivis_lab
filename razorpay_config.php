<?php
require_once __DIR__ . '/backend_config.php';

define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_TGv3vuhTwRfJRd');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'gY3fJC2Gg1UmsidG5TDgxOOm');
define('RAZORPAY_API_BASE_URL', getenv('RAZORPAY_API_BASE_URL') ?: EVERSHOP_API_BASE_URL);
define('EVERSHOP_API_BASE_URL', getenv('EVERSHOP_API_BASE_URL') ?: EVERSHOP_API_BASE_URL);
define('RAZORPAY_SYNC_TOKEN', getenv('RAZORPAY_SYNC_TOKEN') ?: 'nivis-lab-razorpay-sync-dev');
define('RAZORPAY_CURRENCY', 'INR');
define('RAZORPAY_COMPANY_NAME', 'Nivis Labs');
?>
