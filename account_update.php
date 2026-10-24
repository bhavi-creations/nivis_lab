<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

function account_update_response(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if (($_SESSION['auth_backend'] ?? '') !== 'evershop' || empty($_SESSION['backend_access_token'])) {
    account_update_response(['error' => 'Please sign in to edit your account.'], 401);
}
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    account_update_response(['error' => 'Invalid request method.'], 405);
}
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)($input['csrf'] ?? ''))) {
    account_update_response(['error' => 'Your session expired. Refresh the page and try again.'], 419);
}
$name = trim((string)($input['full_name'] ?? ''));
$email = strtolower(trim((string)($input['email'] ?? '')));
$phone = trim((string)($input['phone'] ?? ''));
$address1 = trim((string)($input['address_1'] ?? ''));
$address2 = trim((string)($input['address_2'] ?? ''));
$city = trim((string)($input['city'] ?? ''));
$state = trim((string)($input['state'] ?? ''));
$postcode = trim((string)($input['postcode'] ?? ''));
$country = strtoupper(trim((string)($input['country'] ?? '')));
$password = (string)($input['password'] ?? '');
$countries = json_decode(file_get_contents(__DIR__ . '/assets/customer-countries.json'), true)['data']['countries'] ?? [];
$selectedCountry = null;
foreach ($countries as $countryOption) {
    if (($countryOption['code'] ?? '') === $country) {
        $selectedCountry = $countryOption;
        break;
    }
}
$province = null;
$legacyProvinceCodes = ['TS' => 'IN-TG', 'TG' => 'IN-TG', 'CG' => 'IN-CT', 'UK' => 'IN-UT', 'UT' => 'IN-UT', 'GO' => 'IN-GA', 'OD' => 'IN-OR', 'OR' => 'IN-OR'];
$state = $legacyProvinceCodes[strtoupper($state)] ?? $state;
foreach ($selectedCountry['provinces'] ?? [] as $provinceOption) {
    if (strcasecmp($state, (string) ($provinceOption['code'] ?? '')) === 0 || strcasecmp($state, (string) ($provinceOption['name'] ?? '')) === 0) {
        $province = $provinceOption;
        break;
    }
}
if ($province) {
    $state = $province['code'];
}
if ($name === '' || strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || !preg_match('/^\+?[0-9]{10,15}$/D', $phone) || $address1 === '' || strlen($address1) > 255 || strlen($address2) > 255 || $city === '' || strlen($city) > 120 || $state === '' || strlen($state) > 120 || $postcode === '' || strlen($postcode) > 20 || !preg_match('/^[A-Z]{2}$/D', $country) || ($password !== '' && (strlen($password) < 8 || strlen($password) > 128))) {
    account_update_response(['error' => 'Enter valid name, email, phone, shipping address, country and state details. A new password must contain at least 8 characters.'], 422);
}
if (!$province) account_update_response(['error' => 'Enter a valid state or province for the selected country.'], 422);

try {
    $profile = customer_backend_profile();
    if (!$profile || empty($profile['uuid'])) account_update_response(['error' => 'Could not find your customer account. Please sign in again.'], 401);
    $addresses = $profile['addresses'] ?? [];
    $address = $addresses[0] ?? null;
    foreach ($addresses as $candidate) if (!empty($candidate['isDefault'])) { $address = $candidate; break; }

    $customerChanges = ['full_name' => $name, 'email' => $email];
    if ($password !== '') $customerChanges['password'] = $password;
    customer_backend_request_method('PATCH', '/customers/' . rawurlencode($profile['uuid']), $customerChanges);
    $addressPayload = [
        'full_name' => $name, 'telephone' => $phone, 'address_1' => $address1,
        'address_2' => $address2, 'city' => $city, 'province' => $state,
        'country' => $country, 'postcode' => $postcode, 'is_default' => 1
    ];
    if ($address && !empty($address['uuid'])) {
        customer_backend_request_method('PATCH', '/customers/' . rawurlencode($profile['uuid']) . '/addresses/' . rawurlencode($address['uuid']), $addressPayload);
    } else {
        customer_backend_request_method('POST', '/customers/' . rawurlencode($profile['uuid']) . '/addresses', $addressPayload);
    }
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    account_update_response(['success' => true, 'message' => 'Your account details have been updated.']);
} catch (CustomerBackendException $error) {
    account_update_response(['error' => $error->getMessage()], $error->getCode() >= 400 ? $error->getCode() : 503);
} catch (Throwable $error) {
    account_update_response(['error' => 'Your account details could not be updated. Please try again.'], 503);
}
