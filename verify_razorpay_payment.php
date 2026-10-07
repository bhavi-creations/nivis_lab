<?php
require_once __DIR__ . '/razorpay_config.php';
require_once __DIR__ . '/shiprocket_service.php';
session_start();

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
if (!is_array($input)) {
    jsonResponse(['success' => false, 'message' => 'A JSON object is required.'], 400);
}
$orderId = trim((string) ($input['order_id'] ?? ''));
$paymentId = trim((string) ($input['razorpay_payment_id'] ?? ''));
$gatewayOrderId = trim((string) ($input['razorpay_order_id'] ?? ''));
$signature = trim((string) ($input['razorpay_signature'] ?? ''));

if (!$orderId || !$paymentId || !$gatewayOrderId || !$signature) {
    jsonResponse(['success' => false, 'message' => 'Missing payment verification fields.'], 400);
}

// Verify through Evershop so the backend uses the Razorpay settings record.
if (($_SESSION['shiprocket_orders'][$orderId] ?? null) !== $gatewayOrderId) {
    jsonResponse(['success' => false, 'message' => 'Payment does not belong to this checkout session.'], 403);
}
session_write_close();
$evershopApiBaseUrl = rtrim(getenv('EVERSHOP_API_BASE_URL') ?: EVERSHOP_API_BASE_URL, '/');
$verifyResult = postJson($evershopApiBaseUrl . '/razorpay/verify', [
    'order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_order_id' => $gatewayOrderId,
    'razorpay_signature' => $signature
]);

$apiSuccess = false;
if (!$verifyResult['error'] && $verifyResult['http_code'] >= 200 && $verifyResult['http_code'] < 300) {
    $verifyData = json_decode((string) $verifyResult['response'], true);
    if (empty($verifyData['error']) && ($verifyData['success'] ?? true) !== false
        && ($verifyData['data']['success'] ?? true) !== false
        && (isset($verifyData['data']) || ($verifyData['success'] ?? false) === true)) {
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

$shipping = ['success' => false, 'status' => 'pending'];
try {
    shiprocketRecord($orderId, static function (array $record, callable $save) use ($gatewayOrderId, $paymentId): void {
        if (($record['gateway_order_id'] ?? '') !== $gatewayOrderId) {
            throw new RuntimeException('Shipping payment reference mismatch.');
        }
        $record['verified'] = true;
        $record['payment_id'] = $paymentId;
        $save($record);
    });
    $shipping = shiprocketFulfill($orderId);
} catch (Throwable $e) {
    error_log('Shiprocket fulfillment pending for order ' . $orderId);
}

jsonResponse([
    'success' => true,
    'message' => 'Payment verified successfully.',
    'order_id' => $orderId,
    'gateway_order_id' => $gatewayOrderId,
    'payment_id' => $paymentId,
    'api_updated' => $apiSuccess,
    'shipping' => $shipping,
    'shipping_warning' => empty($shipping['success'])
        ? 'Payment received. Shipping confirmation is pending. Please contact support with your order ID; do not pay again.'
        : null
]);
?>
