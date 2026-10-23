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
    echo json_encode([
        'authenticated' => true,
        'customer' => [
            'full_name' => $profile['fullName'] ?? '',
            'email' => $profile['email'] ?? '',
            'phone' => $address['telephone'] ?? '',
            'address_1' => $address['address1'] ?? '',
            'address_2' => $address['address2'] ?? '',
            'city' => $address['city'] ?? '',
            'state' => $address['province']['name'] ?? '',
            'postcode' => $address['postcode'] ?? '',
            'country' => $address['country']['code'] ?? 'IN'
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
