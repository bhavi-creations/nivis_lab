<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
// Requires isolated fixture backend :8097 and application server :8098.
$cookie = tempnam(sys_get_temp_dir(),'nivis-backend-');
function account_request($path,$payload=null) {
    global $cookie;
    $ch=curl_init('http://127.0.0.1:8098/'.$path);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER=>true,CURLOPT_COOKIEJAR=>$cookie,CURLOPT_COOKIEFILE=>$cookie,CURLOPT_TIMEOUT=>10]);
    if($payload!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($payload));
    $raw=curl_exec($ch); $status=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    if($raw===false) throw new Exception('Test server unavailable');
    return [$status,substr($raw,strpos($raw,"\r\n\r\n")+4),$raw];
}
function expect_account($condition,$description) { if(!$condition) throw new Exception($description); echo "PASS: $description\n"; }
try {
    [$status,$html]=account_request('login.php?mode=signup');
    expect_account($status===200 && strpos($html,'Andhra Pradesh')!==false,'Signup renders backend country/state choices');
    preg_match('/name="csrf" value="([a-f0-9]+)"/',$html,$match); $csrf=$match[1];
    account_request('check_login.php',['csrf'=>'bad','mode'=>'login']);
    [, $html]=account_request('login.php?mode=login'); expect_account(strpos($html,'session expired')!==false,'CSRF protected');
    account_request('check_login.php',['csrf'=>$csrf,'mode'=>'login','email'=>'backend-test@example.invalid','password'=>'wrong']);
    [, $html]=account_request('login.php?mode=login'); expect_account(strpos($html,'Invalid email or password')!==false,'Backend rejects wrong password');
    account_request('check_login.php',['csrf'=>$csrf,'mode'=>'login','email'=>'backend-test@example.invalid','password'=>'BackendTest123']);
    [, $json]=account_request('account_dashboard.php'); $data=json_decode($json,true);
    expect_account(($data['customer']['name']??'')==='Backend Customer','Dashboard uses backend profile');
    expect_account(($data['orders'][0]['order_id']??'')==='BACKEND-001' && $data['orders'][0]['items'][0]['quantity']===2,'Dashboard uses backend orders and quantities');
    expect_account(strpos($json,'fixture-access')===false,'Access token not exposed');
    account_request('logout.php');
    [, $json]=account_request('account_dashboard.php'); expect_account(json_decode($json,true)['authenticated']===false,'Logout removes backend session');
    [, $html]=account_request('forgot_password.php'); preg_match('/name="csrf" value="([a-f0-9]+)"/',$html,$match);$csrf=$match[1];
    [, $html]=account_request('forgot_password.php',['csrf'=>$csrf,'email'=>'backend-test@example.invalid']); expect_account(strpos($html,'account server will send')!==false,'Forgot password delegates to backend');
    [, $html]=account_request('reset_password.php',['csrf'=>$csrf,'token'=>str_repeat('a',128),'password'=>'NewPassword123','confirm_password'=>'NewPassword123']); expect_account(strpos($html,'Your password has been changed')!==false,'Password reset delegates to backend');
} finally { unlink($cookie); }
