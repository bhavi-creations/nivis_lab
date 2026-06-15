<?php
ini_set("display_errors", "0");
error_reporting(E_ALL);
ob_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$graphqlUrl = "https://admin.nivislabs.in/api/graphql";
$category = trim($_GET["category"] ?? "");
$cacheTtl = 60;

function slugifyValue($value)
{
    if (is_object($value)) {
        $value = (array) $value;
    }

    if (is_array($value)) {
        $value = implode(" ", array_map(function ($item) {
            return is_scalar($item) || $item === null ? (string) $item : json_encode($item);
        }, $value));
    }

    $value = strtolower(trim($value ?? ""));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function containsText($haystack, $needle)
{
    return $needle !== "" && strpos($haystack, $needle) !== false;
}

function jsonExit($payload)
{
    if (ob_get_length()) {
        ob_clean();
    }

    echo is_string($payload) ? $payload : json_encode($payload);
    exit;
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

function executeCurlRequest($url, $options)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        $hasSslError = stripos($error, 'ssl') !== false || stripos($error, 'certificate') !== false;
        curl_close($ch);

        if ($hasSslError) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                return ["error" => $error];
            }

            curl_close($ch);
            return $response;
        }

        return ["error" => $error];
    }

    curl_close($ch);
    return $response;
}

function graphqlRequest($query)
{
    global $graphqlUrl;

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
        CURLOPT_USERAGENT => "Mozilla/5.0 (compatible; NivisLabsFetcher/1.0)"
    ];

    $response = executeCurlRequest($graphqlUrl, $options);

    if (is_array($response) && !empty($response["error"])) {
        return $response;
    }

    $decoded = json_decode($response, true);

    if (!is_array($decoded)) {
        return ["error" => "Invalid JSON from GraphQL server", "raw" => $response];
    }

    return $decoded;
}

function normalizeImageUrl($url)
{
    if (!$url) {
        return "./assets/img/product.webp";
    }

    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    return "https://admin.nivislabs.in" . $url;
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
            if (containsText($code, $keyword) || containsText($name, $keyword)) {
                return $value;
            }
        }
    }

    return $fallback;
}

function inferIngredientValue($product, $attributes)
{
    $knownIngredients = [
        "salicylic acid" => "Salicylic Acid",
        "salicylic-acid" => "Salicylic Acid",
        "niacinamide" => "Niacinamide",
        "hyaluronic acid" => "Hyaluronic Acid",
        "hyaluronic-acid" => "Hyaluronic Acid",
        "ceramide" => "Ceramide",
        "ceramides" => "Ceramide",
        "panthenol" => "Panthenol",
        "vitamin c" => "Vitamin C",
        "vitamin-c" => "Vitamin C",
        "retinol" => "Retinol",
        "alpha arbutin" => "Alpha Arbutin",
        "alpha-arbutin" => "Alpha Arbutin",
        "arbutin" => "Arbutin",
        "centella" => "Centella",
        "zinc pca" => "Zinc PCA",
        "zinc-pca" => "Zinc PCA",
        "peptide" => "Peptide",
        "bakuchiol" => "Bakuchiol",
        "ferulic acid" => "Ferulic Acid",
        "ferulic-acid" => "Ferulic Acid",
        "squalane" => "Squalane",
        "shea butter" => "Shea Butter",
        "coenzyme q10" => "Coenzyme Q10",
        "pentavitin" => "Pentavitin",
        "polyglutamic acid" => "Polyglutamic Acid",
        "n acetyl glucosamine" => "N Acetyl Glucosamine",
        "tasmanian pepper" => "Tasmanian Pepper",
        "tyrobright" => "Tyrobright",
        "peptazin" => "Peptazin"
    ];

    $textParts = [
        normalizeText($product["name"] ?? ""),
        normalizeText($product["description"] ?? ""),
        normalizeText($product["subtitle"] ?? ""),
        normalizeText($product["urlKey"] ?? $product["url_key"] ?? "")
    ];

    foreach (($attributes ?? []) as $attribute) {
        $textParts[] = normalizeText($attribute["attributeName"] ?? "");
        $textParts[] = normalizeText($attribute["optionText"] ?? "");
    }

    $text = strtolower(implode(" ", array_filter($textParts)));
    $matches = [];

    foreach ($knownIngredients as $needle => $label) {
        if (strpos($text, $needle) !== false) {
            $matches[] = $label;
        }
    }

    return implode(", ", array_values(array_unique($matches)));
}

