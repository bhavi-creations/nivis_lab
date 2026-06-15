<?php
require_once __DIR__ . '/razorpay_config.php';

header('Content-Type: application/json');

function jsonResponse($payload, $status = 200)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function postJson(string $url, array $payload): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$orderId = trim((string) ($input['order_id'] ?? ''));
$paymentId = trim((string) ($input['razorpay_payment_id'] ?? ''));
$gatewayOrderId = trim((string) ($input['razorpay_order_id'] ?? ''));
$signature = trim((string) ($input['razorpay_signature'] ?? ''));

if (!$orderId || !$paymentId || !$gatewayOrderId || !$signature) {
    jsonResponse(['success' => false, 'message' => 'Missing payment verification fields.'], 400);
}

$baseUrl = rtrim(EVERSHOP_API_BASE_URL, '/');
$verifyResult = postJson($baseUrl . '/api/razorpay/verify', [
    'order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_order_id' => $gatewayOrderId,
    'razorpay_signature' => $signature
]);
$verifyDecoded = json_decode((string) $verifyResult['response'], true);

if ($verifyResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop verification service: ' . $verifyResult['error']
    ], 500);
}

if ($verifyResult['http_code'] < 200 || $verifyResult['http_code'] >= 300 || !empty($verifyDecoded['error'])) {
    jsonResponse([
        'success' => false,
        'message' => $verifyDecoded['error']['message'] ?? 'Payment verification failed.',
        'error' => $verifyDecoded
    ], 500);
}

jsonResponse([
    'success' => true,
    'message' => 'Payment verified successfully.',
    'order_id' => $orderId,
    'payment_id' => $paymentId
]);
?>
