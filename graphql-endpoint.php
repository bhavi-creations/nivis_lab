<?php
// GraphQL backend endpoint using webonyx/graphql-php
// This file serves product data and a persistent cart via PHP session.

session_start();
require __DIR__ . '/vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

$products = [
    [
        'id' => 'prod-ceramides-intensive-repair-cream',
        'name' => '1% Ceramides Intensive Repair Cream',
        'price' => 649,
        'imageUrl' => '/assets/img/ceramides-intensive-repair-cream.jpg',
        'description' => 'Helps relieve the symptoms of dry, irritated, eczema-prone skin.',
        'detailPage' => 'ceramides_intensive_repair_cream.php',
        'category' => 'Eczema',
        'concern' => 'eczema',
        'ingredients' => ['Ceramide', 'Panthenol'],
        'type' => 'Moisturizer',
        'relatedProductIds' => ['prod-ceramide-hydrating-cleanser', 'prod-niacinamide-dewy-skin-serum', 'prod-panthenol-hydrating-gel-sunscreen'],
        'relatedContent' => 'Use this cream with the routine cleanser, serum, and sunscreen for barrier repair and calm skin.',
    ],
    [
        'id' => 'prod-alpha-centella-depigmentation-serum',
        'name' => '2% Alpha Centella Depigmentation Serum',
        'price' => 699,
        'imageUrl' => '/assets/img/alpha-centella-serum.jpg',
        'description' => 'Solution for hyperpigmentation, acne marks and uneven skin tone.',
        'detailPage' => 'alpha_centella_aepigmentation_serum.php',
        'category' => 'Pigmentation',
        'concern' => 'pigmentation',
        'ingredients' => ['Centella-Asiatica', 'Niacinamide'],
        'type' => 'Serum',
        'relatedProductIds' => ['prod-vitamin-c-brightening-serum', 'prod-niacinamide-oil-free-moisturizer-serum'],
        'relatedContent' => 'This serum pairs well with brightening and barrier-support products for clearer skin.',
    ],
    [
        'id' => 'prod-vitamin-c-brightening-serum',
        'name' => '10% Vitamin C Brightening Serum',
        'price' => 699,
        'imageUrl' => '/assets/img/vitamin-c-brightening-serum.jpg',
        'description' => 'Solution for dark spots, uneven skin tone, dullness.',
        'detailPage' => 'vitamin_c_brightening_serum.php',
        'category' => 'Brightening',
        'concern' => 'brightening',
        'ingredients' => ['Vitamin C'],
        'type' => 'Serum',
        'relatedProductIds' => ['prod-niacinamide-dewy-skin-serum', 'prod-panthenol-hydrating-gel-sunscreen'],
        'relatedContent' => 'Best used with hydration and sunscreen to protect the brightening results.',
    ],
    [
        'id' => 'prod-pure-retinol-face-serum',
        'name' => '0.3% Pure Retinol Face Serum',
        'price' => 699,
        'imageUrl' => '/assets/img/pure-retinol-face-serum.jpg',
        'description' => 'Solution for visible signs of ageing like fine lines, wrinkles.',
        'detailPage' => 'pure_retinol_face_serum.php',
        'category' => 'Anti-Ageing',
        'concern' => 'anti-ageing',
        'ingredients' => ['Retinol'],
        'type' => 'Serum',
        'relatedProductIds' => ['prod-ceramide-hydrating-cleanser', 'prod-niacinamide-oil-free-moisturizer-serum'],
        'relatedContent' => 'Pair with a gentle cleanser and moisturizer to support retinal skin renewal.',
    ],
    [
        'id' => 'prod-salicylic-acid-anti-acne-serum',
        'name' => '2% Salicylic Acid Anti-Acne Serum',
        'price' => 649,
        'imageUrl' => '/assets/img/salicylic-acid-anti-acne-serum.jpg',
        'description' => 'Solution for acne, clogged pores and sebum regulation.',
        'detailPage' => 'salicylic_acid_anti_acne_serum.php',
        'category' => 'Acne',
        'concern' => 'acne',
        'ingredients' => ['Salicylic-Acid'],
        'type' => 'Serum',
        'relatedProductIds' => ['prod-ceramide-hydrating-cleanser', 'prod-niacinamide-oil-free-moisturizer-serum'],
        'relatedContent' => 'Use this with a calming cleanser and moisturizer for active acne control.',
    ],
    [
        'id' => 'prod-salicylic-acid-acne-spot-gel',
        'name' => 'Salicylic Acid Acne Spot Treatment Gel',
        'price' => 495,
        'imageUrl' => '/assets/img/salicylic-acid-acne-spot-gel.jpg',
        'description' => 'Solution for rapid healing of acne, pimples and breakouts.',
        'detailPage' => 'salicylic_acid_acne_spot_treatment_gel.php',
        'category' => 'Acne',
        'concern' => 'acne',
        'ingredients' => ['Salicylic-Acid'],
        'type' => 'Spot Treatment',
        'relatedProductIds' => ['prod-salicylic-acid-anti-acne-serum', 'prod-ceramide-hydrating-cleanser'],
        'relatedContent' => 'Targets individual spots while supporting your acne-fighting routine.',
    ],
    [
        'id' => 'prod-niacinamide-dewy-skin-serum',
        'name' => '2% Niacinamide Acid Dewy Skin Serum',
        'price' => 649,
        'imageUrl' => '/assets/img/niacinamide-dewy-skin-serum.jpg',
        'description' => 'Solution for compromised skin barrier, dull, dry and dehydrated skin.',
        'detailPage' => 'niacinamide_oil_free_moisturizer_serum.php',
        'category' => 'Hydration',
        'concern' => 'hydration',
        'ingredients' => ['Niacinamide'],
        'type' => 'Serum',
        'relatedProductIds' => ['prod-ceramide-hydrating-cleanser', 'prod-panthenol-hydrating-gel-sunscreen'],
        'relatedContent' => 'A hydrating partner for dry or sensitized skin, especially after actives.',
    ],
    [
        'id' => 'prod-ceramide-hydrating-cleanser',
        'name' => 'Ceramide Hydrating Cleanser',
        'price' => 315,
        'imageUrl' => '/assets/img/ceramide-hydrating-cleanser.jpg',
        'description' => 'Gentle, barrier-supporting cleanser that preserves moisture while cleansing.',
        'detailPage' => 'ceramide_hydrating_cleanser.php',
        'category' => 'Cleanser',
        'concern' => 'sensitive',
        'ingredients' => ['Ceramide'],
        'type' => 'Cleanser',
        'relatedProductIds' => ['prod-ceramides-intensive-repair-cream', 'prod-niacinamide-dewy-skin-serum'],
        'relatedContent' => 'A gentle start to your routine that keeps the skin barrier intact.',
    ],
    [
        'id' => 'prod-niacinamide-oil-free-moisturizer-serum',
        'name' => 'Niacinamide Oil Free Moisturizer Serum',
        'price' => 549,
        'imageUrl' => '/assets/img/niacinamide-oil-free-moisturizer-serum.jpg',
        'description' => 'Lightweight moisturizer serum for oily to combination skin.',
        'detailPage' => 'niacinamide_oil_free_moisturizer_serum.php',
        'category' => 'Moisturizer',
        'concern' => 'oil-control',
        'ingredients' => ['Niacinamide'],
        'type' => 'Moisturizer',
        'relatedProductIds' => ['prod-panthenol-hydrating-gel-sunscreen', 'prod-ceramide-hydrating-cleanser'],
        'relatedContent' => 'Pairs well with serums and sunscreen for balanced hydration.',
    ],
    [
        'id' => 'prod-panthenol-hydrating-gel-sunscreen',
        'name' => 'Panthenol Hydrating Gel Sunscreen',
        'price' => 599,
        'imageUrl' => '/assets/img/panthenol-hydrating-gel-sunscreen.jpg',
        'description' => 'Lightweight sunscreen with hydration and barrier support.',
        'detailPage' => 'panthenol_hydrating_gel_sunscreen_spf_60.php',
        'category' => 'Sunscreen',
        'concern' => 'sun-protection',
        'ingredients' => ['Panthenol'],
        'type' => 'Sunscreen',
        'relatedProductIds' => ['prod-ceramide-hydrating-cleanser', 'prod-niacinamide-oil-free-moisturizer-serum'],
        'relatedContent' => 'Use this last in your routine for daily SPF and hydration.',
    ],
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'total' => 0,
        'items' => [],
    ];
}

