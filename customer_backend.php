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
    $orderFields = $orders ? 'orders {
        orderNumber uuid currency createdAt customerFullName customerEmail noShippingRequired totalQty coupon shippingMethodName paymentMethodName shippingNote
        subTotal { value } grandTotal { value } shippingFeeInclTax { value } discountAmount { value } totalTaxAmount { value } totalWeight { value unit }
        status { code name } paymentStatus { code name } shipmentStatus { code name }
        shipment { trackingNumber shiprocketTrackingUrl shiprocketCourierName shiprocketStatus }
        items { productName productSku thumbnail qty productPrice { value } lineTotal { value } }
        shippingAddress { fullName address1 address2 city postcode telephone province { name code } country { name code } }
        billingAddress { fullName address1 address2 city postcode telephone province { name code } country { name code } }
        activities { comment createdAt { text } }
    }' : '';
    $data = customer_backend_request('/graphql', ['query' => '{ currentCustomer { customerId uuid email fullName addresses { uuid fullName telephone address1 address2 city postcode province { name code } country { name code } isDefault } ' . $orderFields . ' } }'], true);
    return $data['currentCustomer'] ?? null;
}

function customer_backend_request_method(string $method, string $path, array $payload, bool $authenticated = true, bool $allowRefresh = true): array {
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($authenticated) {
        if (empty($_SESSION['backend_access_token'])) throw new CustomerBackendException('Please log in again.', 401);
        $headers[] = 'Authorization: Bearer ' . $_SESSION['backend_access_token'];
    }
    $ch = curl_init(rtrim(EVERSHOP_API_BASE_URL, '/') . '/' . ltrim($path, '/'));
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ]);
    $raw = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $failed = curl_errno($ch);
    curl_close($ch);
    if ($failed) throw new CustomerBackendException('The account server could not be reached. Please try again.');
    $result = json_decode((string)$raw, true);

    if ($status === 401 && $authenticated && $allowRefresh && !empty($_SESSION['backend_refresh_token'])) {
        $refresh = $_SESSION['backend_refresh_token'];
        try {
            $tokens = customer_backend_request('/customer/token/refresh', ['refreshToken' => $refresh]);
        } catch (Throwable $error) {
            customer_backend_clear_session();
            throw new CustomerBackendException('Your session expired. Please log in again.', 401);
        }
        if (empty($tokens['accessToken'])) {
            customer_backend_clear_session();
            throw new CustomerBackendException('Your session expired. Please log in again.', 401);
        }
        $_SESSION['backend_access_token'] = $tokens['accessToken'];
        $_SESSION['backend_refresh_token'] = $tokens['refreshToken'] ?? $refresh;
        return customer_backend_request_method($method, $path, $payload, $authenticated, false);
    }
    if ($status === 401 && $authenticated) customer_backend_clear_session();
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
