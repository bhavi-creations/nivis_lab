<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-6 mb-4 text-center">
                <h6>Information</h6>
                <ul>
                    <li><a href="tracking.php">Track Your Order</a></li>
                    <li><a href="our-story.php">The Nivis Labs Story</a></li>
                    <!-- <li><a href="our_team.php">The Nivis Labs Council</a></li> -->
                    <li><a href="skinthesis.php">Skinthesis</a></li>
                    <li><a href="reward.php">Rewards</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-6 mb-4 text-center">
                <h6>Important Links</h6>
                <ul>
                    <!-- <li><a href="shipping_returns.php">Shipping & Returns</a></li> -->
                    <li><a href="terms_condition.php">Terms & Conditions</a></li>
                    <li><a href="privacy_policy.php">Privacy Policy</a></li>
                    <li><a href="refund.php">Refund Policy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <!-- <li><a href="build_phd.php">Let's Build Nivis Labs</a></li> -->
                </ul>
            </div>
            <div class="col-lg-4 col-6 mb-4 text-center d-none d-lg-block">
                <h6>You will love us here</h6>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-12">
                <div class="footer-logo">NIVIS LABS</div>
            </div>
        </div>
    </div>
</footer>

<div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer">
    <div class="offcanvas-header border-bottom">
        <h5>Mee Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="cartContent"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="assets/js/category-products.js?v=12"></script>

