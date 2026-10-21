<?php
// Test router: run only under PHP's local development server.
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
header('Content-Type: application/json');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$body = json_decode(file_get_contents('php://input'),true) ?: [];
function fixture_response($data) { echo json_encode(['data'=>$data]); exit; }
function fixture_error($code, $message) { http_response_code($code); echo json_encode(['error'=>['message'=>$message]]); exit; }
if ($path === '/api/customer/tokens') {
    if (($body['email']??'') !== 'backend-test@example.invalid' || ($body['password']??'') !== 'BackendTest123') fixture_error(400,'Invalid email or password');
    fixture_response(['accessToken'=>'fixture-access','refreshToken'=>'fixture-refresh']);
}
if ($path === '/api/customers/reset-password') fixture_response([]);
if ($path === '/api/customers/password') {
    if (($body['token']??'') !== str_repeat('a',128)) fixture_error(400,'Token is invalid or expired');
    fixture_response([]);
}
if (($_SERVER['HTTP_AUTHORIZATION']??'') !== 'Bearer fixture-access') fixture_error(401,'Unauthorized');
if ($path === '/api/graphql') fixture_response(['currentCustomer'=>['customerId'=>98765,'uuid'=>'fixture-customer','fullName'=>'Backend Customer','email'=>'backend-test@example.invalid','addresses'=>[['telephone'=>'9876543210','address1'=>'Backend address','postcode'=>'533101','province'=>['name'=>'Andhra Pradesh'],'country'=>['name'=>'India'],'isDefault'=>true]],'orders'=>[['orderNumber'=>'BACKEND-001','uuid'=>'fixture-order','currency'=>'INR','grandTotal'=>['value'=>499],'createdAt'=>['text'=>'21 October 2026'],'paymentStatus'=>['code'=>'paid','name'=>'Paid'],'items'=>[['productName'=>'Backend serum','qty'=>2]]]]]]);
fixture_error(404,'Unknown test endpoint');
