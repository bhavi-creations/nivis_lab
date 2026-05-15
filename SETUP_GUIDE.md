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
