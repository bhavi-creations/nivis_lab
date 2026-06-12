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

if (!RAZORPAY_KEY_ID || !RAZORPAY_KEY_SECRET) {
    jsonResponse([
        'success' => false,
        'message' => 'Razorpay keys are not configured.'
    ], 500);
}

$input = json_decode(file_get_contents('php://input'), true);
$items = is_array($input['items'] ?? null) ? $input['items'] : [];

$total = 0;
foreach ($items as $item) {
    $price = (float) ($item['price'] ?? 0);
    $quantity = max(1, (int) ($item['quantity'] ?? 1));
    $total += $price * $quantity;
}

if ($total <= 0) {
    jsonResponse(['success' => false, 'message' => 'Cart total is empty.'], 400);
}

$amountPaise = (int) round($total * 100);
$payload = [
    'amount' => $amountPaise,
    'currency' => RAZORPAY_CURRENCY,
    'receipt' => 'nivis_' . time() . '_' . random_int(1000, 9999),
    'notes' => [
        'site' => RAZORPAY_COMPANY_NAME,
        'items_count' => count($items)
    ]
];

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_USERPWD => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
    CURLOPT_TIMEOUT => 20
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    jsonResponse(['success' => false, 'message' => 'Unable to connect Razorpay: ' . $error], 500);
}

$decoded = json_decode($response, true);
if ($httpCode < 200 || $httpCode >= 300 || !is_array($decoded) || empty($decoded['id'])) {
    jsonResponse([
        'success' => false,
        'message' => 'Razorpay order creation failed.',
        'error' => $decoded
    ], 500);
}

jsonResponse([
    'success' => true,
    'key' => RAZORPAY_KEY_ID,
    'order' => $decoded,
    'company' => RAZORPAY_COMPANY_NAME
]);
?>
