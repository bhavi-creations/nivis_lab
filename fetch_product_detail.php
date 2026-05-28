<?php
ini_set("display_errors", "0");
error_reporting(E_ALL);
ob_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$graphqlUrl = "http://localhost:3000/api/graphql";
$productKey = trim($_GET["product"] ?? "");

function jsonExit($payload)
{
    if (ob_get_length()) {
        ob_clean();
    }

    echo is_string($payload) ? $payload : json_encode($payload);
    exit;
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

function cleanDescriptionText($value, $fallback = "")
{
    $text = html_entity_decode(strip_tags(normalizeText($value)), ENT_QUOTES | ENT_HTML5, "UTF-8");
    $text = preg_replace('/\b[rc]__[a-f0-9-]{36}\b/i', ' ', $text);
    $text = preg_replace('/\b[a-z]+:[a-z0-9-]+\b/i', ' ', $text);
    $text = preg_replace('/\b[a-z]-[a-z0-9_-]{8,}\b/i', ' ', $text);
    $text = preg_replace('/\b\d+\.\d+\.\d+\b.*$/', ' ', $text);
    $text = preg_replace('/\b(md|sm|lg|xl):[a-z0-9-]+\b/i', ' ', $text);
    $text = preg_replace('/\b(col-span|grid-cols)-\d+\b/i', ' ', $text);
    $text = str_replace("\xc2\xa0", " ", $text);

    if (stripos($text, "paragraph") !== false) {
        $parts = preg_split('/\bparagraph\b/i', $text);
        $text = trim(end($parts));
    }

    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text, " \t\n\r\0\x0B/|");

    return $text !== "" ? $text : $fallback;
}

