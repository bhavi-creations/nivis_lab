<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$graphqlUrl = "http://localhost:3000/api/graphql";
$category = trim($_GET["category"] ?? "");

function slugifyValue($value)
{
    $value = strtolower(trim($value ?? ""));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function graphqlRequest($query)
{
    global $graphqlUrl;

    $ch = curl_init($graphqlUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["query" => $query]),
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
    if (!$url) {
        return "./assets/img/logo.jpeg";
    }

    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    return "http://localhost:3000" . $url;
}

function normalizeText($value)
{
    if (is_object($value)) {
        $value = (array) $value;
    }

    if (is_array($value)) {
        return trim($value["name"] ?? $value["value"] ?? $value["label"] ?? implode(" ", array_map("normalizeText", $value)));
    }

    return trim((string) $value);
}

function normalizeProduct($product)
{
    $price = $product["price"]["regular"]["text"] ?? "₹0";
    $numericPrice = (float) preg_replace('/[^0-9.]/', '', (string) $price);
    $productSlug = $product["url_key"] ?? "";
    $categoryName = normalizeText($product["category"] ?? "");

    return [
        "id" => $product["id"] ?? $product["sku"] ?? $product["name"] ?? null,
        "name" => normalizeText($product["name"] ?? "Product Name"),
        "subtitle" => normalizeText($product["description"] ?? $categoryName),
        "price" => $price,
        "priceNumber" => (int) round($numericPrice),
        "imageUrl" => normalizeImageUrl($product["image"]["url"] ?? null),
        "secondaryImageUrl" => normalizeImageUrl($product["image"]["url"] ?? null),
        "concern" => $categoryName ?: "Skincare",
        "ingredient" => $categoryName ?: "skincare",
        "type" => $categoryName ?: "skincare",
        "stars" => "★★★★½",
        "reviewsCount" => "120",
        "boughtTag" => "196+ bought in past month",
        "link" => $productSlug ? $productSlug . ".php" : "#"
    ];
}

if ($category === "") {
    echo json_encode(["error" => "Missing category"]);
    exit;
}

$categorySlug = slugifyValue($category);

$query = '{
    products(filters: []) {
        items {
            id
            sku
            name
            url_key
            price { regular { text } }
            image { url }
            category { name }
        }
    }
}';

$result = graphqlRequest($query);

if (!empty($result["error"]) || !empty($result["errors"])) {
    echo json_encode($result);
    exit;
}

$allProducts = $result["data"]["products"]["items"] ?? [];
$products = array_values(array_filter($allProducts, function ($product) use ($categorySlug) {
    $productCategory = normalizeText($product["category"] ?? "");
    return slugifyValue($productCategory) === $categorySlug;
}));

$categoryName = $category;

if (count($products) > 0) {
    $categoryName = normalizeText($products[0]["category"] ?? $category);
}

echo json_encode([
    "category" => [
        "id" => null,
        "name" => $categoryName,
        "slug" => $categorySlug
    ],
    "products" => array_map("normalizeProduct", $products)
]);
