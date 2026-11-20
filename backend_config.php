<?php
define('EVERSHOP_BASE_URL', getenv('EVERSHOP_BASE_URL') ?: 'https://admin.nivislabs.in/');
// define('EVERSHOP_BASE_URL', getenv('EVERSHOP_BASE_URL') ?: 'http://localhost:3000');
define('EVERSHOP_GRAPHQL_URL', rtrim(EVERSHOP_BASE_URL, '/') . '/api/graphql');
define('EVERSHOP_ASSET_BASE_URL', rtrim(EVERSHOP_BASE_URL, '/'));
define('EVERSHOP_API_BASE_URL', rtrim(EVERSHOP_BASE_URL, '/') . '/api');

// Site-wide discount. The storefront shows every EverShop price struck through
// with 20% off (assets/js/product-pricing.js). To charge that amount, create this
// coupon in EverShop admin (Marketing > Coupons): 20% "percentage discount to
// entire order", enabled, no end date, no usage limit, no minimum. The site then
// applies it to every cart automatically. Set to '' to switch auto-apply off.
define('NIVIS_SITE_DISCOUNT_COUPON', getenv('NIVIS_SITE_DISCOUNT_COUPON') !== false ? (string) getenv('NIVIS_SITE_DISCOUNT_COUPON') : 'NIVIS20');
?>