function inferProductTypeValue($product, $categoryName)
{
    $knownTypes = [
        "serum" => "Serum",
        "sunscreen spray" => "Sunscreen Spray",
        "sunscreen" => "Sunscreen",
        "face wash" => "Face Wash",
        "cleanser" => "Cleanser",
        "moisturizer" => "Moisturizer",
        "moisturiser" => "Moisturizer",
        "night cream" => "Night Cream",
        "cream" => "Cream",
        "lotion" => "Lotion",
        "spray" => "Spray",
        "face mist" => "Face Mist",
        "mist" => "Face Mist",
        "gel" => "Gel",
        "spot treatment" => "Spot Treatment",
        "treatment" => "Treatment"
    ];

    $text = strtolower(implode(" ", array_filter([
        normalizeText($product["name"] ?? ""),
        normalizeText($product["description"] ?? ""),
        normalizeText($product["subtitle"] ?? ""),
        normalizeText($product["urlKey"] ?? $product["url_key"] ?? ""),
        normalizeText($categoryName)
    ])));

    foreach ($knownTypes as $needle => $label) {
        if (strpos($text, $needle) !== false) {
            return $label;
        }
    }

    return "";
}

function productSizeUnit($product, $productType, $categoryName)
{
    $text = productSizeSearchText($product, $productType, $categoryName, true);

    foreach (["cream", "moisturizer", "moisturiser", "gel", "balm", "mask", "scrub", "lotion"] as $type) {
        if (containsText($text, $type)) {
            return "g";
        }
    }

    return "ml";
}

function defaultProductSize($product, $productType, $categoryName)
{
    $unit = productSizeUnit($product, $productType, $categoryName);
    $text = productSizeSearchText($product, $productType, $categoryName, false);

    if ($unit === "g") {
        return containsText($text, "spot-treatment") ? "15 g" : "50 g";
    }

    if (containsText($text, "serum")) {
        return "30 ml";
    }

    if (containsText($text, "cleanser") || containsText($text, "face-wash") || containsText($text, "mist") || containsText($text, "spray")) {
        return "100 ml";
    }

    return "50 ml";
}

function productSizeSearchText($product, $productType, $categoryName, $includeDescription)
{
    $parts = [
        normalizeText($productType),
        normalizeText($categoryName),
        normalizeText($product["name"] ?? ""),
        normalizeText($product["urlKey"] ?? $product["url_key"] ?? "")
    ];

    if ($includeDescription) {
        $parts[] = normalizeText($product["description"] ?? "");
    }

    return slugifyValue(implode(" ", array_filter($parts)));
}

