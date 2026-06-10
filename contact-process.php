<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

function postValue($key)
{
    return trim($_POST[$key] ?? '');
}

$name = postValue('name');
$email = postValue('email');
$phone = postValue('phone');
$subject = postValue('subject');
$message = postValue('message');

if ($name === '' || $email === '' || $phone === '' || $subject === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please fill all fields with a valid email address.'); window.history.back();</script>";
    exit;
}

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

$smtpUser = getenv('NIVIS_SMTP_USER') ?: 'manimalladi05@gmail.com';
$smtpPass = getenv('NIVIS_SMTP_PASS') ?: 'cvarqcchfjpawxvo';
$toEmail = getenv('NIVIS_CONTACT_TO') ?: 'manimalladi05@gmail.com';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->setFrom($smtpUser, 'Nivis Labs Website');
    $mail->addAddress($toEmail, 'Nivis Labs');
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Enquiry - ' . $subject;
    $mail->Body = "
        <h2>New Contact Form Submission</h2>
        <table border='1' cellpadding='10' cellspacing='0' width='100%'>
            <tr><th align='left'>Name</th><td>{$safeName}</td></tr>
            <tr><th align='left'>Email</th><td>{$safeEmail}</td></tr>
            <tr><th align='left'>Phone</th><td>{$safePhone}</td></tr>
            <tr><th align='left'>Subject</th><td>{$safeSubject}</td></tr>
            <tr><th align='left'>Message</th><td>{$safeMessage}</td></tr>
        </table>
    ";
    $mail->AltBody = "New Contact Form Submission\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$subject}\nMessage:\n{$message}";

    $mail->send();

    echo "<script>alert('Message sent successfully.'); window.location.href='contact.php';</script>";
} catch (Exception $e) {
    error_log('Contact form mail failed: ' . $mail->ErrorInfo);
    echo "<script>alert('Mail sending failed. Please try again later.'); window.history.back();</script>";
}
