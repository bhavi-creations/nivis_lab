<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../customer_backend.php';
try {
    $data = customer_backend_request('/graphql', ['query'=>'{ currentCustomer { customerId } }']);
    if (array_key_exists('currentCustomer', $data) && $data['currentCustomer'] === null) echo "Live customer GraphQL connection OK; anonymous users have no account data.\n";
    else throw new RuntimeException('Unexpected anonymous profile response');
} catch (Throwable $error) { fwrite(STDERR, $error->getMessage() . "\n"); exit(1); }