function inferProductSize($product, $productType, $categoryName, $attributes)
{
    $size = attributeValue($attributes, ["size", "net_quantity", "net quantity", "quantity", "volume", "weight", "capacity"], "");
    $size = $size ?: normalizeText($product["size"] ?? $product["weight"] ?? $product["volume"] ?? "");

    if ($size !== "") {
        if (preg_match('/(\d+(?:\.\d+)?)\s*(ml|millilitre|milliliter|g|gm|gram|grams)\b/i', $size, $matches)) {
            $unit = strtolower($matches[2]);
            $unit = in_array($unit, ["g", "gm", "gram", "grams"], true) ? "g" : "ml";
            return rtrim(rtrim($matches[1], "0"), ".") . " " . $unit;
        }

        if (preg_match('/^\d+(?:\.\d+)?$/', $size)) {
            return $size . " " . productSizeUnit($product, $productType, $categoryName);
        }
    }

    return defaultProductSize($product, $productType, $categoryName);
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
    $price = nestedValue($product, ["price", "regular", "text"], "₹0");
    if (isset($product["price"]) && !is_array($product["price"])) {
        $price = $product["price"];
    }

    $numericPrice = (float) preg_replace('/[^0-9.]/', '', (string) $price);
    $productSlug = $product["urlKey"] ?? $product["url_key"] ?? "";
    $categoryName = normalizeText($product["category"] ?? "");
    $attributes = $product["attributeIndex"] ?? [];
    $primaryImage = nestedValue($product, ["image", "url"]);
    if (!$primaryImage && !empty($product["primaryImage"])) {
        $primaryImage = $product["primaryImage"];
    }
    if (!$primaryImage && !empty($product["imageUrl"])) {
        $primaryImage = $product["imageUrl"];
    }
    if (!$primaryImage && is_array($product["image"] ?? null)) {
        $primaryImage = $product["image"]["path"] ?? $product["image"]["src"] ?? $product["image"]["source"] ?? null;
    }

    $gallery = is_array($product["gallery"] ?? null) ? $product["gallery"] : [];
    $images = [];

    if ($primaryImage) {
        $images[] = normalizeImageUrl($primaryImage);
    }

    if (!empty($product["secondaryImage"])) {
        $images[] = normalizeImageUrl($product["secondaryImage"]);
    }

    foreach ($gallery as $image) {
        $galleryUrl = is_array($image) ? ($image["url"] ?? $image["path"] ?? $image["src"] ?? null) : $image;
        if ($galleryUrl) {
            $images[] = normalizeImageUrl($galleryUrl);
        }
    }

    $images = array_values(array_unique(array_filter($images)));
    $secondaryImage = $images[1] ?? $images[0] ?? normalizeImageUrl($primaryImage);

    $concern = attributeValue($attributes, ["concern", "skin_concern", "skin concern"], $categoryName ?: "Skincare");
    $ingredient = attributeValue($attributes, ["ingredient", "ingredients", "key_ingredient", "key ingredient"], "");
    if ($ingredient === "" || slugifyValue($ingredient) === slugifyValue($categoryName)) {
        $ingredient = inferIngredientValue($product, $attributes);
    }
    $productType = attributeValue($attributes, ["product_type", "product type", "type"], "");
    if ($productType === "" || slugifyValue($productType) === slugifyValue($categoryName)) {
        $productType = inferProductTypeValue($product, $categoryName);
    }
    $requestedCategory = normalizeText($product["_requestedCategory"] ?? "");
    $displayDescription = cleanDescriptionText($product["subtitle"] ?? $product["description"] ?? "", $concern ?: $categoryName ?: "Skincare");
    $filterConcern = trim($concern . " " . $requestedCategory);
    $size = inferProductSize($product, $productType, $categoryName, $attributes);

    return [
        "id" => $product["id"] ?? $product["sku"] ?? $product["name"] ?? null,
        "sku" => normalizeText($product["sku"] ?? ""),
        "urlKey" => normalizeText($productSlug),
        "name" => normalizeText($product["name"] ?? "Product Name"),
        "subtitle" => $displayDescription,
        "description" => $displayDescription,
        "price" => $price,
        "priceNumber" => (int) round($numericPrice),
        "imageUrl" => $images[0] ?? normalizeImageUrl($primaryImage),
        "secondaryImageUrl" => $secondaryImage,
        "images" => $images,
        "concern" => $filterConcern ?: $concern,
        "displayConcern" => $concern,
        "ingredient" => $ingredient,
        "type" => $productType,
        "size" => $size,
        "stars" => "4",
        "reviewsCount" => "120",
        "boughtTag" => "196+ bought in past month",
        "link" => normalizeText($product["link"] ?? "") ?: ($productSlug ? $productSlug . ".php" : "#"),
        "category" => $categoryName,
        "attributes" => normalizeAttributes($attributes),
        "details" => [
            ["label" => "SKU", "value" => normalizeText($product["sku"] ?? "")],
            ["label" => "Category", "value" => $categoryName],
            ["label" => "Skin Concern", "value" => $concern],
            ["label" => "Ingredient", "value" => $ingredient],
            ["label" => "Product Type", "value" => $productType],
            ["label" => "Size", "value" => $size],
            ["label" => "URL Key", "value" => normalizeText($productSlug)],
            ["label" => "Price", "value" => $price]
        ]
    ];
}

function normalizeCategory($category)
{
    $parent = is_array($category["parent"] ?? null) ? $category["parent"] : [];

    return [
        "id" => normalizeText($category["id"] ?? $category["categoryId"] ?? ""),
        "name" => normalizeText($category["name"] ?? ""),
        "slug" => slugifyValue($category["url_key"] ?? $category["urlKey"] ?? $category["name"] ?? ""),
        "parentId" => normalizeText($category["parent_id"] ?? $category["parentId"] ?? $parent["id"] ?? $parent["categoryId"] ?? ""),
        "parentSlug" => slugifyValue($parent["url_key"] ?? $parent["urlKey"] ?? $parent["name"] ?? ""),
        "parentName" => normalizeText($parent["name"] ?? "")
    ];
}

