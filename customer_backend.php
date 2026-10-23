<?php
require_once __DIR__ . '/backend_config.php';
class CustomerBackendException extends RuntimeException {}
function customer_backend_request(string $path, array $payload, bool $authenticated = false): array {
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($authenticated) {
        if (empty($_SESSION['backend_access_token'])) throw new CustomerBackendException('Please log in again.', 401);
        $headers[] = 'Authorization: Bearer ' . $_SESSION['backend_access_token'];
    }
    $ch = curl_init(rtrim(EVERSHOP_API_BASE_URL, '/') . '/' . ltrim($path, '/'));
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload), CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 30]);
    $raw = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $failed = curl_errno($ch);
    curl_close($ch);
    if ($failed) throw new CustomerBackendException('The account server could not be reached. Please try again.');
    $result = json_decode((string)$raw, true);
    if ($status === 401 && $authenticated && !empty($_SESSION['backend_refresh_token'])) {
        $refresh = $_SESSION['backend_refresh_token'];
        unset($_SESSION['backend_refresh_token']);
        try {
            $tokens = customer_backend_request('/customer/token/refresh', ['refreshToken' => $refresh]);
            $_SESSION['backend_access_token'] = $tokens['accessToken'];
            // Retry once. Do not restore the refresh token until the retry succeeds.
            $data = customer_backend_request($path, $payload, true);
            $_SESSION['backend_refresh_token'] = $refresh;
            return $data;
        } catch (Throwable $error) {
            customer_backend_clear_session();
            throw new CustomerBackendException('Your session expired. Please log in again.', 401);
        }
    }
    if ($status === 401 && $authenticated) customer_backend_clear_session();
    if (!is_array($result) || $status < 200 || $status >= 300 || !empty($result['error']) || !empty($result['errors'])) {
        $message = $result['error']['message'] ?? 'The account server could not complete this request. Please try again.';
        throw new CustomerBackendException(is_string($message) ? $message : 'Account request failed.', $status);
    }
    return $result['data'] ?? $result;
}
function customer_backend_profile(bool $orders = false): ?array {
    $orderFields = $orders ? 'orders { orderNumber uuid currency grandTotal { value } createdAt { text } paymentStatus { code name } items { productName qty } }' : '';
    $data = customer_backend_request('/graphql', ['query' => '{ currentCustomer { customerId uuid email fullName addresses { uuid fullName telephone address1 address2 city postcode province { name code } country { name code } isDefault } ' . $orderFields . ' } }'], true);
    return $data['currentCustomer'] ?? null;
}