<script>
    const cartDrawerEl = document.getElementById('cartDrawer');
    const cartContentEl = document.getElementById('cartContent');
    let cartItems = window.NivisCart ? window.NivisCart.read() : [];
    let cartOffcanvas = null;

    function formatPrice(value) {
        const amount = Number(value || 0);
        return isNaN(amount) ? '₹0' : `₹${amount.toFixed(0)}`;
    }

    function getProductInfoFromCard(card) {
        if (!card) return null;
        const nameEl = card.querySelector('.product-name');
        const priceEl = card.querySelector('.product-price');
        const imgEl = card.querySelector('.product-img-wrap img.img-primary, .product-img-wrap img, img');
        const name = nameEl ? nameEl.textContent.trim() : 'Product';
        const priceText = priceEl ? priceEl.textContent.replace(/[^0-9.]/g, '').trim() : card.dataset.price || '0';
        const sku = card.dataset.sku || card.dataset.productSku || card.dataset.productCode || '';

        return {
            id: sku || card.dataset.productId || name.replace(/\s+/g, '_').toLowerCase(),
            sku,
            name,
            price: Number(priceText) || Number(card.dataset.price) || 0,
            image: imgEl ? imgEl.src : '',
            quantity: 1,
        };
    }

    function removeFromCart(itemId) {
        if (!window.NivisCart) return;
        window.NivisCart.remove(itemId);
        renderCart();
    }

    function getRelatedProducts() {
        const allProducts = document.querySelectorAll('.product-card');
        const relatedProducts = [];

        allProducts.forEach(product => {
            if (relatedProducts.length >= 4) return;
            const productId = product.dataset.productId || product.querySelector('.product-name')?.textContent.trim().replace(/\s+/g, '_').toLowerCase();
            const sku = product.dataset.sku || product.dataset.productSku || product.dataset.productCode || '';
            if (!productId || cartItems.some(item => item.id === productId)) return;

            const name = product.querySelector('.product-name')?.textContent.trim() || '';
            const priceText = product.querySelector('.product-price')?.textContent.replace(/[^0-9.]/g, '').trim() || product.dataset.price || '0';
            const image = product.querySelector('.product-img-wrap img, img')?.src || '';
            const sub = product.querySelector('.product-sub')?.textContent.trim() || '';

            if (name && image) {
                relatedProducts.push({
                    id: productId,
                    sku,
                    name,
                    price: Number(priceText) || 0,
                    image,
                    sub
                });
            }
        });

        return relatedProducts;
    }

    function addRelatedToCart(itemId, itemName, itemPrice, itemImage, sku = '', quantity = 1) {
        if (!window.NivisCart) return;
        window.NivisCart.add({
            id: itemId,
            sku,
            name: itemName,
            price: itemPrice,
            image: itemImage
        }, quantity);
        renderCart();
        openCartDrawer();
    }

    function renderCart() {
        if (!cartContentEl) return;
        cartItems = window.NivisCart ? window.NivisCart.read() : cartItems;

        if (cartItems.length === 0) {
            cartContentEl.innerHTML = `
                <div class="text-center py-4">
                    <p class="mb-2">Your cart is empty.</p>
                    <p class="text-muted">Click Add to Cart on any product to view it here.</p>
                </div>
            `;
            return;
        }

        const totalAmount = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);

        const productHtml = cartItems.map(item => `
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom" style="position: relative;">
                <div class="me-3" style="width: 60px; min-width: 60px;">
                    <img src="${item.image}" alt="${item.name}" class="img-fluid rounded" />
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size: 14px;">${item.name}</div>
                    <div class="text-muted small">${formatPrice(item.price)} x ${item.quantity}</div>
                </div>
                <div class="fw-bold me-3">${formatPrice(item.price * item.quantity)}</div>
                <button onclick="removeFromCart('${item.id}')" class="btn btn-sm btn-outline-danger" title="Remove">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `).join('');

        const relatedProducts = getRelatedProducts();
        const relatedProductsHtml = relatedProducts.length > 0 ? `
            <div class="mt-3 pt-3 border-top">
                <h6 class="mb-3" style="font-size: 14px;">You might also like</h6>
                ${relatedProducts.map((product, idx) => `
                    <div class="border-bottom pb-3 mb-3">
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <div style="width: 70px; min-width: 70px;">
                                <img src="${product.image}" alt="${product.name}" style="width: 100%; height: 70px; object-fit: cover; border-radius: 4px;" />
                            </div>
                            <div style="flex: 1; font-size: 13px;">
                                <div class="fw-bold" style="margin-bottom: 2px; font-size: 14px;">${product.name.substring(0, 35)}${product.name.length > 35 ? '...' : ''}</div>
                                <div class="text-muted small" style="margin-bottom: 4px;">${product.sub.substring(0, 40)}${product.sub.length > 40 ? '...' : ''}</div>
                                <div class="fw-bold" style="color: #d32f2f; margin-bottom: 6px;">${formatPrice(product.price)}</div>
                                <button onclick="addRelatedToCart('${product.id}', '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}', '${(product.sku || '').replace(/'/g, "\\'")}', 1)" class="btn btn-sm btn-dark" style="font-size: 12px;">Add</button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        ` : '';

        cartContentEl.innerHTML = `
            <div class="cart-items">
                ${productHtml}
            </div>
            ${relatedProductsHtml}
            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Subtotal</strong>
                    <strong>${formatPrice(totalAmount)}</strong>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-dark" id="checkoutPageBtn">Proceed to Checkout</button>
                </div>
            </div>
        `;
    }

    function goToCheckoutPage() {
        if (!window.NivisCart) return;

        const cart = window.NivisCart.toCart();
        if (!cart.items.length || cart.total <= 0) {
            alert('Your cart is empty.');
            return;
        }

        window.location.href = 'checkout.php';
    }

    function openCartDrawer() {
        if (!cartOffcanvas || !cartDrawerEl) return;
        cartOffcanvas.show();
    }

    function addProductToCart(item) {
        if (!window.NivisCart || !item || !item.name) return;
        window.NivisCart.add(item, item.quantity || 1);
        renderCart();
        openCartDrawer();
    }

    window.addProductToCart = addProductToCart;

    function initFooterCart() {
        if (window.__nivisFooterCartReady) return;
        window.__nivisFooterCartReady = true;

        cartItems = window.NivisCart ? window.NivisCart.read() : [];
        cartOffcanvas = window.bootstrap ? bootstrap.Offcanvas.getOrCreateInstance(cartDrawerEl) : null;
        renderCart();
        cartDrawerEl.addEventListener('show.bs.offcanvas', renderCart);

        document.body.addEventListener('click', event => {
            const checkoutButton = event.target.closest('#checkoutPageBtn');
            if (checkoutButton) {
                event.preventDefault();
                goToCheckoutPage();
                return;
            }

            const button = event.target.closest('.btn-cart, .video_section_add_btn');
            if (!button) return;

            const card = button.closest('.product-card');
            const product = window.NivisCart ? window.NivisCart.fromCard(card) : getProductInfoFromCard(card);
            if (!product) return;

            addProductToCart(product);
            event.preventDefault();
        });

        window.addEventListener('nivis-cart:updated', renderCart);
    }

    function ensureNivisCart(callback) {
        if (window.NivisCart) {
            callback();
            return;
        }

        const existingScript = document.querySelector('script[src*="graphql-client.js"]');
        const script = existingScript || document.createElement('script');
        let attempts = 0;

        const waitForCart = () => {
            if (window.NivisCart || attempts >= 20) {
                callback();
                return;
            }

            attempts += 1;
            setTimeout(waitForCart, 100);
        };

        script.addEventListener('load', waitForCart, {
            once: true
        });

        script.addEventListener('error', waitForCart, {
            once: true
        });

        if (!existingScript) {
            script.src = 'assets/js/graphql-client.js';
            document.head.appendChild(script);
        } else {
            waitForCart();
        }
    }

    function bootFooterCart() {
        if (!cartDrawerEl || !cartContentEl) return;
        ensureNivisCart(initFooterCart);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootFooterCart, {
            once: true
        });
    } else {
        bootFooterCart();
    }
</script>
</body>
