<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/backend_config.php';

// Decide which GraphQL query to send: categories or products
$type = strtolower(trim($_GET['type'] ?? 'categories'));

if ($type === 'products') {
    $query = json_encode([
        'query' => '{
            products(filters: []) {
                items {
                    id
                    name
                    price {
                        regular {
                            text
                        }
                    }
                    image {
                        url
                    }
                    category
                    concern
                }
            }
        }'
    ]);
} elseif ($type === 'categories') {
    $query = json_encode([
        'query' => '{
            categories(filters: []) {
                items {
                    id
                    name
                    url_key
                    image {
                        url
                    }
                    children_count
                }
            }
        }'
    ]);
} else {
    echo json_encode(['error' => 'Invalid type. Use ?type=categories or ?type=products']);
    exit;
}

$ch = curl_init(EVERSHOP_GRAPHQL_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'User-Agent: PHP-Bridge'
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['error' => 'Connection Error: ' . $error]);
} elseif ($httpCode !== 200) {
    echo json_encode(['error' => "Backend returned HTTP $httpCode", 'details' => 'Please check if EverShop is running.']);
} else {
    echo $response;
}
