<?php
// Read-only account check: does not create orders, labels or pickups.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/shiprocket_config.php';
require_once dirname(__DIR__) . '/shiprocket_storage.php';
try {
    echo 'Shipping storage: OK (' . shiprocketStorageDirectory() . ")\n";
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
$auth = shiprocketRequest('auth/login', ['email' => SHIPROCKET_API_EMAIL, 'password' => SHIPROCKET_API_PASSWORD]);
$token = $auth['data']['token'] ?? null;
if (!$token) {
    fwrite(STDERR, 'Shiprocket authentication failed: HTTP ' . $auth['status'] . '. ' . $auth['error'] . "\n");
    exit(1);
}
echo "Shiprocket authentication: OK\n";
$ch = curl_init('https://apiv2.shiprocket.in/v1/external/settings/company/pickup');
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token], CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 30]);
$body = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$data = json_decode((string) $body, true);
if ($status !== 200) { fwrite(STDERR, "Pickup check failed: HTTP $status\n"); exit(1); }
$locations = array_column($data['data']['shipping_address'] ?? [], 'pickup_location');
echo 'Configured pickup: ' . SHIPROCKET_PICKUP_LOCATION . "\n";
echo 'Available pickup names: ' . json_encode($locations) . "\n";
if (!in_array(SHIPROCKET_PICKUP_LOCATION, $locations, true)) { fwrite(STDERR, "Set SHIPROCKET_PICKUP_LOCATION to an exact available name.\n"); exit(1); }
echo "Pickup location: OK\n";
