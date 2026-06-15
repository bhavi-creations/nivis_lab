<?php
require_once __DIR__ . '/backend_config.php';

define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: '');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: '');
define('RAZORPAY_API_BASE_URL', getenv('RAZORPAY_API_BASE_URL') ?: EVERSHOP_BASE_URL);
define('EVERSHOP_API_BASE_URL', getenv('EVERSHOP_API_BASE_URL') ?: EVERSHOP_BASE_URL);
define('RAZORPAY_CURRENCY', 'INR');
define('RAZORPAY_COMPANY_NAME', 'Nivis Labs');
?>
