<?php
require_once __DIR__ . '/backend_config.php';

session_start();
header('Content-Type: application/json');

function jsonResponse($payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function normalizeLookupKey(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
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

function postJson(string $url, array $payload): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return ['response' => $response, 'http_code' => $httpCode, 'error' => $error];
}

function requestJson(string $method, string $url, ?array $payload = null): array
{
    $ch = curl_init($url);
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30,
    ];

    if ($payload !== null) {
        $options[CURLOPT_POSTFIELDS] = json_encode($payload);
    }

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return ['response' => $response, 'http_code' => $httpCode, 'error' => $error];
}

function currentCartId(): ?string
{
    $cartId = trim((string) ($_SESSION['evershop_cart_uuid'] ?? ''));
    return $cartId !== '' ? $cartId : null;
}

function storeCartId(?string $cartId): void
{
    $_SESSION['evershop_cart_uuid'] = $cartId ?: null;
}

function normalizeQty($qty): int
{
    return max(1, (int) $qty);
}

function createCartFromItems(array $items): array
{
    $catalog = fetchAvailableCatalogItems(rtrim(EVERSHOP_BASE_URL, '/'));
    $payload = [
        'items' => array_values(array_filter(array_map(static function (array $item) use ($catalog): ?array {
            $resolvedCatalogItem = $catalog !== [] ? resolveCatalogItem($item, $catalog) : null;
            $resolvedSku = trim((string) (
                $resolvedCatalogItem['sku'] ??
                $resolvedCatalogItem['id'] ??
                $resolvedCatalogItem['urlKey'] ??
                normalizeSku($item)
            ));

            if ($resolvedSku === '') {
                return null;
            }

            return [
                'sku' => $resolvedSku,
                'qty' => normalizeQty($item['qty'] ?? $item['quantity'] ?? 1)
            ];
        }, $items)))
    ];

    if ($payload['items'] === []) {
        return ['error' => 'No resolvable cart items were provided.'];
    }

    $customerFullName = trim((string) ($GLOBALS['input']['customer_full_name'] ?? ''));
    $customerEmail = trim((string) ($GLOBALS['input']['customer_email'] ?? ''));
    if ($customerFullName !== '') {
        $payload['customer_full_name'] = $customerFullName;
    }
    if ($customerEmail !== '') {
        $payload['customer_email'] = $customerEmail;
    }

    $result = postJson(rtrim(EVERSHOP_BASE_URL, '/') . '/api/carts', $payload);
    $decoded = json_decode((string) $result['response'], true);

    if ($result['error']) {
        return ['error' => 'Unable to connect to EverShop cart service: ' . $result['error']];
    }

    $cartId = $decoded['data']['cartId'] ?? $decoded['data']['cart_id'] ?? null;
    if ($result['http_code'] < 200 || $result['http_code'] >= 300 || !$cartId) {
        return ['error' => $decoded['error']['message'] ?? 'Unable to create EverShop cart.', 'debug' => $decoded];
    }

    storeCartId((string) $cartId);
    return ['cart_id' => (string) $cartId, 'response' => $decoded];
}

function syncAddItem(string $cartId, array $item): array
{
    $result = requestJson('POST', rtrim(EVERSHOP_BASE_URL, '/') . "/api/cart/{$cartId}/items", [
        'sku' => trim((string) ($item['sku'] ?? '')),
        'qty' => normalizeQty($item['qty'] ?? $item['quantity'] ?? 1)
    ]);
    $decoded = json_decode((string) $result['response'], true);

    if ($result['error']) {
        return ['error' => 'Unable to connect to EverShop cart service: ' . $result['error']];
    }

    if ($result['http_code'] < 200 || $result['http_code'] >= 300 || !empty($decoded['error'])) {
        return ['error' => $decoded['error']['message'] ?? 'Unable to add item to EverShop cart.', 'debug' => $decoded];
    }

    return ['response' => $decoded];
}

function removeItem(string $cartId, string $itemId): array
{
    $result = requestJson('DELETE', rtrim(EVERSHOP_BASE_URL, '/') . "/api/cart/{$cartId}/items/{$itemId}");
    $decoded = json_decode((string) $result['response'], true);

    if ($result['error']) {
        return ['error' => 'Unable to connect to EverShop cart service: ' . $result['error']];
    }

    if ($result['http_code'] < 200 || $result['http_code'] >= 300 || !empty($decoded['error'])) {
        return ['error' => $decoded['error']['message'] ?? 'Unable to remove item from EverShop cart.', 'debug' => $decoded];
    }

    return ['response' => $decoded];
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = [];
}
$GLOBALS['input'] = $input;

if ($method === 'GET') {
    jsonResponse([
        'success' => true,
        'cart_id' => currentCartId()
    ]);
}

if ($method !== 'POST' && $method !== 'DELETE') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
}

$action = strtolower(trim((string) ($input['action'] ?? 'add')));
$cartId = currentCartId();

if ($action === 'sync') {
    $items = is_array($input['items'] ?? null) ? $input['items'] : [];
    if ($items === []) {
        jsonResponse(['success' => false, 'message' => 'No items provided for sync.'], 400);
    }

    $createResult = createCartFromItems($items);
    if (!empty($createResult['error'])) {
        jsonResponse(['success' => false, 'message' => $createResult['error'], 'error' => $createResult['debug'] ?? null], 400);
    }

    jsonResponse([
        'success' => true,
        'cart_id' => $createResult['cart_id']
    ]);
}

if ($action === 'clear') {
    storeCartId(null);
    jsonResponse(['success' => true, 'cart_id' => null]);
}

if ($action === 'remove') {
    $itemId = trim((string) ($input['item_id'] ?? $input['remote_id'] ?? ''));
    if ($cartId === null || $itemId === '') {
        jsonResponse(['success' => false, 'message' => 'Missing cart id or item id.'], 400);
    }

    $result = removeItem($cartId, $itemId);
    if (!empty($result['error'])) {
        jsonResponse(['success' => false, 'message' => $result['error'], 'error' => $result['debug'] ?? null], 400);
    }

    jsonResponse(['success' => true, 'cart_id' => $cartId]);
}

$sku = trim((string) ($input['sku'] ?? ''));
$qty = normalizeQty($input['qty'] ?? $input['quantity'] ?? 1);

if ($sku === '') {
    jsonResponse(['success' => false, 'message' => 'SKU is required.'], 400);
}

if ($cartId === null) {
    $createResult = createCartFromItems([['sku' => $sku, 'qty' => $qty]]);
    if (!empty($createResult['error'])) {
        jsonResponse(['success' => false, 'message' => $createResult['error'], 'error' => $createResult['debug'] ?? null], 400);
    }

    jsonResponse([
        'success' => true,
        'cart_id' => $createResult['cart_id'],
        'created' => true,
        'item' => $createResult['response']['data']['items'] ?? null
    ]);
}

$result = syncAddItem($cartId, ['sku' => $sku, 'qty' => $qty]);
if (!empty($result['error'])) {
    jsonResponse(['success' => false, 'message' => $result['error'], 'error' => $result['debug'] ?? null], 400);
}

jsonResponse([
    'success' => true,
    'cart_id' => $cartId,
    'item' => $result['response']['data']['item'] ?? $result['response']['data']['items'] ?? null
]);
