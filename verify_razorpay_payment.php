<?php
require_once __DIR__ . '/razorpay_config.php';
require_once __DIR__ . '/config.php';

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

function getDbConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        return null;
    }
    return $conn;
}

function updateOrderPaymentStatus($conn, $orderId, $status)
{
    $status = $conn->real_escape_string($status);
    $orderId = $conn->real_escape_string($orderId);
    
    $sql = "UPDATE `order` SET payment_status = '$status' WHERE uuid = '$orderId'";
    return $conn->query($sql);
}

function insertPaymentTransaction($conn, $orderId, $razorpayPaymentId, $amount, $currency, $status, $paymentAction, $additionalInfo)
{
    $orderId = $conn->real_escape_string($orderId);
    $razorpayPaymentId = $conn->real_escape_string($razorpayPaymentId);
    $amount = (float) $amount;
    $currency = $conn->real_escape_string($currency);
    $status = $conn->real_escape_string($status);
    $paymentAction = $conn->real_escape_string($paymentAction);
    $additionalInfo = $conn->real_escape_string($additionalInfo);
    $transactionType = 'online';
    $createdAt = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO payment_transaction 
            (payment_transaction_order_id, transaction_id, amount, currency, status, payment_action, transaction_type, additional_information, created_at) 
            VALUES ('$orderId', '$razorpayPaymentId', $amount, '$currency', '$status', '$paymentAction', '$transactionType', '$additionalInfo', '$createdAt')
            ON DUPLICATE KEY UPDATE 
            status = '$status', 
            payment_action = '$paymentAction',
            additional_information = '$additionalInfo'";
    
    return $conn->query($sql);
}

function addOrderActivity($conn, $orderId, $comment)
{
    $orderId = $conn->real_escape_string($orderId);
    $comment = $conn->real_escape_string($comment);
    $createdAt = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO order_activity (order_id, comment, created_at) VALUES ('$orderId', '$comment', '$createdAt')";
    return $conn->query($sql);
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
    
    // Step 3: Fallback - Update database directly if Evershop API fails
    $conn = getDbConnection();
    if ($conn) {
        // Determine payment status based on payment mode
        $paymentMode = getenv('RAZORPAY_PAYMENT_MODE') ?: 'capture';
        $paymentStatus = $paymentMode === 'capture' ? 'razorpay_captured' : 'razorpay_authorized';
        $transactionStatus = $paymentMode === 'capture' ? 'captured' : 'authorized';
        $paymentAction = $paymentMode === 'capture' ? 'capture' : 'authorize';
        
        // Update order payment status
        $statusUpdated = updateOrderPaymentStatus($conn, $orderId, $paymentStatus);
        
        if ($statusUpdated) {
            // Insert/update payment transaction
            $additionalInfo = json_encode([
                'razorpay_order_id' => $gatewayOrderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ]);
            
            insertPaymentTransaction(
                $conn, 
                $orderId, 
                $paymentId, 
                0, // Amount will be updated from order table if needed
                'INR', 
                $transactionStatus, 
                $paymentAction, 
                $additionalInfo
            );
            
            // Add order activity log
            $activityComment = "Payment $transactionStatus using Razorpay. Transaction ID: $paymentId";
            addOrderActivity($conn, $orderId, $activityComment);
            
            // Emit order_placed event (trigger any post-order processing)
            // This can be extended to trigger email notifications, inventory updates, etc.
            if (function_exists('emitOrderPlacedEvent')) {
                emitOrderPlacedEvent($orderId);
            }
            
            error_log("Payment verified and updated directly for order: $orderId");
        } else {
            error_log("Failed to update order status for order: $orderId");
        }
        
        $conn->close();
    } else {
        error_log("Database connection failed. Unable to update order status for order: $orderId");
    }
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