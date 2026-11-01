<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/backend_config.php';

// The live EverShop schema currently exposes only category names.
$graphqlQuery = '{
    categories {
        items {
            name
        }
    }
}';

$query = json_encode(['query' => $graphqlQuery]);
$ch = curl_init(EVERSHOP_GRAPHQL_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CONNECTTIMEOUT_MS => 2000,
    CURLOPT_TIMEOUT_MS => 5000,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; NivisLabsCategoryFetcher/1.0)'
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    $error = curl_error($ch);
    if (stripos($error, 'ssl') !== false || stripos($error, 'certificate') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
    }
}
curl_close($ch);

if (!$response) {
    echo json_encode(['error' => 'Unable to fetch categories from backend']);
    exit;
}

$decoded = json_decode($response, true);
if (!is_array($decoded) || !empty($decoded['errors'])) {
    http_response_code(502);
    echo json_encode(['error' => 'Unable to load categories from EverShop']);
    exit;
}

$items = $decoded['data']['categories']['items'] ?? [];
$normalized = [];
foreach ($items as $item) {
    $name = trim((string) ($item['name'] ?? ''));
    if ($name === '') {
        continue;
    }

    $normalized[] = [
        'id' => $item['id'] ?? null,
        'name' => $name,
        'url_key' => $item['url_key'] ?? $item['urlKey'] ?? strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'))
    ];
}

$decoded['data']['categories']['items'] = $normalized;
echo json_encode($decoded);
?>
