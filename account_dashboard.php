<?php
require_once __DIR__ . '/customer_auth.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');
if (empty($_SESSION['customer_id'])) { echo json_encode(['authenticated' => false]); exit; }
try {
    $db = customer_db();
    $query = $db->prepare('SELECT name,email,phone,address FROM website_customers WHERE id=?');
    $query->execute([$_SESSION['customer_id']]);
    $customer = $query->fetch();
    if (!$customer) { echo json_encode(['authenticated' => false]); exit; }
    $query = $db->prepare('SELECT order_id,items_json,amount_subunits,currency,payment_status,created_at FROM website_customer_orders WHERE customer_id=? ORDER BY id DESC');
    $query->execute([$_SESSION['customer_id']]);
    $orders = $query->fetchAll();
    foreach ($orders as &$order) { $order['items'] = json_decode($order['items_json'], true) ?: []; unset($order['items_json']); }
    echo json_encode(['authenticated' => true, 'customer' => $customer, 'orders' => $orders]);
} catch (Throwable $error) {
    error_log('Account dashboard: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['error' => 'Account details are temporarily unavailable. Please try again.']);
}
