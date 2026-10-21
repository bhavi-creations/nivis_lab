<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
$error = ''; $message = '';
$token = $_SERVER['REQUEST_METHOD'] === 'POST' ? customer_value('token') : (is_string($_GET['token'] ?? null) ? $_GET['token'] : '');
$valid = (bool)preg_match('/^[a-f0-9]{128}$/D', $token);
if (!$valid) $error = 'This reset link is invalid. Please request a new link.';
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $confirmation = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';
    if (!hash_equals($_SESSION['csrf'], customer_value('csrf'))) $error = 'Your session expired. Please try again.';
    elseif (strlen($password)<8 || strlen($password)>72 || trim($password)!==$password) $error = 'Use 8 to 72 characters without leading or trailing spaces.';
    elseif ($password !== $confirmation) $error = 'The passwords do not match.';
    else {
        try {
            customer_backend_reset_password($token,$password);
            $message = 'Your password has been changed. Log in using your email and new password.';
            $valid = false;
            unset($_SESSION['customer_id'], $_SESSION['backend_access_token'], $_SESSION['backend_refresh_token'], $_SESSION['auth_backend'], $_SESSION['customer_name'], $_SESSION['logged_in'], $_SESSION['website_login'], $_SESSION['login_attempts']);
            session_regenerate_id(true);
        } catch (CustomerBackendException $failure) { $error = 'The reset link could not be used. It may be expired or already used. Please request a new link.'; }
    }
}
$title = 'Change your password';
require __DIR__ . '/password_reset_view.php';
