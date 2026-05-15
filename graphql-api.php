<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();

// Load Configuration
require_once __DIR__ . '/config.php';

try {
    require __DIR__ . '/vendor/autoload.php';
    
    use GraphQL\GraphQL;
    use GraphQL\Error\FormattedError;
    use GraphQL\Type\Definition\ObjectType;
    use GraphQL\Type\Definition\Type;
    use GraphQL\Type\Schema;

    // Sample Products
    $products = [
        [
            'id' => '1',
            'name' => 'Ceramides Intensive Repair Cream',
            'price' => 649,
            'imageUrl' => 'https://via.placeholder.com/250x250?text=Ceramides',
            'description' => 'Helps relieve dry, irritated, eczema-prone skin.',
            'category' => 'Eczema',
            'concern' => 'eczema',
        ],
        [
            'id' => '2',
            'name' => 'Vitamin C Brightening Serum',
            'price' => 699,
            'imageUrl' => 'https://via.placeholder.com/250x250?text=Vitamin+C',
            'description' => 'Solution for dark spots and uneven skin tone.',
            'category' => 'Brightening',
            'concern' => 'brightening',
        ],
        [
            'id' => '3',
            'name' => 'Pure Retinol Face Serum',
            'price' => 699,
            'imageUrl' => 'https://via.placeholder.com/250x250?text=Retinol',
            'description' => 'Solution for fine lines and wrinkles.',
            'category' => 'Anti-Ageing',
            'concern' => 'anti-ageing',
        ],
    ];

    // Initialize cart in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [
            'total' => 0,
            'items' => [],
        ];
    }

    // Define GraphQL Types
    $productType = new ObjectType([
        'name' => 'Product',
        'fields' => [
            'id' => Type::nonNull(Type::id()),
            'name' => Type::string(),
            'price' => Type::int(),
            'imageUrl' => Type::string(),
            'description' => Type::string(),
            'category' => Type::string(),
            'concern' => Type::string(),
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

    $authPayloadType = new ObjectType([
        'name' => 'AuthPayload',
        'fields' => [
            'success' => Type::boolean(),
            'message' => Type::string(),
            'isLoggedIn' => Type::boolean(),
        ],
    ]);

    $authStatusType = new ObjectType([
        'name' => 'AuthStatus',
        'fields' => [
            'isLoggedIn' => Type::boolean(),
            'username' => Type::string(),
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

    // Query Type
    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'authStatus' => [
                'type' => $authStatusType,
                'resolve' => function () {
                    return [
                        'isLoggedIn' => isset($_SESSION['website_login']) && $_SESSION['website_login'] === true,
                        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null,
                    ];
                },
            ],
            'products' => [
                'type' => Type::listOf($productType),
                'resolve' => function () use ($products) {
                    return $products;
                },
            ],
            'product' => [
                'type' => $productType,
                'args' => ['id' => Type::nonNull(Type::id())],
                'resolve' => function ($root, $args) use ($products) {
                    foreach ($products as $product) {
                        if ((string)$product['id'] === (string)$args['id']) {
                            return $product;
                        }
                    }
                    return null;
                },
            ],
            'cart' => [
                'type' => $cartType,
                'resolve' => function () {
                    return $_SESSION['cart'];
                },
            ],
        ],
    ]);

    // Mutation Type
    $mutationType = new ObjectType([
        'name' => 'Mutation',
        'fields' => [
            'login' => [
                'type' => $authPayloadType,
                'args' => [
                    'username' => Type::nonNull(Type::string()),
                    'password' => Type::nonNull(Type::string()),
                ],
                'resolve' => function ($root, $args) {
                    $correct_username = 'nivis';
                    $correct_password = 'nivislabs@123';

                    if ($args['username'] === $correct_username && $args['password'] === $correct_password) {
                        $_SESSION['website_login'] = true;
                        $_SESSION['username'] = $args['username'];
                        return [
                            'success' => true,
                            'message' => 'Login successful',
                            'isLoggedIn' => true,
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => 'Invalid username or password',
                        'isLoggedIn' => false,
                    ];
                },
            ],
            'logout' => [
                'type' => $authPayloadType,
                'resolve' => function () {
                    $_SESSION['website_login'] = false;
                    $_SESSION['username'] = null;
                    session_destroy();
                    return [
                        'success' => true,
                        'message' => 'Logged out successfully',
                        'isLoggedIn' => false,
                    ];
                },
            ],
            'addToCart' => [
                'type' => $addToCartPayloadType,
                'args' => [
                    'productId' => Type::nonNull(Type::id()),
                    'quantity' => Type::nonNull(Type::int()),
                ],
                'resolve' => function ($root, $args) use ($products) {
                    $product = null;
                    foreach ($products as $item) {
                        if ((string)$item['id'] === (string)$args['productId']) {
                            $product = $item;
                            break;
                        }
                    }

                    if (!$product) {
                        return [
                            'success' => false,
                            'message' => 'Product not found',
                            'cart' => $_SESSION['cart'],
                        ];
                    }

                    $_SESSION['cart']['items'][] = [
                        'product' => $product,
                        'quantity' => $args['quantity'],
                    ];
                    $_SESSION['cart']['total'] += $product['price'] * $args['quantity'];

                    return [
                        'success' => true,
                        'message' => 'Added to cart',
                        'cart' => $_SESSION['cart'],
                    ];
                },
            ],
        ],
    ]);

    // Create Schema
    $schema = new Schema([
        'query' => $queryType,
        'mutation' => $mutationType,
    ]);

    // Get GraphQL Input
    $input = json_decode(file_get_contents('php://input'), true);
    $query = $input['query'] ?? null;
    $variables = $input['variables'] ?? null;

    // Execute Query
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();

} catch (\Throwable $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
        ]
    ];
}

echo json_encode($output);
exit;
?>
