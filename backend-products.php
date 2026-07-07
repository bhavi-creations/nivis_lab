<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Keep the live backend response aligned with the normalized category feed.
// This file now acts as a thin compatibility wrapper.
require __DIR__ . '/fetch_category_products.php';