/** Update the signed-in customer's profile and default shipping address. */
function customer_backend_update_profile(array $input): array {
    $profile = customer_backend_profile();
    if (!$profile || (empty($profile['customerId']) && empty($profile['uuid']))) throw new CustomerBackendException('Please log in again.', 401);
    $name = trim((string)($input['full_name'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    if ($name === '' || strlen($name) > 120) throw new CustomerBackendException('Enter a name up to 120 characters.');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) throw new CustomerBackendException('Enter a valid email address.');

    $phone = trim((string)($input['phone'] ?? ''));
    $address1 = trim((string)($input['address_1'] ?? ''));
    $address2 = trim((string)($input['address_2'] ?? ''));
    $city = trim((string)($input['city'] ?? ''));
    $state = trim((string)($input['state'] ?? ''));
    $postcode = trim((string)($input['postcode'] ?? ''));
    $country = strtoupper(trim((string)($input['country'] ?? '')));
    $password = (string)($input['password'] ?? '');
    if (!preg_match('/^\\+?[0-9]{10,15}$/D', $phone) || $address1 === '' || strlen($address1) > 255 || strlen($address2) > 255 || $city === '' || strlen($city) > 120 || $state === '' || strlen($state) > 120 || $postcode === '' || strlen($postcode) > 20 || !preg_match('/^[A-Z]{2}$/D', $country) || ($password !== '' && (strlen($password) < 8 || strlen($password) > 128))) {
        throw new CustomerBackendException('Enter valid name, email, phone, shipping address and country details. A new password must contain at least 8 characters.');
    }

    // Customer ID comes from the authenticated profile, never the submitted form.
    $customerId = (string)($profile['uuid'] ?? $profile['customerId']);
    $payload = ['full_name' => $name, 'email' => $email];
    if ($password !== '') $payload['password'] = $password;
    $id = rawurlencode($customerId);
    customer_backend_admin_request_method('PATCH', '/customers/' . $id, $payload);

    $countryData = json_decode((string)file_get_contents(__DIR__ . '/assets/customer-countries.json'), true);
    $countryEntry = null;
    foreach ($countryData['data']['countries'] ?? [] as $entry) if (($entry['code'] ?? '') === $country) { $countryEntry = $entry; break; }
    $provinceName = $state;
    foreach ($countryEntry['provinces'] ?? [] as $entry) if (($entry['code'] ?? '') === $state) { $provinceName = $entry['name']; break; }
    $address = [
        'full_name' => $name,
        'telephone' => $phone,
        'address_1' => $address1,
        'address_2' => $address2,
        'city' => $city,
        'province' => $provinceName,
        'postcode' => $postcode,
        'country' => $country,
        'is_default' => true,
    ];
    $savedAddress = null;
    foreach ($profile['addresses'] ?? [] as $candidate) if (!empty($candidate['isDefault'])) { $savedAddress = $candidate; break; }
    if (!$savedAddress && !empty($profile['addresses'][0])) $savedAddress = $profile['addresses'][0];
    if ($savedAddress && !empty($savedAddress['uuid'])) {
        $address['uuid'] = $savedAddress['uuid'];
        customer_backend_admin_request_method('PATCH', '/customers/' . $id . '/addresses/' . rawurlencode($address['uuid']), $address);
    } else {
        customer_backend_admin_request_method('POST', '/customers/' . $id . '/addresses', $address);
    }
    return customer_backend_profile() ?? [];
}

function customer_backend_admin_request_method(string $method, string $path, array $payload): array {
    $token = trim((string)(getenv('EVERSHOP_ADMIN_API_TOKEN') ?: ''));
    if ($token === '') throw new CustomerBackendException('Account editing is not enabled yet. The store backend needs EVERSHOP_ADMIN_API_TOKEN configured securely.');
    $ch = curl_init(rtrim(EVERSHOP_API_BASE_URL, '/') . '/' . ltrim($path, '/'));
    curl_setopt_array($ch, [CURLOPT_CUSTOMREQUEST => strtoupper($method), CURLOPT_POSTFIELDS => json_encode($payload), CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json', 'Authorization: Bearer ' . $token], CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 30]);
    $raw = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $failed = curl_errno($ch);
    curl_close($ch);
    if ($failed) throw new CustomerBackendException('The account server could not be reached. Please try again.');
    $result = json_decode((string)$raw, true);
    if (!is_array($result) || $status < 200 || $status >= 300 || !empty($result['error']) || !empty($result['errors'])) {
        $message = $result['error']['message'] ?? 'The account server could not complete this request. Please try again.';
        throw new CustomerBackendException(is_string($message) ? $message : 'Account request failed.', $status);
    }
    return $result['data'] ?? $result;
}
function customer_backend_login(string $email, string $password): array {
    $tokens = customer_backend_request('/customer/tokens', ['email'=>$email, 'password'=>$password]);
    if (empty($tokens['accessToken'])) throw new CustomerBackendException('Invalid email or password.');
    $_SESSION['backend_access_token'] = $tokens['accessToken'];
    $_SESSION['backend_refresh_token'] = $tokens['refreshToken'] ?? null;
    try {
        $customer = customer_backend_profile();
        if (!$customer) throw new CustomerBackendException('Invalid email or password.');
        return $customer;
    } catch (Throwable $error) {
        unset($_SESSION['backend_access_token'], $_SESSION['backend_refresh_token']);
        throw $error;
    }
}

function customer_backend_reset_password(string $token, string $password): bool {
    customer_backend_request('/customers/password', ['token'=>$token,'password'=>$password]);
    return true;
}

function customer_backend_clear_session(): void {
    unset($_SESSION['backend_access_token'], $_SESSION['backend_refresh_token'], $_SESSION['auth_backend'], $_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['logged_in'], $_SESSION['website_login']);
}
