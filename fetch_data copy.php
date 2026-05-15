<?php
// CORS మరియు JSON Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

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

$ch = curl_init('http://localhost:3000/api/graphql');
// $ch = curl_init('http://localhost:3000/graphql');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: PHP-Bridge'
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





<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$type = $_GET['type'] ?? 'products';

if ($type == 'products') {

    $query = json_encode([
        'query' => '
        {
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
        }
        '
    ]);
} else {

    echo json_encode([
        'error' => 'Invalid Type'
    ]);

    exit;
}

$ch = curl_init('http://localhost:3000/api/graphql');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);

$error = curl_error($ch);

curl_close($ch);

if ($error) {

    echo json_encode([
        'error' => $error
    ]);
} else {

    echo $response;
}

