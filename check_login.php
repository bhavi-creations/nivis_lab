<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], customer_value('csrf'))) {
    $_SESSION['auth_error'] = 'Your session expired. Please try again.';
    header('Location: login.php?mode=login' . (customer_value('return') === 'checkout.php' ? '&return=checkout.php' : '')); exit;
}
$mode = customer_value('mode') === 'signup' ? 'signup' : 'login';
$email = strtolower(customer_value('email') ?: customer_value('username'));
$name = customer_value('name'); $address = customer_value('address'); $phone = customer_value('phone');
$country = strtoupper(customer_value('country')); $province = customer_value('province'); $postcode = customer_value('postcode');
$_SESSION['auth_old'] = compact('email','name','address','phone','country','province','postcode');
try {
    if (($_SESSION['login_attempts'] ?? 0) >= 5 && time() - ($_SESSION['login_attempt_time'] ?? 0) < 300) throw new RuntimeException('Too many attempts. Please try again in five minutes.');
    if (time() - ($_SESSION['login_attempt_time'] ?? 0) >= 300) $_SESSION['login_attempts'] = 0;
    if ($mode === 'signup') {
        if ($name === '' || strlen($name) > 120 || $address === '' || strlen($address) > 255 || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\+?[0-9]{10,15}$/D', $phone) || !preg_match('/^[A-Z]{2}$/D', $country) || $province === '' || strlen($province) > 100 || $postcode === '' || strlen($postcode) > 20) throw new RuntimeException('Enter your name, full address, country, state, PIN code, valid email and phone number.');
        $countries = json_decode(file_get_contents(__DIR__ . '/assets/customer-countries.json'), true)['data']['countries'];
        $selectedCountry = null;
        foreach ($countries as $entry) if ($entry['code'] === $country) { $selectedCountry=$entry; break; }
        $stateCodes = array_column($selectedCountry['provinces'] ?? [], 'code');
        if (!$selectedCountry || ($stateCodes && !in_array($province,$stateCodes,true))) throw new RuntimeException('Please select a valid country and state.');
        customer_backend_request('/customers', ['full_name'=>$name,'email'=>$email,'password'=>$phone]);
        $customer = customer_backend_login($email, $phone);
        customer_login(['id'=>$customer['customerId'],'name'=>$customer['fullName']]);
        $_SESSION['customer_email'] = $customer['email'];
        $_SESSION['auth_backend'] = 'evershop';
        $_SESSION['auth_notice'] = 'Account created in Nivis Labs. Your email is your username and your phone number is your initial password.';
        try {
            customer_backend_admin_request_method('POST', '/customers/' . rawurlencode($customer['uuid']) . '/addresses', ['full_name'=>$name,'address_1'=>$address,'telephone'=>$phone,'country'=>$country,'province'=>$province,'postcode'=>$postcode,'is_default'=>1]);
        } catch (Throwable $error) {
            $_SESSION['auth_notice'] .= ' Your account was created, but the backend could not save your address. Please add it in your backend account address book.';
        }
        try { customer_welcome($name,$email,$phone); $_SESSION['auth_notice'] .= ' Your login credentials have been emailed to you.'; }
        catch (Throwable $error) { $_SESSION['auth_notice'] .= ' The welcome email could not be sent; you can still log in.'; }
    } else {
        try { $customer = customer_backend_login($email, customer_value('password')); }
        catch (CustomerBackendException $error) {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['login_attempt_time'] = time();
            throw $error;
        }
        customer_login(['id'=>$customer['customerId'],'name'=>$customer['fullName']]);
        $_SESSION['customer_email'] = $customer['email'];
        $_SESSION['auth_backend'] = 'evershop';
        unset($_SESSION['login_attempts'], $_SESSION['login_attempt_time']);
    }
    unset($_SESSION['auth_old']);
    if (customer_value('return') === 'checkout.php') {
        unset($_SESSION['auth_notice']);
        header('Location: checkout.php'); exit;
    }
    header('Location: login.php'); exit;
} catch (RuntimeException $error) {
    $_SESSION['auth_error'] = $error->getMessage();
}
header('Location: login.php?mode=' . $mode . (customer_value('return') === 'checkout.php' ? '&return=checkout.php' : '')); exit;
