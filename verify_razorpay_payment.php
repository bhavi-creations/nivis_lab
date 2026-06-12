<?php
require_once __DIR__ . '/razorpay_config.php';

header('Content-Type: application/json');

function jsonResponse($payload, $status = 200)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
}

if (!RAZORPAY_KEY_SECRET) {
    jsonResponse(['success' => false, 'message' => 'Razorpay secret is not configured.'], 500);
}

$input = json_decode(file_get_contents('php://input'), true);
$orderId = trim($input['razorpay_order_id'] ?? '');
$paymentId = trim($input['razorpay_payment_id'] ?? '');
$signature = trim($input['razorpay_signature'] ?? '');

if (!$orderId || !$paymentId || !$signature) {
    jsonResponse(['success' => false, 'message' => 'Missing payment verification fields.'], 400);
}

$expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);

if (!hash_equals($expectedSignature, $signature)) {
    jsonResponse(['success' => false, 'message' => 'Payment signature verification failed.'], 400);
}

jsonResponse([
    'success' => true,
    'message' => 'Payment verified successfully.',
    'payment_id' => $paymentId,
    'order_id' => $orderId
]);
?>
