<?php include 'navbar.php'; ?>

<section class="products-section py-5">
    <div class="container">
        <h2 class="text-center mb-4">Our Products</h2>
        <div id="products-grid" class="row">
            <!-- Products will be loaded here -->
        </div>
    </div>
</section>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Your Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items">
                    <!-- Cart items will be loaded here -->
                </div>
                <div id="cart-total" class="mt-3">
                    <!-- Total will be shown here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Shopping</button>
                <button type="button" class="btn btn-primary">Checkout</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Load products
    try {
        const data = await loadProducts();
        displayProducts(data.products);
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('products-grid').innerHTML = '<p class="text-center">Error loading products.</p>';
    }

    // Load cart
    loadCart();
});

function displayProducts(products) {
    const grid = document.getElementById('products-grid');
    grid.innerHTML = products.map(product => `
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="${product.imageUrl}" class="card-img-top" alt="${product.name}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.description}</p>
                    <p class="card-text fw-bold">₹${product.price}</p>
                    <button class="btn btn-primary mt-auto" onclick="addProductToCart('${product.id}')">Add to Cart</button>
                </div>
            </div>
        </div>
    `).join('');
}

async function addProductToCart(productId) {
    try {
        const result = await addToCart(productId, 1);
        if (result.addToCart.success) {
            alert('Added to cart!');
            updateCartBadge(); // Update the badge in navbar
        } else {
            alert('Error: ' + result.addToCart.message);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Error adding to cart');
    }
}

async function loadCart() {
    try {
        const data = await getCart();
        displayCart(data.cart);
    } catch (error) {
        console.error('Error loading cart:', error);
    }
}

function displayCart(cart) {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    if (cart.items.length === 0) {
        cartItems.innerHTML = '<p>Your cart is empty.</p>';
        cartTotal.innerHTML = '';
        return;
    }

    cartItems.innerHTML = cart.items.map(item => `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>${item.product.name}</strong> (x${item.quantity})
            </div>
            <div>₹${item.product.price * item.quantity}</div>
        </div>
    `).join('');

    cartTotal.innerHTML = `<strong>Total: ₹${cart.total}</strong>`;
}

async function updateCartBadge() {
    try {
        const data = await getCart();
        const totalItems = data.cart.items.reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.getElementById('cartBadge');
        if (badge) badge.textContent = totalItems;
    } catch (error) {
        console.error('Error updating cart badge:', error);
    }
}
</script>

<?php include 'footer.php'; ?>