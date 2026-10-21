<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
$error = ''; $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(customer_value('email'));
    if (!hash_equals($_SESSION['csrf'], customer_value('csrf'))) $error = 'Your session expired. Please try again.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email)>254) $error = 'Enter a valid registered email address.';
    else {
        $message = 'If this email is registered, the account server will send you a password reset link. Check your inbox and spam folder.';
        if (time() - ($_SESSION['reset_requested_at'] ?? 0) >= 60) {
            $_SESSION['reset_requested_at'] = time();
            try { customer_backend_request('/customers/reset-password', ['email'=>$email]); }
            catch (CustomerBackendException $failure) { $message=''; $error='The reset email could not be requested. Please try again in a minute.'; }
        }
    }
}
$title = 'Forgot password?';
require __DIR__ . '/password_reset_view.php';
