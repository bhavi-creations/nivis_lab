<?php
header('Content-Type: application/json');
$pin = is_string($_GET['pin'] ?? null) ? $_GET['pin'] : '';
if (!preg_match('/^[1-9][0-9]{5}$/D', $pin)) { http_response_code(400); echo json_encode(['error'=>'Enter a valid six-digit Indian PIN code.']); exit; }
// Only the PIN is sent to the postal service, never the customer's full address.
$ch = curl_init('https://api.postalpincode.in/pincode/' . $pin);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>3, CURLOPT_TIMEOUT=>8]);
$raw = curl_exec($ch); $status=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
$data = json_decode((string)$raw,true);
$offices = $data[0]['PostOffice'] ?? [];
$states = array_unique(array_column($offices,'State'));
if ($status !== 200 || ($data[0]['Status'] ?? '') !== 'Success' || count($states) !== 1) {
    http_response_code(404); echo json_encode(['error'=>'PIN code could not be matched. Please select country and state.']); exit;
}
$state = reset($states);
$countries = json_decode(file_get_contents(__DIR__ . '/assets/customer-countries.json'),true)['data']['countries'];
foreach ($countries as $country) {
    if ($country['code'] !== 'IN') continue;
    foreach ($country['provinces'] as $province) {
        if (strcasecmp($province['name'], $state) === 0) {
            header('Cache-Control: public, max-age=86400');
            echo json_encode(['pin'=>$pin,'country'=>'IN','province'=>$province['code'],'state'=>$province['name']]); exit;
        }
    }
}
http_response_code(404); echo json_encode(['error'=>'Please select the state for this PIN code.']);
