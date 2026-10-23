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
    $province = $address['province'] ?? $address['state'] ?? '';
    if (is_array($province)) {
        $province = $province['code'] ?? $province['name'] ?? '';
    }
    $country = $address['country'] ?? $address['countryCode'] ?? '';
    if (is_array($country)) {
        $country = $country['code'] ?? '';
    }
    echo json_encode([
        'authenticated' => true,
        'customer' => [
            'full_name' => $address['fullName'] ?? $profile['fullName'] ?? '',
            'email' => $profile['email'] ?? '',
            'phone' => $address['telephone'] ?? $address['phone'] ?? '',
            'address_1' => $address['address1'] ?? $address['addressLine1'] ?? '',
            'address_2' => $address['address2'] ?? $address['addressLine2'] ?? '',
            'city' => $address['city'] ?? '',
            'state' => $province,
            'postcode' => $address['postcode'] ?? '',
            'country' => $country ?: 'IN'
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
