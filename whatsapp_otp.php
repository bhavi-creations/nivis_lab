<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function otp_json_error(string $message, int $status = 400): never {
    http_response_code($status);
    echo json_encode(['error' => $message], JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') otp_json_error('Method not allowed.', 405);
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? '';
if ($origin !== '') {
    $originHost = parse_url($origin, PHP_URL_HOST);
    $originPort = parse_url($origin, PHP_URL_PORT);
    $requestHost = strtolower(preg_replace('/:\d+$/', '', $host));
    $requestPort = preg_match('/:(\d+)$/', $host, $portMatch) ? (int)$portMatch[1] : null;
    if (!is_string($originHost) || strtolower($originHost) !== $requestHost || $originPort !== $requestPort) otp_json_error('Request origin could not be verified.', 403);
}
$input = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($input)) otp_json_error('Invalid request.');
if (empty($_SESSION['csrf']) || !isset($input['csrf']) || !is_string($input['csrf']) || !hash_equals($_SESSION['csrf'], $input['csrf'])) otp_json_error('Your session expired. Refresh the page and try again.', 403);

$action = $input['action'] ?? '';
$phone = trim((string)($input['phone'] ?? ''));
if (!preg_match('/^\+[1-9]\d{7,14}$/D', $phone)) otp_json_error('Enter the phone number with its country code, for example +919876543210.');
try {
    if ($action === 'request') {
        $purpose = ($input['purpose'] ?? '') === 'registration' ? 'registration' : 'signin';
        if ($purpose === 'registration') {
            $name = trim((string)($input['registration']['fullName'] ?? ''));
            $email = trim((string)($input['registration']['email'] ?? ''));
            if ($name === '' || strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) otp_json_error('Enter your name and a valid email before requesting a code.');
        }
        $result = customer_backend_otp('request', ['phone' => $phone, 'purpose' => $purpose]);
        $_SESSION['auth_challenge_id'] = (string)($result['challengeId'] ?? '');
        $_SESSION['auth_challenge_purpose'] = $purpose;
        $_SESSION['auth_resend_at_ms'] = (int)floor(microtime(true) * 1000) + max(1, (int)($result['resendAfter'] ?? 60)) * 1000;
        $_SESSION['auth_resend_phone_hash'] = hash('sha256', $phone);
        echo json_encode(['data' => $result], JSON_UNESCAPED_SLASHES);
        exit;
    }
    if ($action === 'verify') {
        $purpose = ($input['purpose'] ?? '') === 'registration' ? 'registration' : 'signin';
        $payload = ['phone' => $phone, 'code' => trim((string)($input['code'] ?? '')), 'challengeId' => (string)($input['challengeId'] ?? '')];
        if ($purpose === 'registration') {
            $payload['registration'] = [
                'fullName' => trim((string)($input['registration']['fullName'] ?? '')),
                'email' => strtolower(trim((string)($input['registration']['email'] ?? '')))
            ];
        }
        $result = customer_backend_otp('verify', $payload);
        echo json_encode(['data' => $result], JSON_UNESCAPED_SLASHES);
        exit;
    }
    otp_json_error('Choose a valid verification action.');
} catch (CustomerBackendException $error) {
    otp_json_error($error->getMessage(), $error->getCode() >= 400 && $error->getCode() <= 599 ? $error->getCode() : 502);
} catch (Throwable $error) {
    error_log('[GI Shop] WhatsApp OTP proxy failed: ' . $error->getMessage());
    otp_json_error('We could not complete verification. Please try again.', 502);
}
