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

function postJsonWithHeaders(string $url, array $payload, array $headers): array
{
    $ch = curl_init($url);
    $mergedHeaders = array_merge(['Content-Type: application/json'], $headers);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => $mergedHeaders,
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

function postRazorpayOrder(array $payload): array
{
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic ' . base64_encode(RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET),
            'Content-Type: application/x-www-form-urlencoded'
        ],
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

function normalizeLookupKey(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function provinceCandidates(array $address): array
{
    $province = trim((string) ($address['province'] ?? ''));
    $candidates = [];

    if ($province !== '') {
        $candidates[] = $province;
    }

    $provinceMap = [
        'AP' => 'Andhra Pradesh',
        'AR' => 'Arunachal Pradesh',
        'AS' => 'Assam',
        'BR' => 'Bihar',
        'CG' => 'Chhattisgarh',
        'CH' => 'Chandigarh',
        'DL' => 'Delhi',
        'GA' => 'Goa',
        'GJ' => 'Gujarat',
        'HR' => 'Haryana',
        'HP' => 'Himachal Pradesh',
        'JH' => 'Jharkhand',
        'KA' => 'Karnataka',
        'KL' => 'Kerala',
        'MP' => 'Madhya Pradesh',
        'MH' => 'Maharashtra',
        'MN' => 'Manipur',
        'ML' => 'Meghalaya',
        'MZ' => 'Mizoram',
        'NL' => 'Nagaland',
        'OD' => 'Odisha',
        'OR' => 'Odisha',
        'PB' => 'Punjab',
        'RJ' => 'Rajasthan',
        'SK' => 'Sikkim',
        'TN' => 'Tamil Nadu',
        'TS' => 'Telangana',
        'TG' => 'Telangana',
        'TR' => 'Tripura',
        'UP' => 'Uttar Pradesh',
        'UK' => 'Uttarakhand',
        'UT' => 'Uttarakhand',
        'WB' => 'West Bengal'
    ];

    $upperProvince = strtoupper($province);
    if ($upperProvince !== '' && isset($provinceMap[$upperProvince])) {
        $candidates[] = $provinceMap[$upperProvince];
    }

    return array_values(array_unique(array_filter($candidates)));
}

function fetchAvailableCatalogItems(string $baseUrl): array
{
    $query = <<<'GRAPHQL'
query AvailableProducts {
  products(filters: []) {
    items {
      id
      sku
      name
      urlKey
    }
  }
}
GRAPHQL;

    $result = postJson($baseUrl . '/graphql', ['query' => $query]);
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

        $catalogItem = [
            'id' => trim((string) ($item['id'] ?? '')),
            'sku' => trim((string) ($item['sku'] ?? '')),
            'name' => trim((string) ($item['name'] ?? '')),
            'urlKey' => trim((string) ($item['urlKey'] ?? ''))
        ];

        foreach ([$catalogItem['sku'], $catalogItem['id'], $catalogItem['urlKey'], $catalogItem['name']] as $candidate) {
            $candidateKey = normalizeLookupKey((string) $candidate);
            if ($candidateKey !== '') {
                $available[$candidateKey] = $catalogItem;
            }
        }
    }

    return $available;
}

function fetchAvailableShippingMethods(string $baseUrl, string $cartId, array $address): array
{
    $query = <<<'GRAPHQL'
query CartShippingMethods($cartId: String!, $country: String, $province: String, $postcode: String) {
  cart(id: $cartId) {
    availableShippingMethods(country: $country, province: $province, postcode: $postcode) {
      id
      code
      name
      cost {
        value
        text
      }
    }
  }
}
GRAPHQL;

    foreach (provinceCandidates($address) as $province) {
        $result = postJson($baseUrl . '/graphql', [
            'query' => $query,
            'variables' => [
                'cartId' => $cartId,
                'country' => $address['country'] ?? null,
                'province' => $province,
                'postcode' => $address['postcode'] ?? null
            ]
        ]);

        if ($result['error']) {
            continue;
        }

        $decoded = json_decode((string) $result['response'], true);
        $methods = $decoded['data']['cart']['availableShippingMethods'] ?? [];
        if (!is_array($methods) || $methods === []) {
            continue;
        }

        return array_values(array_filter($methods, static function ($method) {
            return is_array($method) && !empty($method['code']);
        }));
    }

    return [];
}

function normalizeSku(array $item): string
{
    foreach (['sku', 'productSku', 'productCode', 'id', 'productId', 'urlKey', 'url_key', 'name', 'product_name'] as $key) {
        $value = trim((string) ($item[$key] ?? ''));
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

function resolveCatalogItem(array $item, array $catalog): ?array
{
    $candidates = [
        $item['sku'] ?? null,
        $item['productSku'] ?? null,
        $item['productCode'] ?? null,
        $item['id'] ?? null,
        $item['productId'] ?? null,
        $item['urlKey'] ?? null,
        $item['url_key'] ?? null,
        $item['name'] ?? null,
        $item['product_name'] ?? null
    ];

    foreach ($candidates as $candidate) {
        $candidateKey = normalizeLookupKey(trim((string) $candidate));
        if ($candidateKey !== '' && isset($catalog[$candidateKey])) {
            return $catalog[$candidateKey];
        }
    }

    return null;
}

function sanitizeAddress(array $address, array $fallback = []): array
{
    $normalized = [];

    $map = [
        'full_name' => ['full_name', 'name', 'customer_name'],
        'address_1' => ['address_1', 'address1', 'street', 'line1'],
        'address_2' => ['address_2', 'address2', 'line2', 'landmark'],
        'city' => ['city', 'town'],
        'province' => ['province', 'state'],
        'postcode' => ['postcode', 'zip', 'pincode'],
        'country' => ['country'],
        'telephone' => ['telephone', 'phone', 'mobile'],
        'email' => ['email']
    ];

    foreach ($map as $target => $keys) {
        foreach ($keys as $key) {
            $value = trim((string) ($address[$key] ?? ''));
            if ($value === '') {
                $value = trim((string) ($fallback[$key] ?? ''));
            }
            if ($value !== '') {
                $normalized[$target] = $value;
                break;
            }
        }
    }

    return $normalized;
}

function defaultAddress(): array
{
    return [
        'full_name' => getenv('EVERSHOP_BILLING_NAME') ?: 'Nivis Test Customer',
        'address_1' => getenv('EVERSHOP_BILLING_ADDRESS1') ?: '123 Main Street',
        'address_2' => getenv('EVERSHOP_BILLING_ADDRESS2') ?: '',
        'city' => getenv('EVERSHOP_BILLING_CITY') ?: 'Bengaluru',
        'province' => getenv('EVERSHOP_BILLING_PROVINCE') ?: 'KA',
        'country' => getenv('EVERSHOP_BILLING_COUNTRY') ?: 'IN',
        'postcode' => getenv('EVERSHOP_BILLING_POSTCODE') ?: '560001',
        'telephone' => getenv('EVERSHOP_BILLING_PHONE') ?: null,
        'email' => getenv('EVERSHOP_BILLING_EMAIL') ?: null
    ];
}

function addressIsComplete(array $address): bool
{
    return !empty($address['full_name'])
        && !empty($address['address_1'])
        && !empty($address['city'])
        && !empty($address['province'])
        && !empty($address['postcode'])
        && !empty($address['country']);
}

function pickShippingMethod(array $methods): ?array
{
    if ($methods === []) {
        return null;
    }

    usort($methods, static function (array $left, array $right): int {
        $leftCost = (float) ($left['cost']['value'] ?? PHP_FLOAT_MAX);
        $rightCost = (float) ($right['cost']['value'] ?? PHP_FLOAT_MAX);
        return $leftCost <=> $rightCost;
    });

    return $methods[0] ?? null;
}

function fallbackShippingMethod(): ?array
{
    $code = trim((string) (getenv('EVERSHOP_FALLBACK_SHIPPING_METHOD_CODE') ?: ''));
    if ($code === '') {
        return null;
    }

    return [
        'id' => trim((string) (getenv('EVERSHOP_FALLBACK_SHIPPING_METHOD_ID') ?: $code)),
        'code' => $code,
        'name' => trim((string) (getenv('EVERSHOP_FALLBACK_SHIPPING_METHOD_NAME') ?: 'Standard Shipping')),
        'cost' => [
            'value' => (float) (getenv('EVERSHOP_FALLBACK_SHIPPING_METHOD_AMOUNT') ?: 0),
            'text' => trim((string) (getenv('EVERSHOP_FALLBACK_SHIPPING_METHOD_TEXT') ?: 'Calculated at confirmation'))
        ]
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$cartId = trim((string) ($input['cart_id'] ?? ''));
$cartTotal = (float) ($input['cart_total'] ?? 0);
$items = is_array($input['items'] ?? null) ? $input['items'] : [];
$createCartPayload = null;

if ($cartId === '') {
    if ($items === []) {
        jsonResponse(['success' => false, 'message' => 'Cart total is empty.'], 400);
    }

    $availableCatalog = fetchAvailableCatalogItems(rtrim(EVERSHOP_API_BASE_URL, '/'));

    $cartItems = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $resolvedCatalogItem = $availableCatalog !== [] ? resolveCatalogItem($item, $availableCatalog) : null;
        $resolvedSku = trim((string) (
            $resolvedCatalogItem['sku'] ??
            $resolvedCatalogItem['id'] ??
            $resolvedCatalogItem['urlKey'] ??
            normalizeSku($item)
        ));
        $quantity = max(1, (int) ($item['quantity'] ?? $item['qty'] ?? 1));

        if ($resolvedSku === '' || ($availableCatalog !== [] && !$resolvedCatalogItem)) {
            jsonResponse([
                'success' => false,
                'message' => $resolvedSku === ''
                    ? 'Each cart item must include a SKU or a resolvable product identifier.'
                    : 'These items are not available in the connected Evershop catalog.'
            ], 400);
        }

        $cartItems[] = [
            'sku' => $resolvedSku,
            'qty' => $quantity
        ];
    }

    if ($cartItems === []) {
        jsonResponse(['success' => false, 'message' => 'Cart total is empty.'], 400);
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

    $customerPhone = trim((string) ($input['customer_phone'] ?? ''));
    if ($customerPhone !== '') {
        $createCartPayload['customer_phone'] = $customerPhone;
    }
}

$defaultAddress = defaultAddress();
$billingAddressInput = is_array($input['billing_address'] ?? null) ? $input['billing_address'] : [];
$shippingAddressInput = is_array($input['shipping_address'] ?? null) ? $input['shipping_address'] : [];
$billingAddress = sanitizeAddress($billingAddressInput, $defaultAddress);
$shippingAddress = sanitizeAddress($shippingAddressInput, $billingAddress ?: $defaultAddress);

if (!addressIsComplete($billingAddress)) {
    jsonResponse([
        'success' => false,
        'message' => 'Please fill in all required billing address fields.'
    ], 400);
}

if (!addressIsComplete($shippingAddress)) {
    jsonResponse([
        'success' => false,
        'message' => 'Please fill in all required shipping address fields.'
    ], 400);
}

$amountSubunits = (int) round($cartTotal * 100);
if ($amountSubunits <= 0) {
    jsonResponse([
        'success' => false,
        'message' => 'Cart total is empty.'
    ], 400);
}

$baseUrl = rtrim(EVERSHOP_API_BASE_URL, '/');

if ($cartId === '') {
    $cartResult = postJson($baseUrl . '/carts', $createCartPayload);
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
}

$paymentMethodResult = postJson($baseUrl . "/carts/{$cartId}/paymentMethods", [
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

$billingAddressResult = postJson($baseUrl . "/carts/{$cartId}/addresses", [
    'type' => 'billing',
    'address' => array_filter($billingAddress, static function ($value) {
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

$shippingWarning = null;
if ($shippingAddress !== []) {
    $shippingAddressResult = postJson($baseUrl . "/carts/{$cartId}/addresses", [
        'type' => 'shipping',
        'address' => array_filter($shippingAddress, static function ($value) {
            return $value !== null && $value !== '';
        })
    ]);
    $shippingAddressDecoded = json_decode((string) $shippingAddressResult['response'], true);

    if ($shippingAddressResult['error']) {
        $shippingWarning = 'Unable to connect to Evershop shipping address service: ' . $shippingAddressResult['error'];
    }

    if ($shippingWarning === null && ($shippingAddressResult['http_code'] < 200 || $shippingAddressResult['http_code'] >= 300 || !empty($shippingAddressDecoded['error']))) {
        $shippingWarning = $shippingAddressDecoded['error']['message'] ?? 'Shipping address could not be validated for this cart.';
    }
}

$evershopOrderResult = postJson($baseUrl . '/orders', [
    'cart_id' => $cartId
]);
$evershopOrderDecoded = json_decode((string) $evershopOrderResult['response'], true);

if ($evershopOrderResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Evershop order service: ' . $evershopOrderResult['error']
    ], 500);
}

$evershopOrder = $evershopOrderDecoded['data'] ?? null;
$evershopOrderId = is_array($evershopOrder) ? ($evershopOrder['uuid'] ?? null) : null;

if ($evershopOrderResult['http_code'] < 200 || $evershopOrderResult['http_code'] >= 300 || !$evershopOrderId) {
    jsonResponse([
        'success' => false,
        'message' => $evershopOrderDecoded['error']['message'] ?? 'Evershop order creation failed.',
        'error' => $evershopOrderDecoded
    ], 500);
}

$razorpayOrderRequest = [
    'amount' => $amountSubunits,
    'currency' => RAZORPAY_CURRENCY,
    'receipt' => $evershopOrderId,
    'payment_capture' => '1',
    'notes[order_id]' => $evershopOrderId,
    'notes[cart_id]' => $cartId
];

$razorpayCreateOrderResult = postRazorpayOrder($razorpayOrderRequest);
$razorpayCreateOrderDecoded = json_decode((string) $razorpayCreateOrderResult['response'], true);

if ($razorpayCreateOrderResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to connect to Razorpay: ' . $razorpayCreateOrderResult['error']
    ], 500);
}

if ($razorpayCreateOrderResult['http_code'] < 200 || $razorpayCreateOrderResult['http_code'] >= 300 || !empty($razorpayCreateOrderDecoded['error'])) {
    jsonResponse([
        'success' => false,
        'message' => $razorpayCreateOrderDecoded['error']['description'] ?? $razorpayCreateOrderDecoded['error']['message'] ?? 'Failed to create Razorpay order.',
        'error' => $razorpayCreateOrderDecoded
    ], 500);
}

$razorpayData = $razorpayCreateOrderDecoded;
$gateway = $razorpayData['id'] ?? null;

if (!$gateway) {
    jsonResponse([
        'success' => false,
        'message' => 'Razorpay order creation failed: missing order ID in response.',
        'error' => $razorpayCreateOrderDecoded
    ], 500);
}

$syncResult = postJsonWithHeaders($baseUrl . '/razorpay/sync-order', [
    'order_id' => $evershopOrderId,
    'razorpay_order_id' => $gateway
], [
    'X-Razorpay-Sync-Token: ' . RAZORPAY_SYNC_TOKEN
]);
$syncDecoded = json_decode((string) $syncResult['response'], true);

if ($syncResult['error']) {
    jsonResponse([
        'success' => false,
        'message' => 'Unable to sync Razorpay order with Evershop: ' . $syncResult['error']
    ], 500);
}

if ($syncResult['http_code'] < 200 || $syncResult['http_code'] >= 300 || !empty($syncDecoded['error'])) {
    jsonResponse([
        'success' => false,
        'message' => $syncDecoded['error']['message'] ?? 'Failed to sync Razorpay order with Evershop.',
        'error' => $syncDecoded
    ], 500);
}

jsonResponse([
    'success' => true,
    'order_id' => $evershopOrderId,
    'cart_id' => $cartId,
    'gateway' => [
        'razorpayOrderId' => $razorpayData['id'],
        'keyId' => RAZORPAY_KEY_ID,
        'amount' => $razorpayData['amount'],
        'currency' => $razorpayData['currency'] ?? RAZORPAY_CURRENCY,
        'status' => $razorpayData['status'] ?? null
    ],
    'company' => RAZORPAY_COMPANY_NAME,
    'shipping_warning' => $shippingWarning
]);
?>
