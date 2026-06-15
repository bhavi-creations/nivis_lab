<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/backend_config.php';

// ఫ్రంటెండ్ నుండి వచ్చే కేటగిరీని తీసుకుంటుంది (ఉదాహరణకు: face-wash, spotlights, global_dermatology)
$type = $_GET['type'] ?? '';

// 1. ఒకవేళ కేటగిరీ స్పెసిఫిక్ గా ఏమీ ఇవ్వకపోతే.. అన్ని ప్రొడక్ట్స్ తెచ్చేలా డిఫాల్ట్ క్వెరీ
if (empty($type) || $type == 'products') {
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
    // 2. డైనమిక్ కేటగిరీ ఫిల్టరింగ్: $type లో ఏ కేటగిరీ వస్తే ఆ కేటగిరీ ప్రొడక్ట్స్ మాత్రమే వస్తాయి
    // గమనిక: EverShop లో కేటగిరీని ఫిల్టర్ చేయడానికి 'category' బదులు 'category_id' లేదా 'collection' వాడాల్సి వస్తే ఇక్కడ కీ మార్చుకోవచ్చు.
    $graphqlQuery = '{
        products(filters: [{ key: "category", operation: "eq", value: "' . $type . '" }]) {
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
}

// GraphQL కి రిక్వెస్ట్ పంపడం
$query = json_encode(['query' => $graphqlQuery]);
$ch = curl_init(EVERSHOP_GRAPHQL_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