function categoryAliases($slug)
{
    $map = [
        "moisturizers" => ["moisturizer", "moisturisers", "moisturiser", "moisture"],
        "moisturizer" => ["moisturizers", "moisturisers", "moisturiser", "moisture"],
        "moisturisers" => ["moisturizers", "moisturizer", "moisturiser", "moisture"],
        "moisturiser" => ["moisturizers", "moisturizer", "moisturisers", "moisture"],
        "face-serum" => ["face-serums", "face-serums-treatments", "face-serums-and-treatments", "serum", "serums"],
        "face-serums" => ["face-serum", "face-serums-treatments", "face-serums-and-treatments", "serum", "serums"],
        "face-serums-treatments" => ["face-serum", "face-serums", "face-serums-and-treatments", "serum", "serums"],
        "face-wash" => ["face-washes", "cleanser", "cleansers"],
        "sunscreens" => ["sunscreen", "sun-protection"],
        "sunscreen" => ["sunscreens", "sun-protection"],
        "acne" => ["anti-acne", "acne-breakouts", "acne-and-breakouts", "pimples", "breakouts"],
        "acne-marks" => ["acne-marks", "post-acne-marks", "acne-marks-and-blemishes"],
        "dark-spots" => ["dark-spots", "dark-spot", "spots"],
        "anti-ageing" => ["anti-ageing", "anti-aging", "ageing", "aging", "wrinkles", "fine-lines"],
        "anti-aging" => ["anti-ageing", "anti-aging", "ageing", "aging", "wrinkles", "fine-lines"],
        "lines-and-wrinkles" => ["anti-ageing", "anti-aging", "wrinkles", "fine-lines"],
        "brigthening" => ["brightening"]
    ];

    return array_values(array_unique(array_merge([$slug], $map[$slug] ?? [])));
}

function fetchCategories()
{
    $queries = [
        '{ categories { items { id name url_key parent_id } } }',
        '{ categories { items { categoryId name urlKey parentId } } }',
        '{ categories { items { id name url_key parent { id name url_key } } } }',
        '{ categories { items { categoryId name urlKey parent { categoryId name urlKey } } } }',
        '{ categories { items { id name url_key } } }',
        '{ categories { items { categoryId name urlKey } } }'
    ];

    foreach ($queries as $query) {
        $result = graphqlRequest($query);
        $items = $result["data"]["categories"]["items"] ?? null;

        if (is_array($items)) {
            return array_map("normalizeCategory", $items);
        }
    }

    return [];
}

function categoryMatches($category, $keys)
{
    $categoryKeys = array_filter([
        $category["id"] ?? "",
        $category["slug"] ?? "",
        slugifyValue($category["name"] ?? "")
    ]);

    return count(array_intersect($categoryKeys, $keys)) > 0;
}

function categoryParentMatches($category, $keys)
{
    $parentKeys = array_filter([
        $category["parentId"] ?? "",
        $category["parentSlug"] ?? "",
        slugifyValue($category["parentName"] ?? "")
    ]);

    return count(array_intersect($parentKeys, $keys)) > 0;
}

function resolveCategoryKeys($categorySlug)
{
    $keys = categoryAliases($categorySlug);
    $categories = fetchCategories();

    foreach ($categories as $category) {
        if (categoryMatches($category, $keys)) {
            $keys[] = $category["id"];
            $keys[] = $category["slug"];
            $keys[] = slugifyValue($category["name"]);
        }
    }

    $keys = array_values(array_unique(array_filter($keys)));
    $changed = true;

    while ($changed) {
        $changed = false;

        foreach ($categories as $category) {
            if (!categoryParentMatches($category, $keys)) {
                continue;
            }

            $before = count($keys);
            $keys[] = $category["id"];
            $keys[] = $category["slug"];
            $keys[] = slugifyValue($category["name"]);
            $keys = array_values(array_unique(array_filter($keys)));
            $changed = count($keys) > $before;
        }
    }

    return $keys;
}

function productCategorySlug($product)
{
    $category = $product["category"] ?? "";

    if (is_array($category)) {
        return slugifyValue($category["url_key"] ?? $category["urlKey"] ?? $category["name"] ?? normalizeText($category));
    }

    return slugifyValue(normalizeText($category));
}

