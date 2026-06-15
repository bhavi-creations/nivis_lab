<?php
require_once __DIR__ . '/razorpay_config.php';

header('Content-Type: application/json');

function jsonResponse($payload, $status = 200)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function postJson(string $url, array $payload): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

function fetchAvailableSkus(string $baseUrl): array
{
    $query = <<<'GRAPHQL'
query AvailableProducts {
  products(filters: []) {
    items {
      sku
      name
    }
  }
}
GRAPHQL;

    $result = postJson($baseUrl . '/api/graphql', ['query' => $query]);
    if ($result['error']) {
        return [];
    }

    $decoded = json_decode((string) $result['response'], true);
    $items = $decoded['data']['products']['items'] ?? [];
    if (!is_array($items)) {
        return [];
    }

    $available = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $sku = trim((string) ($item['sku'] ?? ''));
        if ($sku !== '') {
            $available[strtoupper($sku)] = [
                'sku' => $sku,
                'name' => trim((string) ($item['name'] ?? ''))
            ];
        }
    }

    return $available;
}

function normalizeSku(array $item): string
{
    return trim((string) (
        $item['sku'] ??
        $item['productSku'] ??
        $item['productCode'] ??
        $item['id'] ??
        ''
    ));
}

function defaultBillingAddress(): array
{
    return [
        'full_name' => getenv('EVERSHOP_BILLING_NAME') ?: 'Nivis Test Customer',
        'address_1' => getenv('EVERSHOP_BILLING_ADDRESS1') ?: '123 Main Street',
        'province' => getenv('EVERSHOP_BILLING_PROVINCE') ?: 'KA',
        'country' => getenv('EVERSHOP_BILLING_COUNTRY') ?: 'IN',
        'postcode' => getenv('EVERSHOP_BILLING_POSTCODE') ?: '560001',
        'telephone' => getenv('EVERSHOP_BILLING_PHONE') ?: null
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$items = is_array($input['items'] ?? null) ? $input['items'] : [];

if ($items === []) {
    jsonResponse(['success' => false, 'message' => 'Cart total is empty.'], 400);
}

$cartItems = [];
$cartItemLabels = [];
foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }

    $sku = normalizeSku($item);
    $quantity = max(1, (int) ($item['quantity'] ?? $item['qty'] ?? 1));

    if ($sku === '') {
        jsonResponse([
            'success' => false,
            'message' => 'Each cart item must include a SKU.'
        ], 400);
    }

    $cartItems[] = [
        'sku' => $sku,
        'qty' => $quantity
    ];
    $cartItemLabels[] = [
        'sku' => $sku,
        'name' => trim((string) ($item['name'] ?? $item['product_name'] ?? $sku))
    ];
}

if ($cartItems === []) {
    jsonResponse(['success' => false, 'message' => 'Cart total is empty.'], 400);
}

$availableSkus = fetchAvailableSkus(rtrim(EVERSHOP_API_BASE_URL, '/'));
if ($availableSkus !== []) {
    $missingItems = [];
    foreach ($cartItemLabels as $cartItem) {
        $lookupKey = strtoupper($cartItem['sku']);
        if (!isset($availableSkus[$lookupKey])) {
            $missingItems[] = $cartItem;
        }
    }

    if ($missingItems !== []) {
        $missingLabel = implode(', ', array_map(static function (array $item): string {
            $label = $item['name'];
            if ($label !== '' && strcasecmp($label, $item['sku']) !== 0) {
                return $label . ' (' . $item['sku'] . ')';
            }

            return $item['sku'];
        }, $missingItems));

        jsonResponse([
            'success' => false,
            'message' => 'These items are not available in the connected Evershop catalog: ' . $missingLabel . '.',
            'error' => [
                'missing_items' => $missingItems,
                'catalog_hint' => 'The local Evershop backend appears to contain only its sample products. Import the Nivis catalog or add a SKU mapping before checkout.'
            ]
        ], 400);
    }
}

$createCartPayload = [
    'items' => $cartItems
];

if (!empty($input['customer_full_name'])) {
    $createCartPayload['customer_full_name'] = trim((string) $input['customer_full_name']);
}

