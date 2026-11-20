<?php
/*
 * Applies the site-wide EverShop coupon (NIVIS_SITE_DISCOUNT_COUPON) to carts,
 * so EverShop's own order total, Razorpay and COD all use the discounted price.
 */
require_once __DIR__ . '/backend_config.php';

function site_discount_coupon(): string
{
    return defined('NIVIS_SITE_DISCOUNT_COUPON') ? trim((string) NIVIS_SITE_DISCOUNT_COUPON) : '';
}

function site_discount_is_site_coupon(?string $coupon): bool
{
    $site = site_discount_coupon();
    return $site !== '' && strcasecmp(trim((string) $coupon), $site) === 0;
}

function site_discount_request(string $method, string $path, ?array $payload = null): array
{
    $ch = curl_init(rtrim(EVERSHOP_BASE_URL, '/') . '/api' . $path);
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30,
    ];
    if ($payload !== null) {
        $options[CURLOPT_POSTFIELDS] = json_encode($payload);
    }
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $decoded = json_decode((string) $response, true);
    $ok = $error === '' && $httpCode >= 200 && $httpCode < 300 && empty($decoded['error']);
    return ['ok' => $ok, 'data' => is_array($decoded) ? $decoded : [], 'error' => $error];
}

/** Coupon currently on the EverShop cart ('' when none, null when unknown). */
function site_discount_cart_coupon(string $cartId): ?string
{
    $result = site_discount_request('POST', '/graphql', [
        'query' => 'query CartCoupon($id: String!) { cart(id: $id) { coupon } }',
        'variables' => ['id' => $cartId],
    ]);
    if (!$result['ok'] || !array_key_exists('cart', $result['data']['data'] ?? [])) {
        return null;
    }
    return trim((string) ($result['data']['data']['cart']['coupon'] ?? ''));
}

/**
 * Puts the site coupon on the cart unless the cart already has a coupon.
 * Returns true when the site discount is on the cart afterwards.
 */
function site_discount_apply(string $cartId): bool
{
    $site = site_discount_coupon();
    if ($site === '' || $cartId === '') {
        return false;
    }
    $current = site_discount_cart_coupon($cartId);
    if ($current !== null && $current !== '') {
        return site_discount_is_site_coupon($current);
    }
    $result = site_discount_request('POST', '/carts/' . rawurlencode($cartId) . '/coupons', ['coupon' => $site]);
    if (!$result['ok']) {
        error_log('Site discount coupon "' . $site . '" could not be applied: ' .
            ($result['data']['error']['message'] ?? $result['error'] ?: 'unknown error'));
    }
    return $result['ok'];
}

/** Takes the site coupon off the cart (so a customer coupon can replace it). */
function site_discount_remove(string $cartId): void
{
    $site = site_discount_coupon();
    if ($site === '' || $cartId === '') {
        return;
    }
    if (site_discount_is_site_coupon(site_discount_cart_coupon($cartId))) {
        site_discount_request('DELETE', '/carts/' . rawurlencode($cartId) . '/coupons/' . rawurlencode($site));
    }
}
