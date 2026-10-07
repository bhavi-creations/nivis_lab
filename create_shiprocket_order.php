<?php
require_once __DIR__ . '/shiprocket_service.php';
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['success' => false, 'message' => 'POST is required.']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
$orderId = is_array($input) ? (string) ($input['order_id'] ?? '') : '';
if ($orderId === '' || empty($_SESSION['shiprocket_orders'][$orderId])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'This checkout session does not own the order.']);
    exit;
}
session_write_close();
try {
    echo json_encode(shiprocketFulfill($orderId));
} catch (Throwable $e) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Shipping is pending. Contact support with your order ID.']);
}
