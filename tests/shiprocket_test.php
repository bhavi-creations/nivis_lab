<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
putenv('SHIPROCKET_STORAGE_DIR=' . sys_get_temp_dir() . '/nivis-shiprocket-test-' . bin2hex(random_bytes(6)));
require_once dirname(__DIR__) . '/shiprocket_service.php';
function check($condition, string $message): void {
    if (!$condition) { throw new RuntimeException($message); }
}
$address = ['full_name' => 'Test Customer', 'address_1' => '123 Test Road', 'city' => 'Hyderabad', 'province' => 'Telangana', 'postcode' => '500001', 'country' => 'IN', 'email' => 'test@example.com', 'telephone' => '+91 9876543210'];
$order = ['uuid' => 'test-order', 'currency' => 'INR', 'grand_total' => 640, 'shipping_fee_incl_tax' => 40, 'items' => [
    ['product_sku' => 'A', 'product_name' => 'Serum', 'qty' => 2, 'final_price_incl_tax' => 250],
    ['product_sku' => 'B', 'product_name' => 'Cream', 'qty' => 1, 'final_price_incl_tax' => 150]
]];
try {
    $payload = shiprocketPayload($order, $address, $address);
    check(count($payload['order_items']) === 2, 'All cart lines must be sent');
    check($payload['sub_total'] === 650.0 && $payload['total_discount'] === 50.0, 'Totals must reconcile');
    check($payload['billing_phone'] === '9876543210', 'Normalize India calling code');
    check($payload['order_id'] === 'test-order', 'Stable backend order reference');
    foreach (['telephone' => '123', 'postcode' => '000000', 'email' => 'invalid', 'country' => 'US'] as $key => $value) {
        $bad = $address; $bad[$key] = $value;
        $rejected = false;
        try { shiprocketPayload($order, $bad, $address); } catch (RuntimeException $e) { $rejected = true; }
        check($rejected, 'Reject invalid address ' . $key);
    }
    $badOrder = $order; $badOrder['items'] = [];
    $rejected = false;
    try { shiprocketPayload($badOrder, $address, $address); } catch (RuntimeException $e) { $rejected = true; }
    check($rejected, 'Reject missing backend items');
    shiprocketRecord('unpaid', function ($record, $save) use ($payload) { $save(['payload' => $payload, 'verified' => false]); });
    $rejected = false;
    try { shiprocketFulfill('unpaid'); } catch (RuntimeException $e) { $rejected = true; }
    check($rejected, 'Unpaid order must not reach Shiprocket');
    $result = ['success' => true, 'status' => 'created', 'shiprocket_order_id' => 123, 'shipment_id' => 456];
    shiprocketRecord('paid', function ($record, $save) use ($payload, $result) { $save(['payload' => $payload, 'verified' => true, 'result' => $result]); });
    check(shiprocketFulfill('paid') === $result && shiprocketFulfill('paid') === $result, 'Repeat request returns saved shipment');
    shiprocketRecord('uncertain', function ($record, $save) use ($payload) { $save(['payload' => $payload, 'verified' => true, 'status' => 'submitting']); });
    check(shiprocketFulfill('uncertain')['status'] === 'review_required', 'Lost response must not create duplicates');
    echo "PASS: payload, totals, address validation, unpaid rejection, replay and uncertain-response protection\n";
} finally {
    foreach (glob(SHIPROCKET_STORAGE_DIR . '/*.php') ?: [] as $file) { unlink($file); }
    if (is_dir(SHIPROCKET_STORAGE_DIR)) { rmdir(SHIPROCKET_STORAGE_DIR); }
}