function slugifyValue($value)
{
    $value = normalizeText($value);
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function nestedValue($array, $path, $fallback = null)
{
    $value = $array;

    foreach ($path as $key) {
        if (!is_array($value) || !array_key_exists($key, $value)) {
            return $fallback;
        }

        $value = $value[$key];
    }

    return $value;
}

function normalizeImageUrl($url)
{
    if (!$url) {
        return "./assets/img/product.webp";
    }

    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    if (str_starts_with($url, "./") || str_starts_with($url, "assets/")) {
        return $url;
    }

    return "http://localhost:3000" . $url;
}

function attributeValue($attributes, $keywords, $fallback)
{
    foreach (($attributes ?? []) as $attribute) {
        $code = strtolower(normalizeText($attribute["attributeCode"] ?? ""));
        $name = strtolower(normalizeText($attribute["attributeName"] ?? ""));
        $value = normalizeText($attribute["optionText"] ?? "");

        if (!$value) {
            continue;
        }

        foreach ($keywords as $keyword) {
            if (strpos($code, $keyword) !== false || strpos($name, $keyword) !== false) {
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
            $items[] = ["name" => $name, "value" => $value];
        }
    }

    return $items;
}

function normalizeProduct($product)
{
    $price = nestedValue($product, ["price", "regular", "text"], "₹0");
    if (isset($product["price"]) && !is_array($product["price"])) {
        $price = $product["price"];
    }

    $productSlug = normalizeText($product["urlKey"] ?? $product["url_key"] ?? "");
    $categoryName = normalizeText($product["category"] ?? "");
    $attributes = $product["attributeIndex"] ?? $product["attributes"] ?? [];
    $primaryImage = nestedValue($product, ["image", "url"]);

    if (!$primaryImage && !empty($product["primaryImage"])) {
        $primaryImage = $product["primaryImage"];
    }
    if (!$primaryImage && !empty($product["imageUrl"])) {
        $primaryImage = $product["imageUrl"];
    }
    if (!$primaryImage && is_array($product["image"] ?? null)) {
        $primaryImage = $product["image"]["path"] ?? $product["image"]["src"] ?? null;
    }

    $gallery = is_array($product["gallery"] ?? null) ? $product["gallery"] : [];
    $images = [];

    foreach ([$primaryImage, $product["secondaryImage"] ?? null, $product["secondaryImageUrl"] ?? null] as $image) {
        if ($image) {
            $images[] = normalizeImageUrl($image);
        }
    }

    foreach ($gallery as $image) {
        $galleryUrl = is_array($image) ? ($image["url"] ?? $image["path"] ?? $image["src"] ?? null) : $image;
        if ($galleryUrl) {
            $images[] = normalizeImageUrl($galleryUrl);
        }
    }

    $images = array_values(array_unique(array_filter($images)));
    if (count($images) === 0) {
        $images[] = "./assets/img/product.webp";
    }

    $concern = $product["concern"] ?? attributeValue($attributes, ["concern", "skin_concern", "skin concern"], $categoryName ?: "Skincare");
    $ingredient = $product["ingredient"] ?? attributeValue($attributes, ["ingredient", "ingredients", "key_ingredient", "key ingredient"], $categoryName ?: "skincare");
    $productType = $product["type"] ?? attributeValue($attributes, ["product_type", "product type", "type"], $categoryName ?: "Product");
    $description = cleanDescriptionText($product["subtitle"] ?? $product["description"] ?? "", normalizeText($concern ?: $categoryName ?: "Skincare"));
    $sku = normalizeText($product["sku"] ?? "");
    $displayConcern = normalizeText($product["displayConcern"] ?? $concern);
    $displayIngredient = normalizeText($ingredient);
    $displayType = normalizeText($productType);
    $whatIs = sprintf(
        "%s is a %s made for %s. %s",
        normalizeText($product["name"] ?? "This product"),
        $displayType ?: "skincare product",
        $displayConcern ?: "daily skincare",
        $description
    );
    $benefits = array_values(array_filter([
        $displayConcern ? "Helps with " . $displayConcern : "",
        $displayIngredient ? "Powered by " . $displayIngredient : "",
        $displayType ? "Fits into your routine as a " . $displayType : ""
    ]));

    return [
        "id" => normalizeText($product["id"] ?? $product["sku"] ?? $product["name"] ?? ""),
        "sku" => $sku,
        "productCode" => $sku ?: normalizeText($product["id"] ?? $productSlug),
        "urlKey" => $productSlug,
        "name" => normalizeText($product["name"] ?? "Product Name"),
        "subtitle" => $description,
        "description" => $description,
        "whatIs" => $whatIs,
        "benefits" => $benefits,
        "howToUse" => "Use as directed on the product label. Patch test before first use and apply consistently as part of your skincare routine.",
        "price" => normalizeText($price),
        "priceNumber" => (int) round((float) preg_replace('/[^0-9.]/', '', (string) $price)),
        "imageUrl" => $images[0],
        "secondaryImageUrl" => $images[1] ?? $images[0],
        "images" => $images,
        "concern" => normalizeText($concern),
        "ingredient" => normalizeText($ingredient),
        "type" => normalizeText($productType),
        "stars" => normalizeText($product["stars"] ?? "★★★★½"),
        "reviewsCount" => normalizeText($product["reviewsCount"] ?? "120"),
        "boughtTag" => normalizeText($product["boughtTag"] ?? "196+ bought in past month"),
        "category" => $categoryName,
        "attributes" => normalizeAttributes($attributes),
        "details" => [
            ["label" => "Product Code", "value" => $sku ?: normalizeText($product["id"] ?? $productSlug)],
            ["label" => "SKU", "value" => $sku],
            ["label" => "Category", "value" => $categoryName],
            ["label" => "Skin Concern", "value" => $displayConcern],
            ["label" => "Ingredient", "value" => $displayIngredient],
            ["label" => "Product Type", "value" => $displayType],
            ["label" => "URL Key", "value" => $productSlug],
            ["label" => "Price", "value" => normalizeText($price)]
        ],
        "faqs" => [
            [
                "question" => "What is this product?",
                "answer" => $whatIs
            ],
            [
                "question" => $sku ? "What is the product code?" : "How do I identify this product?",
                "answer" => $sku ? "Product code: " . $sku : "Product identifier: " . normalizeText($product["id"] ?? $productSlug)
            ],
            [
                "question" => "What are the benefits?",
                "answer" => implode(" ", $benefits) ?: $description
            ]
        ]
    ];
}

function graphqlRequest($query)
{
    global $graphqlUrl;

    if (!function_exists("curl_init")) {
        return ["error" => "PHP cURL extension is not enabled"];
    }

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

    return is_array($decoded) ? $decoded : ["error" => "Invalid JSON from GraphQL server"];
}

function productMatches($product, $productKey)
{
    $needle = slugifyValue($productKey);
    $values = [
        $product["id"] ?? "",
        $product["sku"] ?? "",
        $product["urlKey"] ?? "",
        $product["url_key"] ?? "",
        $product["name"] ?? ""
    ];

    foreach ($values as $value) {
        if (slugifyValue($value) === $needle) {
            return true;
        }
    }

    return false;
}

function findProductInCache($productKey)
{
    foreach (glob(__DIR__ . "/cache/category-products-v*.json") ?: [] as $file) {
        $payload = json_decode(file_get_contents($file), true);
        $products = $payload["products"] ?? [];

        foreach ($products as $product) {
            if (productMatches($product, $productKey)) {
                return normalizeProduct($product);
            }
        }
    }

    return null;
}

if ($productKey === "") {
    jsonExit(["error" => "Missing product"]);
}

$cachedProduct = findProductInCache($productKey);
if ($cachedProduct) {
    jsonExit(["product" => $cachedProduct]);
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
$products = $result["data"]["products"]["items"] ?? [];

foreach ($products as $product) {
    if (productMatches($product, $productKey)) {
        jsonExit(["product" => normalizeProduct($product)]);
    }
}

jsonExit(["error" => "Product not found"]);
