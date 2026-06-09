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
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_FOLLOWLOCATION => true,
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

echo $response;
?>
