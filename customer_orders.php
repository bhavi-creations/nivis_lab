<?php
require_once __DIR__ . '/customer_auth.php';
function customer_orders_ensure_table(): void {
    static $ready = false;
    if ($ready) return;
    customer_db()->exec("CREATE TABLE IF NOT EXISTS website_customer_orders (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        customer_id VARCHAR(191) NOT NULL,
        order_id VARCHAR(100) NOT NULL,
        gateway_order_id VARCHAR(100) NOT NULL,
        items_json LONGTEXT NOT NULL,
        amount_subunits BIGINT NOT NULL DEFAULT 0,
        currency VARCHAR(8) NOT NULL DEFAULT 'INR',
        payment_status VARCHAR(24) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_customer_gateway_order (customer_id, order_id, gateway_order_id),
        KEY idx_customer_order_status (customer_id, payment_status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $column = customer_db()->query("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='website_customer_orders' AND COLUMN_NAME='customer_id'")->fetchColumn();
    if ($column && !in_array(strtolower((string)$column), ['varchar','char'], true)) {
        customer_db()->exec('ALTER TABLE website_customer_orders MODIFY customer_id VARCHAR(191) NOT NULL');
    }
    $ready = true;
}
function customer_order_save($orderId, $gatewayId, $items, $amount, $currency, $customerId = null) {
    $customerId = $customerId ?: ($_SESSION['customer_id'] ?? null);
    if (!$customerId) { return; }
    customer_orders_ensure_table();
    $snapshot = [];
    foreach ($items as $item) {
        if (!is_array($item)) { continue; }
        $snapshot[] = ['name' => substr((string)($item['name'] ?? $item['product_name'] ?? $item['sku'] ?? 'Product'), 0, 250), 'quantity' => max(1, (int)($item['qty'] ?? $item['quantity'] ?? 1))];
    }
    customer_db()->prepare('INSERT INTO website_customer_orders (customer_id,order_id,gateway_order_id,items_json,amount_subunits,currency) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE items_json=VALUES(items_json),amount_subunits=VALUES(amount_subunits),currency=VALUES(currency)')->execute([$customerId, $orderId, $gatewayId, json_encode($snapshot), (int)$amount, $currency]);
}
function customer_order_paid($orderId, $gatewayId, $customerId = null) {
    customer_orders_ensure_table();
    $sql = "UPDATE website_customer_orders SET payment_status='paid' WHERE order_id=? AND gateway_order_id=?";
    $params = [$orderId, $gatewayId];
    if ($customerId) { $sql .= ' AND customer_id=?'; $params[] = $customerId; }
    customer_db()->prepare($sql)->execute($params);
}
function customer_orders_for_customer(string $customerId): array {
    customer_orders_ensure_table();
    $statement = customer_db()->prepare("SELECT order_id,gateway_order_id,items_json,amount_subunits,currency,payment_status,created_at FROM website_customer_orders WHERE customer_id=? ORDER BY created_at DESC");
    $statement->execute([$customerId]);
    return $statement->fetchAll();
}
