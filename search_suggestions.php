<?php
ini_set("display_errors", "0");
error_reporting(E_ALL);
ob_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/backend_config.php';

$queryText = trim($_GET["q"] ?? "");
$limit = max(1, min(12, (int) ($_GET["limit"] ?? 10)));

function searchJsonExit($payload)
{
    if (ob_get_length()) {
        ob_clean();
    }

    echo json_encode($payload);
    exit;
}

function searchText($value)
{
    if (is_object($value)) {
        $value = (array) $value;
    }

    if (is_array($value)) {
        return trim(implode(" ", array_map("searchText", $value)));
    }

    return trim((string) $value);
}

function searchSlug($value)
{
    $value = strtolower(searchText($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function searchNeedle($value)
{
    return strtolower(searchText($value));
}

function searchImageUrl($url)
{
    $url = searchText($url);

    if ($url === "") {
        return "./assets/img/product.webp";
    }

    if (preg_match('/^https?:\/\//i', $url) || strpos($url, "./") === 0 || strpos($url, "/") === 0) {
        return $url;
    }

    return EVERSHOP_ASSET_BASE_URL . $url;
}

function searchGraphqlRequest($query)
{
    if (!function_exists("curl_init")) {
        return ["error" => "PHP cURL extension is not enabled"];
    }

    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["query" => $query]),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_CONNECTTIMEOUT_MS => 2000,
        CURLOPT_TIMEOUT_MS => 5000,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => "Mozilla/5.0 (compatible; NivisLabsSearch/1.0)"
    ];

    $ch = curl_init(EVERSHOP_GRAPHQL_URL);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);

        if (stripos($error, "ssl") !== false || stripos($error, "certificate") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
        }

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["error" => $error];
        }
    }

    curl_close($ch);
    $decoded = json_decode($response, true);

    return is_array($decoded) ? $decoded : ["error" => "Invalid JSON from backend"];
}

function productCategoryName($product)
{
    $category = $product["category"] ?? "";

    if (is_array($category)) {
        return searchText($category["name"] ?? "");
    }

    return searchText($category);
}

function productImage($product)
{
    $image = $product["image"]["url"] ?? $product["primaryImage"] ?? "";

    if (!$image && is_array($product["gallery"] ?? null) && !empty($product["gallery"][0])) {
        $image = is_array($product["gallery"][0]) ? ($product["gallery"][0]["url"] ?? "") : $product["gallery"][0];
    }

    return searchImageUrl($image);
}

function productSearchText($product)
{
    $parts = [
        $product["name"] ?? "",
        $product["sku"] ?? "",
        $product["urlKey"] ?? $product["url_key"] ?? "",
        $product["description"] ?? "",
        productCategoryName($product)
    ];

    foreach (($product["attributeIndex"] ?? []) as $attribute) {
        $parts[] = $attribute["attributeName"] ?? "";
        $parts[] = $attribute["optionText"] ?? "";
    }

    return searchNeedle($parts);
}

function productItem($product)
{
    $key = searchText($product["urlKey"] ?? $product["url_key"] ?? $product["sku"] ?? $product["id"] ?? $product["name"] ?? "");
    $price = $product["price"]["regular"]["text"] ?? "";
    $category = productCategoryName($product);

    return [
        "type" => "product",
        "title" => searchText($product["name"] ?? "Product"),
        "subtitle" => trim(implode(" / ", array_filter([$category, searchText($price)]))),
        "image" => productImage($product),
        "url" => "product-detail.php?product=" . rawurlencode($key)
    ];
}

function categoryItem($category)
{
    $slug = searchSlug($category["url_key"] ?? $category["urlKey"] ?? $category["name"] ?? "");

    return [
        "type" => "category",
        "title" => searchText($category["name"] ?? "Category"),
        "subtitle" => "Category",
        "image" => "",
        "url" => "category.php?category=" . rawurlencode($slug)
    ];
}

if ($queryText === "") {
    searchJsonExit(["query" => "", "items" => []]);
}

$graphqlQuery = '{
    products(filters: []) {
        items {
            id
            sku
            name
            description
            urlKey
            url_key
            price { regular { text } }
            image { url }
            gallery { url }
            primaryImage
            attributeIndex {
                attributeName
                optionText
            }
            category { name url_key }
        }
    }
    categories {
        items {
            id
            name
            url_key
        }
    }
}';

$result = searchGraphqlRequest($graphqlQuery);

if (!empty($result["error"]) || !empty($result["errors"])) {
    $message = !empty($result["error"]) ? $result["error"] : json_encode($result["errors"]);
    searchJsonExit(["query" => $queryText, "items" => [], "error" => "Unable to search backend: " . $message]);
}

$needle = searchNeedle($queryText);
$needleSlug = searchSlug($queryText);
$items = [];

foreach (($result["data"]["categories"]["items"] ?? []) as $category) {
    $haystack = searchNeedle([$category["name"] ?? "", $category["url_key"] ?? $category["urlKey"] ?? ""]);
    $haystackSlug = searchSlug($haystack);

    if (strpos($haystack, $needle) !== false || ($needleSlug !== "" && strpos($haystackSlug, $needleSlug) !== false)) {
        $items[] = categoryItem($category);
    }
}

foreach (($result["data"]["products"]["items"] ?? []) as $product) {
    $haystack = productSearchText($product);
    $haystackSlug = searchSlug($haystack);

    if (strpos($haystack, $needle) !== false || ($needleSlug !== "" && strpos($haystackSlug, $needleSlug) !== false)) {
        $items[] = productItem($product);
    }
}

$uniqueItems = [];
foreach ($items as $item) {
    $uniqueItems[$item["type"] . ":" . $item["url"]] = $item;
}

searchJsonExit([
    "query" => $queryText,
    "items" => array_slice(array_values($uniqueItems), 0, $limit)
]);
?>