function productMatchValues($product)
{
    $values = [
        productCategorySlug($product),
        slugifyValue($product["name"] ?? ""),
        slugifyValue($product["description"] ?? ""),
        slugifyValue($product["subtitle"] ?? ""),
        slugifyValue($product["sku"] ?? ""),
        slugifyValue($product["urlKey"] ?? $product["url_key"] ?? ""),
        slugifyValue($product["concern"] ?? ""),
        slugifyValue($product["ingredient"] ?? ""),
        slugifyValue($product["type"] ?? "")
    ];

    foreach (($product["attributeIndex"] ?? []) as $attribute) {
        $values[] = slugifyValue($attribute["attributeCode"] ?? "");
        $values[] = slugifyValue($attribute["attributeName"] ?? "");
        $values[] = slugifyValue(normalizeText($attribute["optionText"] ?? ""));
    }

    return array_values(array_unique(array_filter($values)));
}

function productMatchesCategory($product, $categoryKeys)
{
    $values = productMatchValues($product);

    foreach ($categoryKeys as $key) {
        if (!$key) {
            continue;
        }

        if (in_array($key, $values, true)) {
            return true;
        }

        foreach ($values as $value) {
            if (strlen($key) >= 4 && containsText($value, $key)) {
                return true;
            }
        }
    }

    return false;
}

function fetchProductsByCategorySlug($slug)
{
    if (!$slug) {
        return [];
    }

    $safeSlug = addslashes($slug);
    $query = '
        query {
            productsByCategory(categorySlug: "' . $safeSlug . '") {
                id
                sku
                name
                description
                subtitle
                urlKey
                url_key
                price { regular { text } }
                image { url }
                gallery { url }
                attributeIndex {
                    attributeCode
                    attributeName
                    optionText
                }
                primaryImage
                secondaryImage
                category {
                    name
                    url_key
                    urlKey
                }
            }
        }
    ';

    $result = graphqlRequest($query);
    $items = $result["data"]["productsByCategory"] ?? [];

    return is_array($items) ? $items : [];
}

if ($category === "") {
    jsonExit(["error" => "Missing category"]);
}

$categorySlug = slugifyValue($category);
$isAllProductsCategory = in_array($categorySlug, ["all", "products", "all-products"], true);
$cacheDir = __DIR__ . "/cache";
$cacheFile = $cacheDir . "/category-products-v9-" . $categorySlug . ".json";

if (is_file($cacheFile) && time() - filemtime($cacheFile) < $cacheTtl) {
    jsonExit(file_get_contents($cacheFile));
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
        jsonExit(file_get_contents($cacheFile));
    }

    $errorMessage = !empty($result["error"]) ? $result["error"] : json_encode($result["errors"]);
    jsonExit(["error" => "Unable to load products from backend: " . $errorMessage]);
}

$backendConnectionError = false;
$allProducts = $result["data"]["products"]["items"] ?? [];
$categoryKeys = $isAllProductsCategory ? [] : ($backendConnectionError ? categoryAliases($categorySlug) : resolveCategoryKeys($categorySlug));
$products = $isAllProductsCategory ? array_values($allProducts) : array_values(array_filter($allProducts, function ($product) use ($categoryKeys) {
    return productMatchesCategory($product, $categoryKeys);
}));

if (!$isAllProductsCategory && count($products) === 0 && !$backendConnectionError) {
    $productsById = [];

    foreach ($categoryKeys as $key) {
        foreach (fetchProductsByCategorySlug($key) as $product) {
            $id = normalizeText($product["id"] ?? $product["sku"] ?? $product["name"] ?? "");
            $productsById[$id ?: md5(json_encode($product))] = $product;
        }
    }

    $products = array_values($productsById);
}

$categoryName = $isAllProductsCategory ? ($categorySlug === "products" ? "Our Products" : "All Products") : $category;

if (!$isAllProductsCategory && count($products) > 0) {
    $categoryName = normalizeText($products[0]["category"] ?? "") ?: $category;
}

foreach ($products as &$product) {
    $product["_requestedCategory"] = $categorySlug;
}
unset($product);

$payload = json_encode([
    "category" => [
        "id" => null,
        "name" => $categoryName,
        "slug" => $categorySlug,
        "matchedCategories" => $categoryKeys
    ],
    "products" => array_map("normalizeProduct", $products)
]);

if (!$backendConnectionError && count($products) > 0) {
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    file_put_contents($cacheFile, $payload);
}

jsonExit($payload);
