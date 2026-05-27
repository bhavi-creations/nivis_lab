<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 text-center">
                <h6>Information</h6>
                <ul>
                    <li><a href="tracking.php">Track Your Order</a></li>
                    <li><a href="our-story.php">The /PHD/ Story</a></li>
                    <li><a href="our_team.php">The /PHD/ Council</a></li>
                    -->
                    <li><a href="skinthesis.php">Skinthesis</a></li>
                    <li><a href="reward.php">Rewards</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4 text-center">
                <h6>Important Links</h6>
                <ul>
                    <li><a href="shipping_returns.php">Shipping & Returns</a></li>
                    <li><a href="terms_condition.php">Terms & Conditions</a></li>
                    <li><a href="privacy_policy.php">Privacy Policy</a></li>
                    <li><a href="refund.php">Refund Policy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="build_phd.php">Let's Build /PHD/</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4 text-center">
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
                <div class="footer-logo">/NIVIS LAB/</div>
                <!-- <p class="text-uppercase small tracking-widest">Proven Honest Derma</p>
                    <div class="copyright">Copyright © 2026 /PHD/. All rights reserved.</div> -->
                <!-- <img src="./assets/img/logo_1 (1).png" alt="" style="width: 200px;"> -->
            </div>
        </div>
    </div>
</footer>



<div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer">
    <div class="offcanvas-header border-bottom">
        <h5>Mee Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="cartContent">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const cartDrawerEl = document.getElementById('cartDrawer');
    const cartContentEl = document.getElementById('cartContent');
    const cartItems = [];
    let cartOffcanvas = null;

    function formatPrice(value) {
        const amount = Number(value || 0);
        return isNaN(amount) ? '₹0' : `₹${amount.toFixed(0)}`;
    }

    function getProductInfoFromCard(card) {
        if (!card) return null;

        const nameEl = card.querySelector('.product-name');
        const priceEl = card.querySelector('.product-price');
        const imgEl = card.querySelector('.product-img-wrap img.img-primary, .product-img-wrap img');

        const name = nameEl ? nameEl.textContent.trim() : 'Product';
        const priceText = priceEl ? priceEl.textContent.replace(/[^0-9.]/g, '').trim() : card.dataset.price || '0';
        const image = imgEl ? imgEl.src : '';
        const quantity = 1;

        return {
            id: card.dataset.productId || name.replace(/\s+/g, '_').toLowerCase(),
            name,
            price: Number(priceText) || Number(card.dataset.price) || 0,
            image,
            quantity,
        };
    }

    function removeFromCart(itemId) {
        const item = cartItems.find(cartItem => cartItem.id === itemId);
        if (item) {
            if (item.quantity > 1) {
                item.quantity -= 1;
            } else {
                const index = cartItems.indexOf(item);
                if (index > -1) {
                    cartItems.splice(index, 1);
                }
            }
            renderCart();
        }
    }

    function getRelatedProducts(currentItem) {
        const allProducts = document.querySelectorAll('.product-card');
        const relatedProducts = [];
        
        allProducts.forEach(product => {
            if (relatedProducts.length < 4) {
                const productId = product.dataset.productId || product.querySelector('.product-name')?.textContent.trim().replace(/\s+/g, '_').toLowerCase();
                
                // Skip if already in cart
                if (cartItems.some(item => item.id === productId)) return;
                
                const name = product.querySelector('.product-name')?.textContent.trim() || '';
                const priceText = product.querySelector('.product-price')?.textContent.replace(/[^0-9.]/g, '').trim() || product.dataset.price || '0';
                const price = Number(priceText) || 0;
                const image = product.querySelector('.product-img-wrap img')?.src || '';
                const sub = product.querySelector('.product-sub')?.textContent.trim() || '';
                
                if (name && image) {
                    relatedProducts.push({
                        id: productId,
                        name,
                        price,
                        image,
                        sub
                    });
                }
            }
        });
        
        return relatedProducts;
    }

    function addRelatedToCart(itemId, itemName, itemPrice, itemImage, quantity = 1) {
        const existing = cartItems.find(item => item.id === itemId);
        if (existing) {
            existing.quantity += quantity;
        } else {
            cartItems.push({
                id: itemId,
                name: itemName,
                price: itemPrice,
                image: itemImage,
                quantity: quantity
            });
        }
        renderCart();
        openCartDrawer();
    }

    function renderCart() {
        if (!cartContentEl) return;

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

        const relatedProductsHtml = cartItems.length > 0 ? (() => {
            const relatedProducts = getRelatedProducts(cartItems[0]);
            if (relatedProducts.length === 0) return '';
            
            const relatedHtml = relatedProducts.map((product, idx) => `
                <div class="border-bottom pb-3 mb-3">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 70px; min-width: 70px;">
                            <img src="${product.image}" alt="${product.name}" style="width: 100%; height: 70px; object-fit: cover; border-radius: 4px;" />
                        </div>
                        <div style="flex: 1; font-size: 13px;">
                            <div class="fw-bold" style="margin-bottom: 2px; font-size: 14px;">${product.name.substring(0, 35)}${product.name.length > 35 ? '...' : ''}</div>
                            <div class="text-muted small" style="margin-bottom: 4px;">${product.sub.substring(0, 40)}${product.sub.length > 40 ? '...' : ''}</div>
                            <div class="fw-bold" style="color: #d32f2f; margin-bottom: 6px;">₹${product.price}</div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <button onclick="changeRelatedQty('${idx}', -1)" class="btn btn-sm btn-outline-secondary" style="padding: 2px 6px; font-size: 12px;">−</button>
                                <span id="qty-${idx}" style="min-width: 20px; text-align: center;">1</span>
                                <button onclick="changeRelatedQty('${idx}', 1)" class="btn btn-sm btn-outline-secondary" style="padding: 2px 6px; font-size: 12px;">+</button>
                                <button onclick="addRelatedToCart('${product.id}', '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}', parseInt(document.getElementById('qty-${idx}').textContent))" class="btn btn-sm btn-dark" style="margin-left: auto; font-size: 12px;">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            return `
                <div class="mt-3 pt-3 border-top">
                    <h6 class="mb-3" style="font-size: 14px;">You might also like</h6>
                    ${relatedHtml}
                </div>
            `;
        })() : '';

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
                    <a href="#" class="btn btn-dark">Go to Checkout</a>
                </div>
            </div>
        `;
    }

    function changeRelatedQty(idx, change) {
        const qtyEl = document.getElementById(`qty-${idx}`);
        if (qtyEl) {
            const current = parseInt(qtyEl.textContent) || 1;
            const newQty = Math.max(1, current + change);
            qtyEl.textContent = newQty;
        }
    }

    function openCartDrawer() {
        if (!cartOffcanvas || !cartDrawerEl) return;
        cartOffcanvas.show();
    }

    function addToCart(item) {
        if (!item || !item.name) return;

        const existing = cartItems.find(cartItem => cartItem.id === item.id);
        if (existing) {
            existing.quantity += item.quantity;
        } else {
            cartItems.push({ ...item });
        }

        renderCart();
        openCartDrawer();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!cartDrawerEl || !cartContentEl) return;
        cartOffcanvas = new bootstrap.Offcanvas(cartDrawerEl);
        renderCart();

        document.body.addEventListener('click', event => {
            const button = event.target.closest('.btn-cart, .video_section_add_btn');
            if (!button) return;

            const card = button.closest('.product-card');
            const product = getProductInfoFromCard(card);
            if (!product) return;

            addToCart(product);
            event.preventDefault();
        });
    });
</script>
</body>