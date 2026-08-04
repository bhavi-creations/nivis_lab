<?php
require_once __DIR__ . '/razorpay_config.php';

header('Content-Type: application/json');

function jsonResponse($payload, $status = 200)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function postJson(string $url, array $payload, array $headers = []): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => array_merge(['Content-Type: application/json'], $headers),
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

if (RAZORPAY_KEY_SECRET === '') {
    jsonResponse([
        'success' => false,
        'message' => 'Missing Razorpay secret key.'
    ], 500);
}

// Step 1: Verify Razorpay signature locally
$expectedSignature = hash_hmac('sha256', $gatewayOrderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
if (!hash_equals($expectedSignature, $signature)) {
    jsonResponse([
        'success' => false,
        'message' => 'Payment signature verification failed.'
    ], 400);
}

// Step 2: Try to update via Evershop API first
$evershopApiBaseUrl = rtrim(getenv('EVERSHOP_API_BASE_URL') ?: EVERSHOP_API_BASE_URL, '/');
$verifyResult = postJson($evershopApiBaseUrl . '/razorpay/verify', [
    'order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_order_id' => $gatewayOrderId,
    'razorpay_signature' => $signature
], [
    'X-Razorpay-Sync-Token: ' . RAZORPAY_SYNC_TOKEN
]);

$apiSuccess = false;
if (!$verifyResult['error'] && $verifyResult['http_code'] >= 200 && $verifyResult['http_code'] < 300) {
    $verifyData = json_decode((string) $verifyResult['response'], true);
    if (isset($verifyData['data']) || (isset($verifyData['success']) && $verifyData['success'])) {
        $apiSuccess = true;
    }
}

if (!$apiSuccess) {
    if ($verifyResult['error']) {
        error_log('Evershop Razorpay verify API connection error: ' . $verifyResult['error']);
    } else {
        $errorData = json_decode((string) $verifyResult['response'], true);
        error_log('Evershop Razorpay verify failed: ' . ($errorData['error']['message'] ?? 'Unknown error'));
    }

    jsonResponse([
        'success' => false,
        'message' => 'Payment verification failed in Evershop.',
        'order_id' => $orderId,
        'gateway_order_id' => $gatewayOrderId,
        'payment_id' => $paymentId,
        'api_updated' => false
    ], 502);
}

jsonResponse([
    'success' => true,
    'message' => 'Payment verified successfully.',
    'order_id' => $orderId,
    'gateway_order_id' => $gatewayOrderId,
    'payment_id' => $paymentId,
    'api_updated' => $apiSuccess
]);
?>
