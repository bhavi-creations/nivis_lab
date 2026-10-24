<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

if (($_SESSION['auth_backend'] ?? '') !== 'evershop' || empty($_SESSION['backend_access_token'])) {
    echo json_encode(['authenticated' => false]);
    exit;
}

try {
    $profile = customer_backend_profile();
    if (!$profile) {
        echo json_encode(['authenticated' => false]);
        exit;
    }
    $addresses = $profile['addresses'] ?? [];
    $address = $addresses[0] ?? [];
    foreach ($addresses as $candidate) {
        if (!empty($candidate['isDefault'])) {
            $address = $candidate;
            break;
        }
    }
    $countryCode = $address['country']['code'] ?? 'IN';
    $provinceCode = (string) ($address['province']['code'] ?? '');
    $provinceAliases = ['TS' => 'IN-TG', 'TG' => 'IN-TG', 'CG' => 'IN-CT', 'UK' => 'IN-UT', 'UT' => 'IN-UT', 'GO' => 'IN-GA', 'OD' => 'IN-OR', 'OR' => 'IN-OR'];
    $provinceCode = $provinceAliases[strtoupper($provinceCode)] ?? $provinceCode;
    if ($countryCode === 'IN' && preg_match('/^[A-Z]{2}$/i', $provinceCode)) {
        $provinceCode = 'IN-' . strtoupper($provinceCode);
    }
    echo json_encode([
        'authenticated' => true,
        'customer' => [
            'full_name' => $profile['fullName'] ?? '',
            'email' => $profile['email'] ?? '',
            'phone' => $address['telephone'] ?? '',
            'address_1' => $address['address1'] ?? '',
            'address_2' => $address['address2'] ?? '',
            'city' => $address['city'] ?? '',
            'state' => $provinceCode,
            'postcode' => $address['postcode'] ?? '',
            'country' => $countryCode
        ]
    ]);
} catch (CustomerBackendException $error) {
    if ($error->getCode() === 401) {
        echo json_encode(['authenticated' => false]);
        exit;
    }
    http_response_code(503);
    echo json_encode(['error' => $error->getMessage()]);
}
