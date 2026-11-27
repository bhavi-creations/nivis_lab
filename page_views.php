<?php
// Page view counter used by the eye button in footer.php.
// GET  page_views.php?page=/index.php            -> current count
// POST page_views.php  (page=/index.php)         -> count + 1, returns new count
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$storeFile = __DIR__ . '/storage/page-views.json';

function nivis_normalize_page($page)
{
    $path = parse_url((string) $page, PHP_URL_PATH);
    $path = is_string($path) ? $path : '/';
    $query = parse_url((string) $page, PHP_URL_QUERY);

    // "/about", "/about.php" and "/about/" style URLs all count as the same page.
    $base = substr($path, -1) === '/' ? 'index' : strtolower(basename($path));
    $base = preg_replace('/\.php$/', '', $base);
    $base = preg_replace('/[^a-z0-9._-]/', '', $base);
    if ($base === '') {
        $base = 'index';
    }
    if (strlen($base) > 120) {
        return null;
    }
    $base .= '.php';

    // Product detail pages are counted per product.
    if ($base === 'product-detail.php' && is_string($query)) {
        parse_str($query, $params);
        $product = strtolower(preg_replace('/[^A-Za-z0-9._-]/', '', (string) ($params['product'] ?? '')));
        if ($product !== '') {
            $base .= '?product=' . substr($product, 0, 120);
        }
    }

    return $base;
}

$page = nivis_normalize_page($_POST['page'] ?? $_GET['page'] ?? '');
if ($page === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid page']);
    exit;
}

$handle = @fopen($storeFile, 'c+');
if (!$handle) {
    http_response_code(500);
    echo json_encode(['error' => 'Counter storage unavailable']);
    exit;
}

$isIncrement = $_SERVER['REQUEST_METHOD'] === 'POST';
flock($handle, $isIncrement ? LOCK_EX : LOCK_SH);

$raw = stream_get_contents($handle);
$counts = json_decode($raw ?: '{}', true);
if (!is_array($counts)) {
    $counts = [];
}

$count = (int) ($counts[$page] ?? 0);

if ($isIncrement) {
    $count++;
    $counts[$page] = $count;
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($counts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    fflush($handle);
}

flock($handle, LOCK_UN);
fclose($handle);

echo json_encode(['page' => $page, 'views' => $count]);
