<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// EverShop నుండి అన్ని కేటగిరీలను తెచ్చే GraphQL Query
$graphqlQuery = '{
    categories {
        items {
            id
            name
            url_key
        }
    }
}';

$query = json_encode(['query' => $graphqlQuery]);
$ch = curl_init('https://admin.nivislabs.in/api/graphql');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
