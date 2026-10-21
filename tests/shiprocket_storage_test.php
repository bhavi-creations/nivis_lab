<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/shiprocket_storage.php';
$root = sys_get_temp_dir() . '/nivis-storage-test-' . bin2hex(random_bytes(8));
mkdir($root, 0700);
function storageAssert(bool $ok, string $message): void {
    if (!$ok) { throw new RuntimeException($message); }
}
try {
    // A file as a parent reliably simulates a hosting path PHP cannot create.
    file_put_contents($root . '/blocked', 'blocked');
    $paths = [$root . '/blocked/shiprocket', $root . '/cache/shiprocket'];
    $selected = shiprocketResolveStorage('', $paths);
    storageAssert($selected === $paths[1], 'Use writable cache when the default cannot be created');
    file_put_contents($selected . '/saved.php', '<?php exit; ?>');
    unlink($root . '/blocked');
    mkdir($root . '/blocked');
    storageAssert(shiprocketResolveStorage('', $paths) === $selected, 'Keep existing storage after original parent becomes writable');
    storageAssert(is_file($selected . '/saved.php'), 'Keep previous records');
    $custom = $root . '/private/orders';
    storageAssert(shiprocketResolveStorage($custom, $paths) === $custom, 'Honor explicit private storage');
    file_put_contents($root . '/invalid', 'blocked');
    $rejected = false;
    try { shiprocketResolveStorage($root . '/invalid/orders', $paths); }
    catch (RuntimeException $e) { $rejected = true; }
    storageAssert($rejected, 'Never bypass an explicit invalid configuration');
    $privateFallback = $root . '/account-home/.nivis-shipping/site';
    storageAssert(shiprocketResolveStorage('', [
        $root . '/invalid/storage', $root . '/invalid/cache', $privateFallback
    ]) === $privateFallback, 'Use durable account-home storage when web folders cannot be created');
    echo "PASS: blocked default fallback, stable existing records, private override and invalid configuration\n";
} finally {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $entry) {
        if ($entry->isDir()) { rmdir($entry->getPathname()); } else { unlink($entry->getPathname()); }
    }
    rmdir($root);
}
