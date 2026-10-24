<?php

require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (
    ($_SESSION['auth_backend'] ?? '') !== 'evershop' ||
    empty($_SESSION['backend_access_token'])
) {
    echo json_encode(['authenticated' => false]);
    exit;
}

$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));

try {
    $profile = customer_backend_profile(true);

    if (!$profile) {
        echo json_encode(['authenticated' => false]);
        exit;
    }

    $addresses = $profile['addresses'] ?? [];
    $address = $addresses[0] ?? [];
    foreach ($addresses as $entry) {
        if (!empty($entry['isDefault'])) {
            $address = $entry;
            break;
        }
    }

    $priceValue = static fn($order, $field) => (float) ($order[$field]['value'] ?? 0);
    $formatAddress = static function ($address) {
        if (!is_array($address)) {
            return null;
        }

        return [
            'full_name' => $address['fullName'] ?? '',
            'address_1' => $address['address1'] ?? '',
            'address_2' => $address['address2'] ?? '',
            'city' => $address['city'] ?? '',
            'postcode' => $address['postcode'] ?? '',
            'telephone' => $address['telephone'] ?? '',
            'province' => $address['province']['name'] ?? '',
            'country' => $address['country']['name'] ?? ''
        ];
    };

    $orders = [];
    $paidOrders = [];
    $paidOrdersCount = 0;
    $productsPurchased = 0;

    foreach ($profile['orders'] ?? [] as $order) {
        $status = strtolower(trim((string) (
            $order['paymentStatus']['code']
            ?? $order['payment_status']
            ?? 'pending'
        )));
        $isPaid = in_array($status, [
            'paid', 'captured', 'success', 'succeeded', 'completed', 'complete', 'razorpay_captured'
        ], true);

        $items = array_map(static fn($item) => [
            'name' => $item['productName']
                ?? $item['product_name']
                ?? $item['name']
                ?? 'Product',
            'sku' => $item['productSku'] ?? '',
            'thumbnail' => $item['thumbnail'] ?? '',
            'quantity' => (int) ($item['qty'] ?? $item['quantity'] ?? 1),
            'unit_price' => (float) ($item['productPrice']['value'] ?? 0),
            'line_total' => (float) ($item['lineTotal']['value'] ?? 0)
        ], $order['items'] ?? []);

        if ($isPaid) {
            $paidOrdersCount++;
            // Prefer EverShop's order quantity total, which remains available
            // even when an order has no expanded item rows in the response.
            $productsPurchased += (int) ($order['totalQty'] ?? array_sum(array_column($items, 'quantity')));
        }

        $createdAt = $order['createdAt']['text']
            ?? $order['createdAt']
            ?? $order['created_at']
            ?? '';
        if (is_string($createdAt) && $createdAt !== '') {
            try {
                $createdAt = (new DateTimeImmutable($createdAt, new DateTimeZone('Asia/Kolkata')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata'))
                    ->format('d M Y, h:i A');
            } catch (Throwable $dateError) {
                // Keep the original backend value if it is not a parseable date.
            }
        }

        $shipment = $order['shipment'] ?? [];
        $orderData = [
            'uuid' => $order['uuid'] ?? '',
            'order_id' => $order['orderNumber']
                ?? $order['order_number']
                ?? $order['id']
                ?? '',
            'currency' => $order['currency'] ?? 'INR',
            'amount_subunits' => (int) round(
                $priceValue($order, 'grandTotal') * 100
                ?: (float) ($order['grand_total'] ?? 0) * 100
            ),
            'subtotal' => $priceValue($order, 'subTotal'),
            'shipping_amount' => $priceValue($order, 'shippingFeeInclTax'),
            'discount_amount' => $priceValue($order, 'discountAmount'),
            'tax_amount' => $priceValue($order, 'totalTaxAmount'),
            'total_weight' => $order['totalWeight'] ?? ['value' => 0, 'unit' => 'kg'],
            'quantity' => (int) ($order['totalQty'] ?? array_sum(array_column($items, 'quantity'))),
            'coupon' => $order['coupon'] ?? '',
            'payment_method' => $order['paymentMethodName'] ?? '',
            'shipping_method' => $order['shippingMethodName'] ?? '',
            'status_label' => $order['status']['name'] ?? 'Processing',
            'no_shipping_required' => !empty($order['noShippingRequired']),
            'payment_status' => $isPaid ? 'paid' : $status,
            'payment_status_label' => $order['paymentStatus']['name'] ?? ucfirst($status),
            'shipment_status_label' => $order['shipmentStatus']['name'] ?? 'Pending',
            'created_at' => $createdAt,
            'customer_name' => $order['customerFullName'] ?? $profile['fullName'] ?? '',
            'customer_email' => $order['customerEmail'] ?? $profile['email'] ?? '',
            'shipping_note' => $order['shippingNote'] ?? '',
            'shipment' => [
                'awb' => $shipment['trackingNumber'] ?? '',
                'tracking_url' => $shipment['shiprocketTrackingUrl'] ?? '',
                'courier' => $shipment['shiprocketCourierName'] ?? '',
                'status' => $shipment['shiprocketStatus'] ?? ''
            ],
            'shipping_address' => $formatAddress($order['shippingAddress'] ?? null),
            'billing_address' => $formatAddress($order['billingAddress'] ?? null),
            'items' => $items,
            'activities' => array_map(static fn($activity) => [
                'comment' => $activity['comment'] ?? '',
                'created_at' => $activity['createdAt']['text'] ?? ''
            ], $order['activities'] ?? [])
        ];

        $orders[] = $orderData;
        if ($isPaid) {
            $paidOrders[] = $orderData;
        }
    }

    echo json_encode([
        'authenticated' => true,
        'csrf' => $_SESSION['csrf'],
        'customer' => [
            'name' => $profile['fullName'] ?? '',
            'email' => $profile['email'] ?? '',
            'phone' => $address['telephone'] ?? '',
            'address' => implode(', ', array_filter([
                $address['address1'] ?? '',
                $address['address2'] ?? '',
                $address['city'] ?? '',
                $address['province']['name'] ?? '',
                $address['postcode'] ?? '',
                $address['country']['name'] ?? ''
            ])),
            'address_1' => $address['address1'] ?? '',
            'address_2' => $address['address2'] ?? '',
            'city' => $address['city'] ?? '',
            'state' => $address['province']['name'] ?? '',
            'state_code' => $address['province']['code'] ?? '',
            'postcode' => $address['postcode'] ?? '',
            'country' => $address['country']['code'] ?? 'IN'
        ],
        'orders' => $orders,
        'paid_orders_count' => $paidOrdersCount,
        'products_purchased' => $productsPurchased,
        'paid_orders' => $paidOrders
    ]);
} catch (CustomerBackendException $error) {
    if ($error->getCode() === 401) {
        echo json_encode(['authenticated' => false]);
        exit;
    }

    http_response_code(503);
    echo json_encode(['error' => $error->getMessage()]);
}
