<?php

function shiprocketStorageError(string $path): RuntimeException
{
    error_log('Shiprocket storage unavailable: ' . $path . '. Configure a persistent directory writable by PHP.');
    return new RuntimeException('Shipping setup is temporarily unavailable. Please contact support before making payment.');
}

function shiprocketCheckDirectory(string $path): bool
{
    // Actually test writing: is_writable alone does not detect quotas or ACL issues.
    $probe = $path . '/.check-' . bin2hex(random_bytes(12)) . '.php';
    $handle = @fopen($probe, 'x');
    if (!$handle) {
        return false;
    }
    $guard = '<?php exit; ?>';
    $ok = fwrite($handle, $guard) === strlen($guard) && fflush($handle);
    fclose($handle);
    return @unlink($probe) && $ok;
}

function shiprocketResolveStorage(string $configured, array $candidates): string
{
    if ($configured !== '') {
        $candidates = [$configured];
    }
    // Reuse an existing directory, even when a previously unavailable parent
    // becomes writable. Never silently abandon records and duplicate shipments.
    foreach ($candidates as $path) {
        if (@is_dir($path)) {
            if (!shiprocketCheckDirectory($path)) {
                throw shiprocketStorageError($path);
            }
            return $path;
        }
    }
    foreach ($candidates as $path) {
        if (@mkdir($path, 0700, true) || @is_dir($path)) {
            if (!shiprocketCheckDirectory($path)) {
                throw shiprocketStorageError($path);
            }
            return $path;
        }
    }
    throw shiprocketStorageError(implode(', ', $candidates));
}

function shiprocketStorageDirectory(): string
{
    static $directory = null;
    if ($directory === null) {
        $candidates = [
            __DIR__ . '/storage/shiprocket',
            __DIR__ . '/cache/shiprocket'
        ];
        // Shared hosting commonly keeps the document root read-only while the
        // account's home directory is writable. This is durable, not /tmp.
        $accountHome = getenv('HOME');
        if (is_string($accountHome) && $accountHome !== '' && @is_dir($accountHome)) {
            $candidates[] = rtrim($accountHome, '/\\') . '/.nivis-shipping/'
                . substr(hash('sha256', __DIR__), 0, 20);
        }
        $directory = shiprocketResolveStorage(SHIPROCKET_STORAGE_DIR, $candidates);
    }
    return $directory;
}
