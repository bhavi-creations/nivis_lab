# Nivis Lab E-Commerce - Local Setup Guide

## 📋 Requirements

- XAMPP (PHP 7.4+)
- Composer
- Browser

---

## 🚀 Quick Start

### Step 1: XAMPP शुरू करें

```cmd
# Windows PowerShell में
cd C:\xampp
.\apache_start.bat
```

या XAMPP Control Panel से Apache को ON करें।

---

### Step 2: Composer Dependencies Install करें

```cmd
cd C:\xampp\htdocs\nivis_lab
composer install
```

यह `vendor/` folder में GraphQL library डाउनलोड करेगा।

---

### Step 3: Local URLs

आपकी साइट अब यहाँ चलेगी:

| Page | URL |
|------|-----|
| Home | http://localhost/nivis_lab/index.php |
| Products | http://localhost/nivis_lab/products.php |
| **Admin Dashboard** | **http://localhost/nivis_lab/admin.php** ⭐ |
| GraphQL API | http://localhost/nivis_lab/graphql-api.php |
| GraphQL Test | http://localhost/nivis_lab/test-graphql.php |

---

## 🔧 Architecture

```
Frontend (PHP/HTML)
    ↓
JavaScript (GraphQL Client)
    ↓
GraphQL API (graphql-api.php)
    ↓
Backend Data (Products, Cart, Auth)
```

---

## 📝 Key Files

### 1. **graphql-api.php** (Backend API)
- GraphQL queries/mutations को handle करता है
- Sample products को return करता है
- Cart management
- Auth handling

### 2. **assets/js/graphql-client.js** (Frontend GraphQL Client)
- GraphQL endpoint को कॉल करता है
- Products, Cart, Auth functions

### 3. **admin.php** (Admin Dashboard) ⭐ नया
- GraphQL से products pull करता है
- Admin panel दिखाता है
- Real-time data loading

### 4. **config.php** (Configuration) ⭐ नया
- API URLs
- Database config (future)
- CORS settings
- Helper functions

### 5. **test-graphql.php** (Testing) ⭐ नया
- GraphQL API को test करने के लिए

---

## 🧪 Testing GraphQL

### Method 1: Browser में Direct
```
http://localhost/nivis_lab/test-graphql.php
```

### Method 2: cURL से (PowerShell)
```powershell
$query = '{ products { id name price category } }'
$json = @{ query = $query } | ConvertTo-Json
$json | Invoke-WebRequest -Uri "http://localhost/nivis_lab/graphql-api.php" -Method POST -ContentType "application/json"
```

### Method 3: JavaScript से (Browser Console)
```javascript
await GraphQLClient.getProducts()
```

---

## 🛒 Admin Dashboard का उपयोग

1. http://localhost/nivis_lab/admin.php खोलें
2. यह automatically:
   - Auth status check करेगा
   - GraphQL API से सभी products load करेगा
   - Products को card format में display करेगा
   - Add to Cart functionality

---

## 🔌 अपने Pages में GraphQL Connect करें

किसी भी PHP page में GraphQL data load करने के लिए:

```html
<?php include 'navbar.php'; ?>

<div id="products-container"></div>

<script src="./assets/js/graphql-client.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const data = await GraphQLClient.getProducts();
    console.log('Products:', data);
    // अपना custom rendering यहाँ करें
});
</script>
```

---

## 📱 Port Configuration

अगर port 80 काम न करे:

```
XAMPP Control Panel > Apache > Config > httpd.conf

Listen 80 को बदलकर Listen 8080 करें
फिर URL: http://localhost:8080/nivis_lab/
```

---

## 🐛 Troubleshooting

### GraphQL API काम नहीं कर रहा
```
1. config.php में API_BASE_URL check करें
2. graphql-api.php में errors check करें
3. test-graphql.php को run करें
```

### Products load नहीं हो रहे
```
1. Browser console में errors देखें
2. Network tab में API call check करें
3. graphql-api.php response check करें
```

### Composer error
```powershell
cd C:\xampp\htdocs\nivis_lab
composer install --no-interaction
```

---

## 🎯 Next Steps

1. ✅ XAMPP शुरू करें
2. ✅ Composer install करें
3. ✅ admin.php खोलें
4. ✅ Products GraphQL से load हों रहे हैं?
5. ✅ अपने pages में GraphQL integrate करें
6. ✅ Database में products migrate करें (future)

---

## 📞 Support

