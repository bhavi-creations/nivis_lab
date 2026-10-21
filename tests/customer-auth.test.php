<?php
// Run from CLI only: php tests/customer-auth.test.php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
ini_set('session.save_path', sys_get_temp_dir());
require __DIR__ . '/../customer_auth.php';
$db = customer_db();
$email = 'auth-test-' . bin2hex(random_bytes(6)) . '@example.invalid';
$cookie = tempnam(sys_get_temp_dir(), 'nivis-auth-');
function request_auth($path, $data = null) {
    global $cookie;
    $ch = curl_init('http://localhost/nivis_lab/' . $path);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_COOKIEJAR => $cookie, CURLOPT_COOKIEFILE => $cookie, CURLOPT_HEADER => true, CURLOPT_TIMEOUT => 20]);
    if ($data !== null) { curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); }
    $body = curl_exec($ch);
    if ($body === false) { throw new Exception(curl_error($ch)); }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$status, $body];
}
function check_auth($condition, $message) {
    if (!$condition) { throw new Exception($message); }
    echo "PASS: $message\n";
}
try {
    [$status, $body] = request_auth('index.php');
    check_auth($status === 302 && strpos($body, 'Location: login.php') !== false, 'Home requires login');
    [$status, $body] = request_auth('login.php');
    preg_match('/name="csrf" value="([a-f0-9]+)"/', $body, $matches);
    $csrf = $matches[1];
    check_auth($status === 200 && strpos($body, 'name="address"') !== false, 'Signup fields render');
    request_auth('check_login.php', ['mode'=>'signup','csrf'=>'invalid']);
    [, $body] = request_auth('login.php');
    check_auth(strpos($body, 'session expired') !== false, 'CSRF rejected');
    request_auth('check_login.php', ['mode'=>'signup','csrf'=>$csrf,'email'=>'bad']);
    [, $body] = request_auth('login.php');
    check_auth(strpos($body, 'valid email') !== false, 'Invalid signup rejected');
    $db->prepare('INSERT INTO website_customers(name,address,phone,email,password_hash) VALUES(?,?,?,?,?)')->execute(['Auth Test','Test address','9876543210',$email,password_hash('9876543210', PASSWORD_DEFAULT)]);
    request_auth('check_login.php', ['mode'=>'signup','csrf'=>$csrf,'name'=>'Test','address'=>'Test','phone'=>'9876543210','email'=>$email]);
    [, $body] = request_auth('login.php');
    check_auth(strpos($body, 'already registered') !== false, 'Duplicate email rejected');
    request_auth('check_login.php', ['mode'=>'login','csrf'=>$csrf,'email'=>$email,'password'=>'wrong']);
    [, $body] = request_auth('login.php?mode=login');
    check_auth(strpos($body, 'Invalid email or password') !== false, 'Wrong password rejected');
    request_auth('check_login.php', ['mode'=>'login','csrf'=>$csrf,'email'=>$email,'password'=>'9876543210']);
    [$status, $body] = request_auth('login.php');
    check_auth($status === 302 && strpos($body, 'Location: index.php') !== false, 'Email and phone login succeeds');
    require_once __DIR__ . '/../customer_orders.php';
    $owner = $db->prepare('SELECT id FROM website_customers WHERE email=?');
    $owner->execute([$email]);
    $_SESSION['customer_id'] = $owner->fetchColumn();
    $orderId = 'test-' . bin2hex(random_bytes(8));
    customer_order_save($orderId, 'gateway-test', [['name'=>'Test serum', 'qty'=>2]], 125000, 'INR');
    customer_order_paid($orderId, 'incorrect-gateway');
    [, $body] = request_auth('account_dashboard.php');
    $data = json_decode(substr($body, strpos($body, "\r\n\r\n") + 4), true);
    check_auth($data['orders'][0]['payment_status'] === 'pending', 'Wrong gateway cannot mark order paid');
    customer_order_paid($orderId, 'gateway-test');
    [, $body] = request_auth('account_dashboard.php');
    $data = json_decode(substr($body, strpos($body, "\r\n\r\n") + 4), true);
    check_auth($data['customer']['email'] === $email && count($data['orders']) === 1 && $data['orders'][0]['items'][0]['quantity'] === 2 && $data['orders'][0]['payment_status'] === 'paid', 'Dashboard returns owned order items and verified payment status');
    request_auth('logout.php');
    [$status, $body] = request_auth('index.php');
    check_auth($status === 302, 'Logout removes access');
    [, $body] = request_auth('account_dashboard.php');
    check_auth(strpos($body, '"authenticated":false') !== false, 'Logged-out dashboard hides personal details');
} finally {
    $db->prepare('DELETE FROM website_customers WHERE email=?')->execute([$email]);
    unlink($cookie);
}
