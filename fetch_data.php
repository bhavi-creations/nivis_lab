<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$type = $_GET['type'] ?? 'products';

// GraphQL Query logic
if ($type == 'global_dermatology') {
    $graphqlQuery = '{
        products(filters: [{ key: "category", operation: "eq", value: "global-dermatology-products" }]) {
            items {
                id
                name
                url_key
                price { regular { text } }
                image { url }
                category
                concern
            }
        }
    }';
} elseif ($type == 'spotlights') {
    $graphqlQuery = '{
        products(filters: []) {
            items {
                id
                name
                url_key
                price { regular { text } }
                image { url }
                category
                concern
            }
        }
    }';
} else {
    $graphqlQuery = '{ products(filters: []) { items { id name url_key price { regular { text } } image { url } } } }';
}

$query = json_encode(['query' => $graphqlQuery]);
$ch = curl_init('http://localhost:3000/api/graphql');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>