if (!empty($input['customer_email'])) {
    $createCartPayload['customer_email'] = trim((string) $input['customer_email']);
}

$baseUrl = rtrim(EVERSHOP_API_BASE_URL, '/');

$cartResult = postJson($baseUrl . '/api/carts', $createCartPayload);
$cartDecoded = json_decode((string) $cartResult['response'], true);

if ($cartResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop cart service: ' . $cartResult['error']
    ], 500);
}

$cartId = $cartDecoded['data']['cartId'] ?? $cartDecoded['data']['cart_id'] ?? null;
if ($cartResult['http_code'] < 200 || $cartResult['http_code'] >= 300 || !$cartId) {
    jsonResponse([
        'success' => false,
        'message' => $cartDecoded['error']['message'] ?? 'Evershop cart creation failed.',
        'error' => $cartDecoded
    ], 500);
}

$paymentMethodResult = postJson($baseUrl . "/api/carts/{$cartId}/paymentMethods", [
    'method_code' => 'razorpay',
    'method_name' => 'Razorpay'
]);
$paymentMethodDecoded = json_decode((string) $paymentMethodResult['response'], true);

if ($paymentMethodResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop payment method service: ' . $paymentMethodResult['error']
    ], 500);
}

if ($paymentMethodResult['http_code'] < 200 || $paymentMethodResult['http_code'] >= 300 || !empty($paymentMethodDecoded['error'])) {
    jsonResponse([
        'success' => false,
        'message' => $paymentMethodDecoded['error']['message'] ?? 'Unable to set payment method on the Evershop cart.',
        'error' => $paymentMethodDecoded
    ], 500);
}

$billingAddressResult = postJson($baseUrl . "/api/carts/{$cartId}/addresses", [
    'type' => 'billing',
    'address' => array_filter(defaultBillingAddress(), static function ($value) {
        return $value !== null && $value !== '';
    })
]);
$billingAddressDecoded = json_decode((string) $billingAddressResult['response'], true);

if ($billingAddressResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop billing address service: ' . $billingAddressResult['error']
    ], 500);
}

if ($billingAddressResult['http_code'] < 200 || $billingAddressResult['http_code'] >= 300 || !empty($billingAddressDecoded['error'])) {
    jsonResponse([
        'success' => false,
        'message' => $billingAddressDecoded['error']['message'] ?? 'Unable to set billing address on the Evershop cart.',
        'error' => $billingAddressDecoded
    ], 500);
}

$orderResult = postJson($baseUrl . '/api/orders', [
    'cart_id' => $cartId
]);
$orderDecoded = json_decode((string) $orderResult['response'], true);

if ($orderResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop order service: ' . $orderResult['error']
    ], 500);
}

$order = $orderDecoded['data'] ?? null;
$orderId = is_array($order) ? ($order['uuid'] ?? null) : null;

if ($orderResult['http_code'] < 200 || $orderResult['http_code'] >= 300 || !$orderId) {
    jsonResponse([
        'success' => false,
        'message' => $orderDecoded['error']['message'] ?? 'Evershop order creation failed.',
        'error' => $orderDecoded
    ], 500);
}

$razorpayResult = postJson($baseUrl . '/api/razorpay/orders', [
    'order_id' => $orderId
]);
$razorpayDecoded = json_decode((string) $razorpayResult['response'], true);

if ($razorpayResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Razorpay gateway service: ' . $razorpayResult['error']
    ], 500);
}

$gateway = $razorpayDecoded['data'] ?? null;
if ($razorpayResult['http_code'] < 200 || $razorpayResult['http_code'] >= 300 || !is_array($gateway)) {
    jsonResponse([
        'success' => false,
        'message' => $razorpayDecoded['error']['message'] ?? 'Razorpay order creation failed.',
        'error' => $razorpayDecoded
    ], 500);
}

jsonResponse([
    'success' => true,
    'order_id' => $orderId,
    'cart_id' => $cartId,
    'gateway' => $gateway,
    'company' => RAZORPAY_COMPANY_NAME
]);
?>
