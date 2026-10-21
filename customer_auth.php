<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['use_strict_mode' => true, 'cookie_httponly' => true,
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'cookie_samesite' => 'Lax']);
}
function customer_db() {
    require_once __DIR__ . '/config.php';
    return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
}
function customer_value($key) {
    return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
}
function customer_escape($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function customer_login($customer) {
    session_regenerate_id(true);
    $_SESSION['customer_id'] = (int)$customer['id'];
    $_SESSION['customer_name'] = $customer['name'];
    $_SESSION['logged_in'] = true;
    $_SESSION['website_login'] = true;
}
function customer_welcome($name, $email, $phone) {
    require_once __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/smtp_settings.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = getenv('NIVIS_SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int)(getenv('NIVIS_SMTP_PORT') ?: 587);
    $mail->Timeout = 15;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($smtpUser, 'Nivis Labs International');
    $mail->addAddress($email, $name);
    $mail->Subject = 'Welcome to Nivis Labs International - Login credentials';
    $mail->Body = "Hello {$name},\n\nThank you for login Nivis Labs International.\n\nLogin credentials\nUsername: {$email}\nPassword: {$phone}\n\nUse your email and phone number to log in next time.\n\nNivis Labs International";
    $mail->send();
}
