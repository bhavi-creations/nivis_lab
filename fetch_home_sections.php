<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiGraphqlUrl = "https://admin.nivislabs.in/api/graphql";

function graphqlRequest($url, $query, $variables = [])
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            "query" => $query,
            "variables" => (object) $variables
        ]),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_CONNECTTIMEOUT_MS => 1000,
        CURLOPT_TIMEOUT_MS => 3000
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => $error];
    }

    curl_close($ch);
    $decoded = json_decode($response, true);

    if (!is_array($decoded)) {
        return ["error" => "Invalid JSON from GraphQL server", "raw" => $response];
    }

    return $decoded;
}

function normalizeImageUrl($url)
{
    if (empty($url)) {
        return null;
    }

    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    return "http://localhost:3000" . $url;
}

function slugify($value)
{
    $value = strtolower(trim($value ?? ""));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function normalizeProduct($product)
{
    $price = $product["price"]["regular"]["text"] ?? $product["price"] ?? "";

    return [
        "productId" => $product["id"] ?? $product["productId"] ?? null,
        "name" => $product["name"] ?? "Product Name",
        "urlKey" => $product["url_key"] ?? $product["urlKey"] ?? null,
        "link" => $product["link"] ?? null,
        "price" => $price,
        "originalPrice" => $product["originalPrice"] ?? $price,
        "imageUrl" => normalizeImageUrl($product["image"]["url"] ?? $product["primaryImage"] ?? null),
        "concern" => $product["concern"] ?? "Skincare",
        "reviewsCount" => $product["reviewsCount"] ?? 120,
        "stars" => $product["stars"] ?? "★★★★★"
    ];
}

function fetchProductsByCategory($slug, $apiGraphqlUrl)
{
    if (empty($slug)) {
        return [];
    }

    $productsByCategoryQuery = '
        query GetProductsByCategory($categorySlug: String!) {
            productsByCategory(categorySlug: $categorySlug) {
                id
                name
                subtitle
                price
                originalPrice
                reviewsCount
                stars
                boughtTag
                primaryImage
                secondaryImage
                concern
                ingredient
                type
                link
            }
        }
    ';

    $result = graphqlRequest($apiGraphqlUrl, $productsByCategoryQuery, ["categorySlug" => $slug]);
    $items = $result["data"]["productsByCategory"] ?? null;

    if (is_array($items)) {
        return array_map("normalizeProduct", $items);
    }

    $safeSlug = addslashes($slug);
    $productsQuery = '
        {
            products(filters: [{ key: "category", operation: "eq", value: "' . $safeSlug . '" }]) {
                items {
                    id
                    name
                    url_key
                    price { regular { text } }
                    image { url }
                    category
                    concern
                }
            }
        }
    ';

    $result = graphqlRequest($apiGraphqlUrl, $productsQuery);
    $items = $result["data"]["products"]["items"] ?? [];

    return is_array($items) ? array_map("normalizeProduct", $items) : [];
}

$categoriesQuery = '{
    categories {
        items {
            id
            name
            url_key
            products {
                items {
                    id
                    name
                    url_key
                    price { regular { text } }
                    image { url }
                    concern
                }
            }
        }
    }
}';

$result = graphqlRequest($apiGraphqlUrl, $categoriesQuery);

if (!empty($result["errors"])) {
    $categoriesQuery = '{
        categories {
            items {
                categoryId
                name
                urlKey
                products {
                    items {
                        productId
                        name
                        urlKey
                        price { regular { text } }
                        image { url }
                        concern
                    }
                }
            }
        }
    }';

    $result = graphqlRequest($apiGraphqlUrl, $categoriesQuery);
}

if (!empty($result["error"]) || !empty($result["errors"])) {
    echo json_encode($result);
    exit;
}

$categories = $result["data"]["categories"]["items"] ?? [];
$normalizedCategories = [];

foreach ($categories as $category) {
    $slug = $category["url_key"] ?? $category["urlKey"] ?? slugify($category["name"] ?? "");
    $nestedProducts = $category["products"]["items"] ?? [];
    $products = is_array($nestedProducts) ? array_map("normalizeProduct", $nestedProducts) : [];

    if (count($products) === 0) {
        $products = fetchProductsByCategory($slug, $apiGraphqlUrl);
    }

    $normalizedCategories[] = [
        "categoryId" => $category["id"] ?? $category["categoryId"] ?? null,
        "name" => $category["name"] ?? "Category",
        "urlKey" => $slug,
        "products" => [
            "items" => $products
        ]
    ];
}

echo json_encode([
    "data" => [
        "categories" => [
            "items" => $normalizedCategories
        ]
    ]
]);
