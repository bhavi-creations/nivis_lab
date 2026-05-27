<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$graphqlQuery = '{
  categories {
    items {
      categoryId
      name
      urlKey
      products {
        items {
          productId
          name
          urlKey
          price {
            regular {
              text
            }
          }
          image {
            url
          }
        }
      }
    }
  }
}';

$ch = curl_init("http://localhost:3000/api/graphql");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(["query" => $graphqlQuery]),
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
    exit;
}

curl_close($ch);
echo $response;
