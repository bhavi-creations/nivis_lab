<?php
// Minimal GraphQL backend endpoint using webonyx/graphql-php
// Save this file as graphql-endpoint.php in the project root

require __DIR__ . '/vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

// Sample product data - replace this with your real product database or backend data.
$products = [
    [
        'id' => '1',
        'name' => 'Sample Face Serum',
        'price' => 499,
        'imageUrl' => '/assets/img/sample-serum.jpg',
        'description' => 'Example product description.',
    ],
    [
        'id' => '2',
        'name' => 'Hydrating Cleanser',
        'price' => 299,
        'imageUrl' => '/assets/img/sample-cleanser.jpg',
        'description' => 'Example product description.',
    ],
];

$cart = [
    'total' => 0,
    'items' => [],
];

$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => Type::nonNull(Type::id()),
        'name' => Type::string(),
        'price' => Type::int(),
        'imageUrl' => Type::string(),
        'description' => Type::string(),
    ],
]);

$cartItemType = new ObjectType([
    'name' => 'CartItem',
    'fields' => [
        'product' => $productType,
        'quantity' => Type::int(),
    ],
]);

$cartType = new ObjectType([
    'name' => 'Cart',
    'fields' => [
        'total' => Type::int(),
        'items' => Type::listOf($cartItemType),
    ],
]);

$addToCartPayloadType = new ObjectType([
    'name' => 'AddToCartPayload',
    'fields' => [
        'success' => Type::boolean(),
        'message' => Type::string(),
        'cart' => $cartType,
    ],
]);

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'products' => [
            'type' => Type::listOf($productType),
            'resolve' => function () use ($products) {
                return $products;
            },
        ],
        'cart' => [
            'type' => $cartType,
            'resolve' => function () use (&$cart) {
                return $cart;
            },
        ],
    ],
]);

$mutationType = new ObjectType([
    'name' => 'Mutation',
    'fields' => [
        'addToCart' => [
            'type' => $addToCartPayloadType,
            'args' => [
                'productId' => Type::nonNull(Type::id()),
                'quantity' => Type::nonNull(Type::int()),
            ],
            'resolve' => function ($root, $args) use (&$products, &$cart) {
                $product = null;
                foreach ($products as $item) {
                    if ($item['id'] === $args['productId']) {
                        $product = $item;
                        break;
                    }
                }

                if (! $product) {
                    return [
                        'success' => false,
                        'message' => 'Product not found',
                        'cart' => $cart,
                    ];
                }

                $cart['items'][] = [
                    'product' => $product,
                    'quantity' => $args['quantity'],
                ];
                $cart['total'] += $product['price'] * $args['quantity'];

                return [
                    'success' => true,
                    'message' => 'Added to cart',
                    'cart' => $cart,
                ];
            },
        ],
    ],
]);

$schema = new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

$query = isset($input['query']) ? $input['query'] : null;
$variables = isset($input['variables']) ? $input['variables'] : null;

try {
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'errors' => [FormattedError::createFromException($e)],
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
