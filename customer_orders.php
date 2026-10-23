<?php
require_once __DIR__ . '/customer_auth.php';
function customer_order_save($orderId, $gatewayId, $items, $amount, $currency) {
    if (empty($_SESSION['customer_id'])) { return; }
    $snapshot = [];
    foreach ($items as $item) {
        if (!is_array($item)) { continue; }
        $snapshot[] = ['name' => substr((string)($item['name'] ?? $item['product_name'] ?? $item['sku'] ?? 'Product'), 0, 250), 'quantity' => max(1, (int)($item['qty'] ?? $item['quantity'] ?? 1))];
    }
    customer_db()->prepare('INSERT INTO website_customer_orders (customer_id,order_id,gateway_order_id,items_json,amount_subunits,currency) VALUES (?,?,?,?,?,?)')->execute([$_SESSION['customer_id'], $orderId, $gatewayId, json_encode($snapshot), (int)$amount, $currency]);
}
function customer_order_paid($orderId, $gatewayId) {
    customer_db()->prepare("UPDATE website_customer_orders SET payment_status='paid' WHERE order_id=? AND gateway_order_id=?")->execute([$orderId, $gatewayId]);
}
