<?php
require_once __DIR__ . '/customer_auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php'); exit;
}
if (!hash_equals($_SESSION['csrf'] ?? '', customer_value('csrf')) || empty($_SESSION['csrf'])) {
    $_SESSION['auth_error'] = 'Your session expired. Please try again.';
    header('Location: login.php'); exit;
}
$mode = customer_value('mode') === 'signup' ? 'signup' : 'login';
$email = strtolower(customer_value('email') ?: customer_value('username'));
$name = customer_value('name');
$address = customer_value('address');
$phone = customer_value('phone');
$_SESSION['auth_old'] = compact('email', 'name', 'address', 'phone');
try {
    $db = customer_db();
    if ($mode === 'signup') {
        if ($name === '' || strlen($name) > 120 || $address === '' || strlen($address) > 4000 || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\+?[0-9]{10,15}$/D', $phone)) {
            throw new RuntimeException('Enter your name, address, valid email, and a phone number with 10 to 15 digits (optional +).');
        }
        $insert = $db->prepare('INSERT INTO website_customers (name,address,phone,email,password_hash) VALUES (?,?,?,?,?)');
        $insert->execute([$name, $address, $phone, $email, password_hash($phone, PASSWORD_DEFAULT)]);
        $customer = ['id' => $db->lastInsertId(), 'name' => $name];
        customer_login($customer);
        $_SESSION['auth_notice'] = 'Account created. Your email is your username and your phone number is your password.';
        try {
            customer_welcome($name, $email, $phone);
            $db->prepare('UPDATE website_customers SET welcome_email_sent_at=NOW() WHERE id=?')->execute([$customer['id']]);
            $_SESSION['auth_notice'] .= ' Your login credentials have been emailed to you.';
        } catch (Throwable $mailError) {
            error_log('Customer welcome email failed: ' . $mailError->getMessage());
            $_SESSION['auth_notice'] .= ' The welcome email could not be sent. You can still log in using these details.';
        }
    } else {
        if (($_SESSION['login_attempts'] ?? 0) >= 5 && time() - ($_SESSION['login_attempt_time'] ?? 0) < 300) {
            throw new RuntimeException('Too many attempts. Please try again in five minutes.');
        }
        if (time() - ($_SESSION['login_attempt_time'] ?? 0) >= 300) { $_SESSION['login_attempts'] = 0; }
        $query = $db->prepare('SELECT id,name,password_hash FROM website_customers WHERE email=?');
        $query->execute([$email]);
        $customer = $query->fetch();
        if (!$customer || !password_verify(customer_value('password'), $customer['password_hash'])) {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['login_attempt_time'] = time();
            throw new RuntimeException('Invalid email or password.');
        }
        customer_login($customer);
        unset($_SESSION['login_attempts'], $_SESSION['login_attempt_time']);
    }
    unset($_SESSION['auth_old']);
    header('Location: login.php'); exit;
} catch (PDOException $error) {
    $_SESSION['auth_error'] = $error->getCode() === '23000' ? 'This email is already registered. Please log in.' : 'Account service is unavailable. Please try again later.';
    if ($error->getCode() !== '23000') { error_log('Customer database error: ' . $error->getMessage()); }
} catch (RuntimeException $error) {
    $_SESSION['auth_error'] = $error->getMessage();
}
header('Location: login.php?mode=' . $mode);
exit;
