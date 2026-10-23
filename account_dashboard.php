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
    $province = $address['province'] ?? $address['state'] ?? [];
    $stateName = is_array($province) ? ($province['name'] ?? $province['code'] ?? '') : (string)$province;
    $stateCode = is_array($province) ? ($province['code'] ?? '') : '';
    $country = $address['country'] ?? [];
    $countryName = is_array($country) ? ($country['name'] ?? '') : (string)$country;
    $countryCode = is_array($country) ? ($country['code'] ?? 'IN') : 'IN';
    $phone = $address['telephone'] ?? $address['phone'] ?? '';
    $address1 = $address['address1'] ?? $address['addressLine1'] ?? '';
    $address2 = $address['address2'] ?? $address['addressLine2'] ?? '';
    $orders = customer_backend_saved_orders($profile);
    account_dashboard_json(['authenticated'=>true,'csrf'=>$_SESSION['csrf'],'customer'=>['name'=>$profile['fullName'],'email'=>$profile['email'],'phone'=>$phone, 'address'=>implode(', ',array_filter([$address1, $address2, $address['city']??'', $stateName, $address['postcode']??'', $countryName])), 'address_1'=>$address1, 'address_2'=>$address2, 'city'=>$address['city']??'', 'state'=>$stateName, 'state_code'=>$stateCode, 'postcode'=>$address['postcode']??'', 'country'=>$countryCode], 'orders'=>$orders]);
} catch (CustomerBackendException $error) {
    if ($error->getCode() === 401) { account_dashboard_json(['authenticated'=>false]); }
    account_dashboard_json(['error'=>$error->getMessage()], 503);
} catch (Throwable $error) {
    error_log('Account dashboard failed: ' . $error->getMessage());
    account_dashboard_json(['error'=>'Unable to load your account dashboard. Check the PHP/server error log.'], 503);
}
