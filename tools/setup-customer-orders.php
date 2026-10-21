<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../customer_auth.php';
customer_db()->exec("CREATE TABLE IF NOT EXISTS website_customer_orders (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 customer_id BIGINT UNSIGNED NOT NULL,
 order_id VARCHAR(100) NOT NULL UNIQUE,
 gateway_order_id VARCHAR(100) NOT NULL,
 items_json LONGTEXT NOT NULL,
 amount_subunits BIGINT UNSIGNED NOT NULL,
 currency VARCHAR(8) NOT NULL DEFAULT 'INR',
 payment_status VARCHAR(24) NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX customer_orders (customer_id, created_at),
 FOREIGN KEY (customer_id) REFERENCES website_customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "Order history table ready.\n";