अगर कोई issue हो तो:
1. Browser Console देखें (F12)
2. Network tab में API calls देखें
3. PHP error log check करें

Good luck! 🚀

## Shiprocket checkout integration

The PHP checkout creates an EverShop order and saves its authoritative item prices and totals. After EverShop verifies the Razorpay payment, PHP creates a prepaid Shiprocket order. Browser-supplied prices are not used for fulfillment. Shiprocket IDs are stored with the order UUID under `storage/shiprocket/` (ignored by Git).

Deployment:
- Upload the PHP changes and separately provision `shiprocket_config.local.php` using `shiprocket_config.local.example.php`, or set `SHIPROCKET_API_EMAIL` and `SHIPROCKET_API_PASSWORD` in the PHP server environment. The local credentials file is deliberately ignored by Git.
- Set `SHIPROCKET_PICKUP_LOCATION` to the exact existing pickup name. The account checked during setup uses `Home`.
- Set `SHIPROCKET_PACKAGE_LENGTH`, `SHIPROCKET_PACKAGE_BREADTH`, `SHIPROCKET_PACKAGE_HEIGHT` (cm), and `SHIPROCKET_PACKAGE_WEIGHT` (kg) to the actual packed parcel. Existing defaults are 10 x 10 x 5 cm and 0.5 kg; these are fixed per order, not a calculation from cart quantity.
- Give PHP write access to the shipping storage directory. For production, set `SHIPROCKET_STORAGE_DIR` to durable private storage outside the web root. Multiple application servers must share this storage and its file locks. Back it up; it contains customer shipping information and fulfillment references.
- Run `php tools/check_shiprocket.php` to check authentication and pickup configuration without creating an order.
- Run `php tests/shiprocket_test.php` for local validation and duplicate protection tests.

A shipping failure does not turn a verified payment into a failed payment. Operators can retry known failed submissions with `php tools/retry_shiprocket.php <EverShop order UUID>`. The HTTP retry endpoint accepts only an order ID owned by the checkout session and requires a persisted verified payment. An uncertain network response or interrupted submission returns `review_required`; reconcile that UUID in the Shiprocket dashboard before any resubmission. No automatic retry is made for uncertain submissions.

Scope: creates prepaid domestic shipping orders; courier/AWB assignment, pickup booking and tracking are handled in Shiprocket. Payment verification is still triggered by the browser callback. If the customer closes the browser before verification, backend Razorpay webhook recovery is needed; the remote EverShop application is not part of this workspace. This integration stores shipment IDs locally, not in EverShop's shipment tables. A full paid checkout and actual parcel booking were not performed during development.

API references: https://apidocs.shiprocket.in/ and https://evershop.io/docs/api/order

### Live hosting storage fix

Upload `shiprocket_storage.php` together with the updated `shiprocket_config.php`, `shiprocket_service.php`, `create_razorpay_order.php`, and `tools/check_shiprocket.php`.

Without an explicit storage setting, PHP reuses existing `storage/shiprocket` or `cache/shiprocket`. For a new installation it tries creating `storage/shiprocket` first, then `cache/shiprocket` if the first location cannot be created. An existing directory that is no longer writable causes a setup error instead of abandoning its order records. Do not purge `cache/shiprocket` with disposable cache files; it contains persistent order records. Temporary/session storage is not used.

If the hosting account permits neither location, create a private persistent directory using the hosting file manager and grant the PHP runtime user write access. In `shiprocket_config.local.php`, add:

```php
define('SHIPROCKET_LOCAL_STORAGE_DIR', '/home/YOUR_ACCOUNT/private/shiprocket');
```

Use your hosting account's actual absolute path. `SHIPROCKET_STORAGE_DIR` in the environment takes precedence. When changing an existing storage location, migrate its records while checkout is stopped. Do not grant global 777 permissions. Run `php tools/check_shiprocket.php` on the live server to verify actual storage writes, API authentication and pickup configuration. Storage is checked before an EverShop order is created. Local tests cannot verify the live server's filesystem permissions.

The storage resolver also tries a site-specific directory under the hosting account's `HOME/.nivis-shipping/` when neither web storage location can be created. This is persistent account storage, never a temporary directory. Upload the supplied `storage/.htaccess` and `storage/index.php` too, so the parent folder exists after deployment. Existing shipping records always take priority over a new location. An explicit storage override never silently falls back. If every candidate is blocked by permissions, quota, or open_basedir, the hosting administrator must provide one writable persistent directory; application code cannot bypass that restriction.
