<?php
// Server environment overrides the untracked local credentials file.
foreach (['EMAIL', 'PASSWORD'] as $key) {
    $value = getenv('SHIPROCKET_API_' . $key);
    if ($value !== false && $value !== '') {
        define('SHIPROCKET_API_' . $key, $value);
    }
}
if (is_file(__DIR__ . '/shiprocket_config.local.php')) {
    require_once __DIR__ . '/shiprocket_config.local.php';
}
foreach (['EMAIL', 'PASSWORD'] as $key) {
    if (!defined('SHIPROCKET_API_' . $key)) {
        define('SHIPROCKET_API_' . $key, '');
    }
}
if (getenv('SHIPROCKET_PICKUP_LOCATION')) {
    // Environment can override the locally configured warehouse.
    $shiprocketPickup = getenv('SHIPROCKET_PICKUP_LOCATION');
} else {
    $shiprocketPickup = defined('SHIPROCKET_LOCAL_PICKUP_LOCATION') ? SHIPROCKET_LOCAL_PICKUP_LOCATION : 'Primary';
}
define('SHIPROCKET_PICKUP_LOCATION', $shiprocketPickup);
define('SHIPROCKET_STORAGE_DIR', getenv('SHIPROCKET_STORAGE_DIR')
    ?: (defined('SHIPROCKET_LOCAL_STORAGE_DIR') ? SHIPROCKET_LOCAL_STORAGE_DIR : ''));
foreach (['LENGTH' => 10, 'BREADTH' => 10, 'HEIGHT' => 5, 'WEIGHT' => 0.5] as $key => $default) {
    define('SHIPROCKET_PACKAGE_' . $key, (float) (getenv('SHIPROCKET_PACKAGE_' . $key) ?: $default));
}

function shiprocketRequest(string $path, array $payload, string $token = ''): array
{
    $ch = curl_init('https://apiv2.shiprocket.in/v1/external/' . $path);
    $headers = ['Content-Type: application/json'];
    if ($token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload), CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 30
    ]);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    return ['status' => $status, 'data' => json_decode((string) $body, true), 'error' => $error];
}

function getShiprocketToken(): ?string
{
    if (SHIPROCKET_API_EMAIL === '' || SHIPROCKET_API_PASSWORD === '') {
        return null;
    }
    $result = shiprocketRequest('auth/login', [
        'email' => SHIPROCKET_API_EMAIL, 'password' => SHIPROCKET_API_PASSWORD
    ]);
    return $result['status'] === 200 ? ($result['data']['token'] ?? null) : null;
}
