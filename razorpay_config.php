<?php
require_once __DIR__ . '/backend_config.php';
if (!defined('RAZORPAY_API_BASE_URL')) {
    define('RAZORPAY_API_BASE_URL', getenv('RAZORPAY_API_BASE_URL') ?: EVERSHOP_API_BASE_URL);
}
if (!defined('RAZORPAY_CURRENCY')) {
    define('RAZORPAY_CURRENCY', 'INR');
}
if (!defined('RAZORPAY_COMPANY_NAME')) {
    define('RAZORPAY_COMPANY_NAME', 'Nivis Labs');
}
?>
