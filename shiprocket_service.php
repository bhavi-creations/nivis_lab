<?php
require_once __DIR__ . '/shiprocket_config.php';

// Records exit when accessed over HTTP, and are locked across concurrent requests.
function shiprocketRecord(string $id, callable $callback)
{
    if (!is_dir(SHIPROCKET_STORAGE_DIR) && !mkdir(SHIPROCKET_STORAGE_DIR, 0700, true) && !is_dir(SHIPROCKET_STORAGE_DIR)) {
        throw new RuntimeException('Shipping storage is unavailable.');
    }
    $handle = fopen(SHIPROCKET_STORAGE_DIR . '/' . hash('sha256', $id) . '.php', 'c+');
    if (!$handle || !flock($handle, LOCK_EX)) {
        throw new RuntimeException('Shipping storage cannot be locked.');
    }
    try {
        $raw = stream_get_contents($handle);
        $record = $raw === '' ? [] : json_decode(substr($raw, strlen("<?php exit; ?>\n")), true);
        if (!is_array($record)) {
            throw new RuntimeException('Invalid shipping record.');
        }
        $save = static function (array $value) use ($handle): void {
            $data = "<?php exit; ?>\n" . json_encode($value, JSON_THROW_ON_ERROR);
            rewind($handle);
            if (!ftruncate($handle, 0) || fwrite($handle, $data) !== strlen($data) || !fflush($handle)) {
                throw new RuntimeException('Shipping record could not be saved.');
            }
        };
        return $callback($record, $save);
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function shiprocketPayload(array $order, array $billing, array $shipping): array
{
    if (strtoupper($order['currency'] ?? 'INR') !== 'INR') {
        throw new RuntimeException('Shiprocket checkout requires INR.');
    }
    $payload = [
        'order_id' => (string) $order['uuid'],
        'order_date' => (new DateTimeImmutable('now', new DateTimeZone('Asia/Kolkata')))->format('Y-m-d H:i'),
        'pickup_location' => SHIPROCKET_PICKUP_LOCATION,
        'shipping_is_billing' => false, 'payment_method' => 'Prepaid',
        'order_items' => [], 'sub_total' => 0,
        'shipping_charges' => (float) ($order['shipping_fee_incl_tax'] ?? $order['shipping_fee_excl_tax'] ?? 0),
        'length' => SHIPROCKET_PACKAGE_LENGTH, 'breadth' => SHIPROCKET_PACKAGE_BREADTH,
        'height' => SHIPROCKET_PACKAGE_HEIGHT, 'weight' => SHIPROCKET_PACKAGE_WEIGHT
    ];
    foreach (['billing' => $billing, 'shipping' => $shipping] as $prefix => $address) {
        if (strtoupper($address['country'] ?? '') !== 'IN') {
            throw new RuntimeException('Shipping is currently available within India only.');
        }
        $name = preg_split('/\s+/', trim($address['full_name'] ?? ''), 2);
        $phone = preg_replace('/\D/', '', $address['telephone'] ?? '');
        if (strlen($phone) === 12 && substr($phone, 0, 2) === '91') {
            $phone = substr($phone, 2);
        }
        if (empty($name[0]) || !preg_match('/^[6-9][0-9]{9}$/', $phone)
            || !preg_match('/^[1-9][0-9]{5}$/', $address['postcode'] ?? '')
            || !filter_var($address['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Enter a valid name, email, Indian mobile number and PIN code.');
        }
        $payload[$prefix . '_customer_name'] = $name[0];
        $payload[$prefix . '_last_name'] = $name[1] ?? '';
        foreach (['address' => 'address_1', 'address_2' => 'address_2', 'city' => 'city', 'state' => 'province', 'pincode' => 'postcode', 'email' => 'email'] as $field => $key) {
            $payload[$prefix . '_' . $field] = trim((string) ($address[$key] ?? ''));
            if ($field !== 'address_2' && $payload[$prefix . '_' . $field] === '') {
                throw new RuntimeException('Complete your billing and shipping addresses.');
            }
        }
        $payload[$prefix . '_phone'] = $phone;
        $payload[$prefix . '_country'] = 'India';
    }
    foreach ($order['items'] ?? [] as $item) {
        $price = $item['final_price_incl_tax'] ?? $item['product_price_incl_tax'] ?? null;
        $qty = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT);
        if (!$qty || $qty < 1 || !is_numeric($price) || $price < 0 || empty($item['product_sku']) || empty($item['product_name'])) {
            throw new RuntimeException('Backend order has incomplete shipping items.');
        }
        $payload['order_items'][] = ['name' => $item['product_name'], 'sku' => $item['product_sku'], 'units' => $qty, 'selling_price' => (float) $price];
        $payload['sub_total'] += round($price * $qty, 2);
    }
    if ($payload['order_items'] === []) {
        throw new RuntimeException('Backend order did not return shipping items.');
    }
    $payload['sub_total'] = round($payload['sub_total'], 2);
    $total = $order['grand_total'] ?? null;
    $discount = round($payload['sub_total'] + $payload['shipping_charges'] - (float) $total, 2);
    if (!is_numeric($total) || $total <= 0 || $discount < -0.01 || $discount > $payload['sub_total']) {
        throw new RuntimeException('Backend order totals cannot be reconciled for shipping.');
    }
    $payload['total_discount'] = max(0, $discount);
    foreach (['length', 'breadth', 'height', 'weight'] as $dimension) {
        if ($payload[$dimension] <= 0) {
            throw new RuntimeException('Configure valid parcel dimensions and weight.');
        }
    }
    return $payload;
}

function shiprocketFulfill(string $orderId): array
{
    return shiprocketRecord($orderId, static function (array $record, callable $save): array {
        if (empty($record['verified']) || empty($record['payload'])) {
            throw new RuntimeException('A verified checkout order is required.');
        }
        if (!empty($record['result'])) {
            return $record['result'];
        }
        // A lost response may still have created an order. Do not blindly resubmit.
        if (in_array($record['status'] ?? '', ['submitting', 'review_required'], true)) {
            return ['success' => false, 'status' => 'review_required'];
        }
        $token = getShiprocketToken();
        if (!$token) {
            $record['status'] = 'pending';
            $save($record);
            return ['success' => false, 'status' => 'pending'];
        }
        $record['status'] = 'submitting';
        $save($record);
        $response = shiprocketRequest('orders/create/adhoc', $record['payload'], $token);
        $data = $response['data'];
        if ($response['status'] >= 200 && $response['status'] < 300 && !empty($data['order_id']) && !empty($data['shipment_id'])) {
            $record['result'] = ['success' => true, 'status' => 'created', 'shiprocket_order_id' => $data['order_id'], 'shipment_id' => $data['shipment_id']];
            $record['status'] = 'created';
        } else {
            $record['status'] = in_array($response['status'], [400, 401, 403, 422, 429], true) ? 'pending' : 'review_required';
            $record['last_http_status'] = $response['status'];
        }
        $save($record);
        return $record['result'] ?? ['success' => false, 'status' => $record['status']];
    });
}
