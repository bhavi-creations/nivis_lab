<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$graphqlUrl = "http://localhost:3000/api/graphql";
$category = trim($_GET["category"] ?? "");
$cacheTtl = 60;

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
        CURLOPT_CONNECTTIMEOUT_MS => 2000,
        CURLOPT_TIMEOUT_MS => 10000
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

function attributeValue($attributes, $keywords, $fallback)
{
    foreach (($attributes ?? []) as $attribute) {
        $code = strtolower($attribute["attributeCode"] ?? "");
        $name = strtolower($attribute["attributeName"] ?? "");
        $value = normalizeText($attribute["optionText"] ?? "");

        if (!$value) {
            continue;
        }

        foreach ($keywords as $keyword) {
            if (str_contains($code, $keyword) || str_contains($name, $keyword)) {
                return $value;
            }
        }
    }

    return $fallback;
}

function normalizeAttributes($attributes)
{
    $items = [];

    foreach (($attributes ?? []) as $attribute) {
        $name = normalizeText($attribute["attributeName"] ?? $attribute["attributeCode"] ?? "");
        $value = normalizeText($attribute["optionText"] ?? "");

        if ($name && $value) {
            $items[] = [
                "name" => $name,
                "value" => $value
            ];
        }
    }

    return $items;
}

function normalizeProduct($product)
{
    $price = $product["price"]["regular"]["text"] ?? "₹0";
    $numericPrice = (float) preg_replace('/[^0-9.]/', '', (string) $price);
    $productSlug = $product["urlKey"] ?? $product["url_key"] ?? "";
    $categoryName = normalizeText($product["category"] ?? "");
    $attributes = $product["attributeIndex"] ?? [];
    $primaryImage = $product["image"]["url"] ?? null;
    $gallery = is_array($product["gallery"] ?? null) ? $product["gallery"] : [];
    $secondaryImage = $primaryImage;

    foreach ($gallery as $image) {
        $galleryUrl = $image["url"] ?? null;
        if ($galleryUrl && $galleryUrl !== $primaryImage) {
            $secondaryImage = $galleryUrl;
            break;
        }
    }

    $concern = attributeValue($attributes, ["concern", "skin_concern", "skin concern"], $categoryName ?: "Skincare");
    $ingredient = attributeValue($attributes, ["ingredient", "ingredients", "key_ingredient", "key ingredient"], $categoryName ?: "skincare");
    $productType = attributeValue($attributes, ["product_type", "product type", "type"], $categoryName ?: "Product");

    return [
        "id" => $product["id"] ?? $product["sku"] ?? $product["name"] ?? null,
        "sku" => normalizeText($product["sku"] ?? ""),
        "urlKey" => normalizeText($productSlug),
        "name" => normalizeText($product["name"] ?? "Product Name"),
        "subtitle" => normalizeText($product["description"] ?? $categoryName),
        "price" => $price,
        "priceNumber" => (int) round($numericPrice),
        "imageUrl" => normalizeImageUrl($primaryImage),
        "secondaryImageUrl" => normalizeImageUrl($secondaryImage),
        "concern" => $concern,
        "ingredient" => $ingredient,
        "type" => $productType,
        "stars" => "★★★★½",
        "reviewsCount" => "120",
        "boughtTag" => "196+ bought in past month",
        "link" => $productSlug ? $productSlug . ".php" : "#",
        "category" => $categoryName,
        "attributes" => normalizeAttributes($attributes),
        "details" => [
            ["label" => "SKU", "value" => normalizeText($product["sku"] ?? "")],
            ["label" => "Category", "value" => $categoryName],
            ["label" => "Skin Concern", "value" => $concern],
            ["label" => "Ingredient", "value" => $ingredient],
            ["label" => "Product Type", "value" => $productType],
            ["label" => "URL Key", "value" => normalizeText($productSlug)],
            ["label" => "Price", "value" => $price]
        ]
    ];
}

if ($category === "") {
    echo json_encode(["error" => "Missing category"]);
    exit;
}

$categorySlug = slugifyValue($category);
$cacheDir = __DIR__ . "/cache";
$cacheFile = $cacheDir . "/category-products-v3-" . $categorySlug . ".json";

if (is_file($cacheFile) && time() - filemtime($cacheFile) < $cacheTtl) {
    readfile($cacheFile);
    exit;
}

$query = '{
    products(filters: []) {
        items {
            id
            sku
            name
            description
            urlKey
            price { regular { text } }
            image { url }
            gallery { url }
            attributeIndex {
                attributeCode
                attributeName
                optionText
            }
            category { name }
        }
    }
}';

$result = graphqlRequest($query);

if (!empty($result["error"]) || !empty($result["errors"])) {
    if (is_file($cacheFile)) {
        readfile($cacheFile);
        exit;
    }

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

$payload = json_encode([
    "category" => [
        "id" => null,
        "name" => $categoryName,
        "slug" => $categorySlug
    ],
    "products" => array_map("normalizeProduct", $products)
]);

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

file_put_contents($cacheFile, $payload);
echo $payload;