$cart = &$_SESSION['cart'];

$productType = null;
$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => Type::nonNull(Type::id()),
        'name' => Type::string(),
        'price' => Type::int(),
        'imageUrl' => Type::string(),
        'description' => Type::string(),
        'detailPage' => Type::string(),
        'category' => Type::string(),
        'concern' => Type::string(),
        'ingredients' => Type::listOf(Type::string()),
        'type' => Type::string(),
        'relatedContent' => Type::string(),
        'relatedProductIds' => Type::listOf(Type::id()),
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
            'args' => [
                'category' => Type::string(),
                'concern' => Type::string(),
                'ingredient' => Type::string(),
                'type' => Type::string(),
            ],
            'resolve' => function ($root, $args) use ($products) {
                return array_values(array_filter($products, function ($product) use ($args) {
                    if (!empty($args['category']) && strcasecmp($product['category'], $args['category']) !== 0) {
                        return false;
                    }
                    if (!empty($args['concern']) && strcasecmp($product['concern'], $args['concern']) !== 0) {
                        return false;
                    }
                    if (!empty($args['type']) && strcasecmp($product['type'], $args['type']) !== 0) {
                        return false;
                    }
                    if (!empty($args['ingredient'])) {
                        return in_array($args['ingredient'], $product['ingredients'], true);
                    }
                    return true;
                }));
            },
        ],
        'product' => [
            'type' => $productType,
            'args' => ['id' => Type::nonNull(Type::id())],
            'resolve' => function ($root, $args) use ($products) {
                foreach ($products as $product) {
                    if ($product['id'] === $args['id']) {
                        return $product;
                    }
                }
                return null;
            },
        ],
        'relatedProducts' => [
            'type' => Type::listOf($productType),
            'args' => ['productId' => Type::nonNull(Type::id())],
            'resolve' => function ($root, $args) use ($products) {
                $base = null;
                foreach ($products as $product) {
                    if ($product['id'] === $args['productId']) {
                        $base = $product;
                        break;
                    }
                }
                if (!$base || empty($base['relatedProductIds'])) {
                    return [];
                }
                return array_values(array_filter($products, fn($item) => in_array($item['id'], $base['relatedProductIds'], true)));
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
            'resolve' => function ($root, $args) use (&$products, &$cart) {
                $product = null;
                foreach ($products as $item) {
                    if ($item['id'] === $args['productId']) {
                        $product = $item;
                        break;
                    }
                }

                if (!$product) {
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
