<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/backend_config.php';

$graphqlQuery = '
query GetProductsByCategory($categorySlug: String!) {
  productsByCategory(categorySlug: $categorySlug) {
    id
    name
    subtitle
    price
    originalPrice
    reviewsCount
    stars
    boughtTag
    primaryImage
    secondaryImage
    concern
    ingredient
    type
    link
  }
}
';

$variables = [
    "categorySlug" => "moisturizers"
];

$data = json_encode([
    "query" => $graphqlQuery,
    "variables" => $variables
]);

$ch = curl_init(EVERSHOP_GRAPHQL_URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

echo $response;
