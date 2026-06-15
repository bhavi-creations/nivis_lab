<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$category = $_GET['category'] ?? 'face-wash';

require_once __DIR__ . '/backend_config.php';

$graphqlUrl = EVERSHOP_GRAPHQL_URL;
// $ch = curl_init(EVERSHOP_GRAPHQL_URL);
$query = '
query GetProductsByCategory($categorySlug: String!) {
  productsByCategory(categorySlug: $categorySlug) {
    id
    name
    price
    originalPrice
    primaryImage
    link
  }
}';

$data = [
    "query" => $query,
    "variables" => [
        "categorySlug" => $category
    ]
];

$ch = curl_init($graphqlUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
