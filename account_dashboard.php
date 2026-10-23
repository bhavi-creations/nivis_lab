<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');
function account_dashboard_json(array $payload, int $status = 200): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
if (($_SESSION['auth_backend'] ?? '') !== 'evershop' || empty($_SESSION['backend_access_token'])) { account_dashboard_json(['authenticated'=>false]); }
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
try {
    $profile = customer_backend_profile(true);
    if (!$profile) { account_dashboard_json(['authenticated'=>false]); }
    $addresses = $profile['addresses'] ?? [];
    $address = $addresses[0] ?? [];
    foreach ($addresses as $entry) if (!empty($entry['isDefault'])) { $address=$entry; break; }
    $orders = customer_backend_saved_orders($profile);
    account_dashboard_json(['authenticated'=>true,'csrf'=>$_SESSION['csrf'],'customer'=>['name'=>$profile['fullName'],'email'=>$profile['email'],'phone'=>$address['telephone'] ?? '', 'address'=>implode(', ',array_filter([$address['address1']??'', $address['address2']??'', $address['city']??'', $address['province']['name']??'', $address['postcode']??'', $address['country']['name']??''])), 'address_1'=>$address['address1']??'', 'address_2'=>$address['address2']??'', 'city'=>$address['city']??'', 'state'=>$address['province']['name']??'', 'state_code'=>$address['province']['code']??'', 'postcode'=>$address['postcode']??'', 'country'=>$address['country']['code']??'IN'], 'orders'=>$orders]);
} catch (CustomerBackendException $error) {
    if ($error->getCode() === 401) { account_dashboard_json(['authenticated'=>false]); }
    account_dashboard_json(['error'=>$error->getMessage()], 503);
} catch (Throwable $error) {
    error_log('Account dashboard failed: ' . $error->getMessage());
    account_dashboard_json(['error'=>'Unable to load your account dashboard. Check the PHP/server error log.'], 503);
}
