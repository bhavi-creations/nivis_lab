<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
$returnTo = customer_value('return');
$returnOrderId = customer_value('order_id');
$returnToTracking = $returnTo === 'tracking.php' && $returnOrderId !== '' && strlen($returnOrderId) <= 100;
$returnSuffix = $returnToTracking
    ? '&return=tracking.php&order_id=' . rawurlencode($returnOrderId)
    : ($returnTo === 'checkout.php' ? '&return=checkout.php' : '');
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], customer_value('csrf'))) {
    $_SESSION['auth_error'] = 'Your session expired. Please try again.';
    header('Location: login.php?mode=login' . $returnSuffix); exit;
}
$mode = customer_value('mode') === 'signup' ? 'signup' : 'login';
$email = strtolower(customer_value('email') ?: customer_value('username'));
$name = customer_value('name'); $address = customer_value('address'); $phone = customer_value('phone');
$challengeId = customer_value('challenge_id'); $otpCode = customer_value('otp_code');
$country = strtoupper(customer_value('country')); $province = customer_value('province'); $postcode = customer_value('postcode');
$_SESSION['auth_old'] = compact('email','name','address','phone','country','province','postcode');
try {
    if (($_SESSION['login_attempts'] ?? 0) >= 5 && time() - ($_SESSION['login_attempt_time'] ?? 0) < 300) throw new RuntimeException('Too many attempts. Please try again in five minutes.');
    if (time() - ($_SESSION['login_attempt_time'] ?? 0) >= 300) $_SESSION['login_attempts'] = 0;
    if ($mode === 'signup') {
        if ($name === '' || strlen($name) > 120 || $address === '' || strlen($address) > 255 || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\+[1-9][0-9]{7,14}$/D', $phone) || !preg_match('/^[A-Z]{2}$/D', $country) || $province === '' || strlen($province) > 100 || $postcode === '' || strlen($postcode) > 20 || !preg_match('/^[0-9]{6}$/D', $otpCode) || !preg_match('/^[0-9a-f-]{36}$/iD', $challengeId)) throw new RuntimeException('Complete the registration details and enter the six-digit WhatsApp code.');
        $countries = json_decode(file_get_contents(__DIR__ . '/assets/customer-countries.json'), true)['data']['countries'];
        $selectedCountry = null;
        foreach ($countries as $entry) if ($entry['code'] === $country) { $selectedCountry=$entry; break; }
        $stateCodes = array_column($selectedCountry['provinces'] ?? [], 'code');
        if (!$selectedCountry || ($stateCodes && !in_array($province,$stateCodes,true))) throw new RuntimeException('Please select a valid country and state.');
        $verified = customer_backend_otp('verify', [
            'phone' => $phone, 'code' => $otpCode, 'challengeId' => $challengeId,
            'registration' => ['fullName' => $name, 'email' => $email]
        ]);
        $customer = $verified['customer'] ?? null;
        if (!$customer || empty($customer['customer_id'])) throw new CustomerBackendException('Your account could not be created. Please try again.');
        customer_login(['id'=>$customer['customer_id'],'name'=>$customer['full_name'] ?? $name]);
        $_SESSION['customer_email'] = $customer['email'] ?? $email;
        $_SESSION['auth_backend'] = 'evershop';
        $_SESSION['auth_notice'] = 'Your account is ready. You can sign in next time with your WhatsApp number and a verification code.';
        try {
            if (!empty($customer['uuid'])) customer_backend_request('/customers/' . rawurlencode($customer['uuid']) . '/addresses', ['full_name'=>$name,'address_1'=>$address,'telephone'=>$phone,'country'=>$country,'province'=>$province,'postcode'=>$postcode,'is_default'=>1], true);
        } catch (Throwable $error) {
            $_SESSION['auth_notice'] .= ' Your account was created, but the backend could not save your address. Please add it in your backend account address book.';
        }
        try { customer_welcome($name,$email); $_SESSION['auth_notice'] .= ' A welcome email has been sent.'; }
        catch (Throwable $error) { $_SESSION['auth_notice'] .= ' The welcome email could not be sent.'; }
    } else {
        if (!preg_match('/^\+[1-9][0-9]{7,14}$/D', $phone) || !preg_match('/^[0-9]{6}$/D', $otpCode) || !preg_match('/^[0-9a-f-]{36}$/iD', $challengeId)) throw new RuntimeException('Enter your WhatsApp number and the six-digit verification code.');
        try {
            $verified = customer_backend_otp('verify', ['phone' => $phone, 'code' => $otpCode, 'challengeId' => $challengeId]);
            $customer = $verified['customer'] ?? null;
            if (!$customer || empty($customer['customer_id'])) throw new CustomerBackendException('The WhatsApp number could not be signed in.');
        }
        catch (CustomerBackendException $error) {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['login_attempt_time'] = time();
            throw $error;
        }
        customer_login(['id'=>$customer['customer_id'],'name'=>$customer['full_name'] ?? '']);
        $_SESSION['customer_email'] = $customer['email'] ?? '';
        $_SESSION['auth_backend'] = 'evershop';
        unset($_SESSION['login_attempts'], $_SESSION['login_attempt_time']);
    }
    unset($_SESSION['auth_challenge_id'], $_SESSION['auth_challenge_purpose'], $_SESSION['auth_resend_at_ms'], $_SESSION['auth_resend_phone_hash']);
    unset($_SESSION['auth_old']);
    if ($returnToTracking) {
        unset($_SESSION['auth_notice']);
        header('Location: tracking.php?order_id=' . rawurlencode($returnOrderId)); exit;
    }
    if ($returnTo === 'checkout.php') {
        unset($_SESSION['auth_notice']);
        header('Location: checkout.php'); exit;
    }
    header('Location: login.php'); exit;
} catch (RuntimeException $error) {
    $_SESSION['auth_error'] = $error->getMessage();
    if (preg_match('/^[0-9a-f-]{36}$/iD', $challengeId)) {
        $_SESSION['auth_challenge_id'] = $challengeId;
        $_SESSION['auth_challenge_purpose'] = $mode === 'signup' ? 'registration' : 'signin';
        $_SESSION['auth_resend_phone_hash'] = hash('sha256', $phone);
    }
}
header('Location: login.php?mode=' . $mode . $returnSuffix); exit;
