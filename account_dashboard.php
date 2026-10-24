<?php

require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (
    ($_SESSION['auth_backend'] ?? '') !== 'evershop' ||
    empty($_SESSION['backend_access_token'])
) {
    echo json_encode([
        'authenticated' => false
    ]);
    exit;
}

$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));

try {

    $profile = customer_backend_profile(true);

    if (!$profile) {
        echo json_encode([
            'authenticated' => false
        ]);
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

    $orders = [];
    $paidOrders = [];
    $paidOrdersCount = 0;
    $productsPurchased = 0;

    foreach ($profile['orders'] ?? [] as $order) {

        /*
         * Payment status
         */
        $status = strtolower(
            trim(
                (string) (
                    $order['paymentStatus']['code']
                    ?? $order['payment_status']
                    ?? 'pending'
                )
            )
        );

        /*
         * Paid status check
         */
        $isPaid = in_array(
            $status,
            [
                'paid',
                'captured',
                'success',
                'succeeded',
                'completed',
                'complete'
            ],
            true
        );

        /*
         * Order items
         */
        $items = array_map(
            static fn($item) => [
                'name' => $item['productName']
                    ?? $item['product_name']
                    ?? $item['name']
                    ?? 'Product',

                'quantity' => (int) (
                    $item['qty']
                    ?? $item['quantity']
                    ?? 1
                )
            ],
            $order['items'] ?? []
        );

        /*
         * Count purchased products only from paid orders
         */
        if ($isPaid) {

            $paidOrdersCount++;

            foreach ($items as $item) {
                $productsPurchased += (int) ($item['quantity'] ?? 0);
            }
        }

        /*
         * Original order structure
         */
        $orderData = [

            'order_id' =>
                $order['orderNumber']
                ?? $order['order_number']
                ?? $order['id']
                ?? '',

            'amount_subunits' => (int) round(
                (float) (
                    $order['grandTotal']['value']
                    ?? $order['grand_total']
                    ?? 0
                ) * 100
            ),

            'currency' =>
                $order['currency']
                ?? 'INR',

            'payment_status' =>
                $isPaid
                    ? 'paid'
                    : $status,

            'payment_status_label' =>
                $order['paymentStatus']['name']
                ?? ucfirst($status),

            'created_at' =>
                $order['createdAt']['text']
                ?? $order['createdAt']
                ?? $order['created_at']
                ?? '',

            'items' => $items
        ];

        /*
         * All orders
         */
        $orders[] = $orderData;

        /*
         * Paid orders only
         */
        if ($isPaid) {
            $paidOrders[] = $orderData;
        }
    }

    echo json_encode([

        'authenticated' => true,

        'csrf' => $_SESSION['csrf'],

        'customer' => [

            'name' =>
                $profile['fullName']
                ?? '',

            'email' =>
                $profile['email']
                ?? '',

            'phone' =>
                $address['telephone']
                ?? '',

            'address' => implode(
                ', ',
                array_filter([
                    $address['address1'] ?? '',
                    $address['address2'] ?? '',
                    $address['city'] ?? '',
                    $address['province']['name'] ?? '',
                    $address['postcode'] ?? '',
                    $address['country']['name'] ?? ''
                ])
            ),

            'address_1' =>
                $address['address1']
                ?? '',

            'address_2' =>
                $address['address2']
                ?? '',

            'city' =>
                $address['city']
                ?? '',

            'state' =>
                $address['province']['name']
                ?? '',

            'state_code' =>
                $address['province']['code']
                ?? '',

            'postcode' =>
                $address['postcode']
                ?? '',

            'country' =>
                $address['country']['code']
                ?? 'IN'
        ],

        /*
         * Existing orders
         */
        'orders' => $orders,

        /*
         * Added fields
         */
        'paid_orders_count' => $paidOrdersCount,

        'products_purchased' => $productsPurchased,

        'paid_orders' => $paidOrders

    ]);

} catch (CustomerBackendException $error) {

    if ($error->getCode() === 401) {

        echo json_encode([
            'authenticated' => false
        ]);

        exit;
    }

    http_response_code(503);

    echo json_encode([
        'error' => $error->getMessage()
    ]);
}




