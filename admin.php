<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nivis Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .admin-container {
            padding: 20px;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .loading {
            text-align: center;
            padding: 40px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>GraphQL से Products और Cart Management</p>
    </div>

    <!-- Status Message -->
    <div id="status-message"></div>

    <!-- Auth Status -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Auth Status</h5>
        </div>
        <div class="card-body">
            <p>Status: <span id="auth-status">Loading...</span></p>
            <p>Username: <span id="auth-username">-</span></p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>All Products (GraphQL से Pull)</h5>
        </div>
        <div class="card-body">
            <div id="products-container" class="row">
                <div class="loading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Products load हो रहे हैं...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GraphQL Client Script -->
<script src="./assets/js/graphql-client.js"></script>

<script>
// जब page load हो, तो GraphQL से data fetch करें
document.addEventListener('DOMContentLoaded', async () => {
    console.log('Admin Dashboard Loaded');
    
    // 1. Auth Status Check करें
    await loadAuthStatus();
    
    // 2. Products Load करें
    await loadAllProducts();
});

// Auth Status Load करें
async function loadAuthStatus() {
    try {
        const data = await GraphQLClient.checkAuthStatus();
        const authStatus = data.data.authStatus;
        
        document.getElementById('auth-status').textContent = authStatus.isLoggedIn ? '✓ Logged In' : '✗ Not Logged In';
        document.getElementById('auth-username').textContent = authStatus.username || 'Guest';
    } catch (error) {
        console.error('Error loading auth status:', error);
        document.getElementById('auth-status').textContent = 'Error';
    }
}

// सभी Products Load करें (GraphQL से)
async function loadAllProducts() {
    try {
        console.log('Fetching products from GraphQL API...');
        const data = await GraphQLClient.getProducts();
        
        if (data.errors) {
            throw new Error(data.errors[0].message);
        }
        
        const products = data.data.products;
        console.log('Products fetched:', products);
        
        displayProducts(products);
        
        // Success message
        showMessage(`✓ Successfully loaded ${products.length} products from GraphQL API`, 'success');
    } catch (error) {
        console.error('Error loading products:', error);
        showMessage(`✗ Error loading products: ${error.message}`, 'error');
        document.getElementById('products-container').innerHTML = '<div class="error">Failed to load products</div>';
    }
}

// Products को Display करें
function displayProducts(products) {
    const container = document.getElementById('products-container');
    
    if (products.length === 0) {
        container.innerHTML = '<p class="text-center text-muted">कोई products नहीं मिले</p>';
        return;
    }
    
    container.innerHTML = products.map(product => `
        <div class="col-md-4 col-lg-3">
            <div class="product-card">
                <img src="${product.imageUrl}" alt="${product.name}" onerror="this.src='https://placehold.co/200x150/ccc/999?text=No+Image'">
                <h6 class="card-title">${product.name}</h6>
                <p class="text-muted small">${product.description}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-info">${product.category}</span>
                    <strong>₹${product.price}</strong>
                </div>
                <button class="btn btn-sm btn-success mt-2 w-100" onclick="addProductToCart('${product.id}', '${product.name}')">
                    Add to Cart
                </button>
            </div>
        </div>
    `).join('');
}

// Cart में Add करें
async function addProductToCart(productId, productName) {
    try {
        const result = await GraphQLClient.addToCart(productId, 1);
        
        if (result.data.addToCart.success) {
            showMessage(`✓ ${productName} को cart में add किया गया`, 'success');
        } else {
            showMessage(`✗ ${result.data.addToCart.message}`, 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showMessage(`✗ Cart में add करते समय error: ${error.message}`, 'error');
    }
}

// Status Message दिखाएं
function showMessage(message, type) {
    const messageEl = document.getElementById('status-message');
    messageEl.className = type;
    messageEl.textContent = message;
    
    // 5 सेकंड बाद message गायब करें
    setTimeout(() => {
        messageEl.textContent = '';
        messageEl.className = '';
    }, 5000);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
