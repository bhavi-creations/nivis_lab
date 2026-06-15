<?php
define('EVERSHOP_BASE_URL', getenv('EVERSHOP_BASE_URL') ?: 'https://admin.nivislabs.in/');
// define('EVERSHOP_BASE_URL', getenv('EVERSHOP_BASE_URL') ?: 'http://localhost:3000');
define('EVERSHOP_GRAPHQL_URL', rtrim(EVERSHOP_BASE_URL, '/') . '/api/graphql');
define('EVERSHOP_ASSET_BASE_URL', rtrim(EVERSHOP_BASE_URL, '/'));
?>
