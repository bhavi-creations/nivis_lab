<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$category = $_GET['category'] ?? 'face-wash';

$graphqlUrl = "https://admin.nivislabs.in/api/graphql";
// $ch = curl_init('https://admin.nivislabs.in/api/graphql');
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
