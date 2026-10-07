<?php
// Server operator retry for a persisted, already verified payment only.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/shiprocket_service.php';
$orderId = trim($argv[1] ?? '');
if ($orderId === '') { fwrite(STDERR, "Usage: php tools/retry_shiprocket.php <EverShop order UUID>\n"); exit(1); }
try {
    $result = shiprocketFulfill($orderId);
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
    exit(empty($result['success']) ? 1 : 0);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
