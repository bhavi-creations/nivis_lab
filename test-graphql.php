<?php
// GraphQL API টেস্ট করার জন্য
header('Content-Type: application/json');

// GraphQL Query পাঠান
$query = '{
  products {
    id
    name
    price
    category
    description
  }
  authStatus {
    isLoggedIn
    username
  }
}';

// API কল করুন
$ch = curl_init('http://localhost/nivis_lab/graphql-api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
