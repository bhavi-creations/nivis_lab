<?php
require_once __DIR__ . '/customer_auth.php';
$checkoutAuthenticated = ($_SESSION['auth_backend'] ?? '') === 'evershop' && !empty($_SESSION['backend_access_token']);
require_once __DIR__ . '/razorpay_config.php';
include 'navbar.php';
?>

<div class="container py-5 checkout-page">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="mb-1">Checkout</h1>
            <p class="text-muted mb-0">Review your order and add shipping details before payment.</p>
        </div>
        <a href="products.php" class="btn btn-outline-dark">Continue Shopping</a>
    </div>

    <?php if (!$checkoutAuthenticated): ?>
        <div class="border  p-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3" id="checkoutAccountOptions" style="background: radial-gradient(ellipse at 17% 0%, rgba(54, 157, 211, .20), transparent 35%), radial-gradient(ellipse at 75% 100%, rgba(54, 145, 190, .14), transparent 37%), linear-gradient(90deg, #032641 0%, #042e4b 48%, #02233b 100%); ">
            <div><strong>Checkout options</strong>
                <div class="small text-muted">Sign in to use your saved details, or continue as a guest.</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-dark" href="login.php?mode=login&amp;return=checkout.php">Login / Sign Up</a>
                <button class="btn btn-dark" type="button" id="continueAsGuest">Continue as Guest</button>
            </div>
        </div>
    <?php endif; ?>

    <div id="checkoutEmptyState" class="border p-4 d-none">
        <h5 class="mb-2">Your cart is empty</h5>
        <p class="text-muted mb-3">Add products to the cart before starting checkout.</p>
        <a href="products.php" class="btn btn-dark">Browse Products</a>
    </div>

    <div class="row g-4" id="checkoutLayout">
        <div class="col-lg-7">
            <form id="checkoutForm" class="border bg-white p-4">
                <div class="mb-4">
                    <h5 class="mb-3">Shipping details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full name</label>
                            <input type="text" class="form-control rounded-0" id="full_name" name="full_name" autocomplete="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-0" id="email" name="email" autocomplete="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control rounded-0" id="phone" name="phone" autocomplete="tel" required>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control rounded-0" id="country" name="country" value="IN" required>
                        </div>
                        <div class="col-12">
                            <label for="address_1" class="form-label">Address line 1</label>
                            <input type="text" class="form-control rounded-0" id="address_1" name="address_1" autocomplete="address-line1" required>
                        </div>
                        <div class="col-12">
                            <label for="address_2" class="form-label">Address line 2</label>
                            <input type="text" class="form-control rounded-0" id="address_2" name="address_2" autocomplete="address-line2">
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control rounded-0" id="city" name="city" autocomplete="address-level2" required>
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select rounded-0" id="state" name="state" autocomplete="address-level1" required>
                                <option value="">Select state</option>
                                <option value="KA">Karnataka</option>
                                <option value="MH">Maharashtra</option>
                                <option value="DL">Delhi</option>
                                <option value="TN">Tamil Nadu</option>
                                <option value="TS">Telangana</option>
                                <option value="AP">Andhra Pradesh</option>
                                <option value="KL">Kerala</option>
                                <option value="GJ">Gujarat</option>
                                <option value="RJ">Rajasthan</option>
                                <option value="UP">Uttar Pradesh</option>
                                <option value="WB">West Bengal</option>
                                <option value="HR">Haryana</option>
                                <option value="PB">Punjab</option>
                                <option value="MP">Madhya Pradesh</option>
                                <option value="BR">Bihar</option>
                                <option value="OR">Odisha</option>
                                <option value="AS">Assam</option>
                                <option value="CH">Chandigarh</option>
                                <option value="JH">Jharkhand</option>
                                <option value="UK">Uttarakhand</option>
                                <option value="HP">Himachal Pradesh</option>
                                <option value="CG">Chhattisgarh</option>
                                <option value="GO">Goa</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="postcode" class="form-label">Pincode</label>
                            <input type="text" class="form-control rounded-0" id="postcode" name="postcode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="postal-code" required>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="mb-4" id="paymentMethodsSection" hidden>
                        <h5 class="mb-3">Payment method</h5>
                        <div id="paymentMethodsList" class="d-grid gap-2"></div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 fw-semibold" id="payNowBtn">
                        Calculate Shipping
                    </button>
                    <div class="alert alert-warning rounded-0 mt-3 d-none" id="shippingWarning"></div>
                    <p class="text-muted small mb-0 mt-3">
                        Your shipping details are passed with the order so the payment request has complete checkout information.
                    </p>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="border bg-white p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Order summary</h5>
                    <span class="text-muted" id="checkoutItemCount">0 items</span>
                </div>

                <div id="checkoutSummaryItems" class="d-grid gap-3"></div>

                <div class="border-top mt-4 pt-3">
                    <div class="mb-3">
                        <label for="checkoutCouponCode" class="form-label checkout-coupon-label">Coupon code</label>
                        <div class="input-group checkout-coupon-group">
                            <input type="text" class="form-control checkout-coupon-input" id="checkoutCouponCode" placeholder="Enter coupon code" autocomplete="off">
                            <button type="button" class="btn checkout-coupon-action" id="applyCouponBtn">Apply</button>
                            <button type="button" class="btn checkout-coupon-action d-none" id="removeCouponBtn">Remove</button>
                        </div>
                        <div class="small mt-2" id="couponMessage" role="status" aria-live="polite"></div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong id="checkoutSubtotal">₹0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 d-none" id="checkoutDiscountRow">
                        <span>Discount</span>
                        <strong class="text-success" id="checkoutDiscount">−₹0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <strong id="checkoutShipping">Calculated at confirmation</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="fw-semibold">Total</span>
                        <strong id="checkoutTotal">₹0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="shippingConfirmModal" tabindex="-1" aria-labelledby="shippingConfirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="shippingConfirmTitle">Shipping price updated</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Review your order before continuing to secure payment.</p>
                <div id="shippingConfirmItems" class="d-grid gap-3 mb-3" style="max-height: 260px; overflow-y: auto;"></div>
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong id="shippingConfirmSubtotal"></strong></div>
                    <div class="d-flex justify-content-between mb-2 d-none" id="shippingConfirmDiscountRow"><span>Discount</span><strong class="text-success" id="shippingConfirmDiscount"></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Shipping</span><strong id="shippingConfirmAmount"></strong></div>
                    <div class="d-flex justify-content-between border-top pt-2 fs-5"><span class="fw-semibold">Total</span><strong id="shippingConfirmTotal"></strong></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-0" data-bs-dismiss="modal">Review details</button>
                <button type="button" class="btn btn-dark rounded-0" id="confirmShippingPayment">Proceed to Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const draftKey = 'nivis_checkout_details';
        const form = document.getElementById('checkoutForm');
        const layout = document.getElementById('checkoutLayout');
        const emptyState = document.getElementById('checkoutEmptyState');
        const summaryItems = document.getElementById('checkoutSummaryItems');
        const subtotalEl = document.getElementById('checkoutSubtotal');
        const shippingEl = document.getElementById('checkoutShipping');
        const totalEl = document.getElementById('checkoutTotal');
        const discountRowEl = document.getElementById('checkoutDiscountRow');
        const discountEl = document.getElementById('checkoutDiscount');
        const couponInput = document.getElementById('checkoutCouponCode');
        const couponMessageEl = document.getElementById('couponMessage');
        const applyCouponBtn = document.getElementById('applyCouponBtn');
        const removeCouponBtn = document.getElementById('removeCouponBtn');
        const itemCountEl = document.getElementById('checkoutItemCount');
        const payBtn = document.getElementById('payNowBtn');
        const shippingWarningEl = document.getElementById('shippingWarning');
        const shippingModalEl = document.getElementById('shippingConfirmModal');
        const shippingConfirmItemsEl = document.getElementById('shippingConfirmItems');
        const shippingConfirmSubtotalEl = document.getElementById('shippingConfirmSubtotal');
        const shippingConfirmDiscountRowEl = document.getElementById('shippingConfirmDiscountRow');
        const shippingConfirmDiscountEl = document.getElementById('shippingConfirmDiscount');
        const shippingConfirmAmountEl = document.getElementById('shippingConfirmAmount');
        const shippingConfirmTotalEl = document.getElementById('shippingConfirmTotal');
        const confirmPaymentBtn = document.getElementById('confirmShippingPayment');
        const paymentMethodsSection = document.getElementById('paymentMethodsSection');
        const paymentMethodsList = document.getElementById('paymentMethodsList');
        let confirmingShipping = false;
        let quotedShipping = null;
        let cartPricing = null;
        let appliedCoupon = '';

        function selectedPaymentMethod() {
            return form.querySelector('[name="checkout_payment_method"]:checked')?.value || 'razorpay';
        }

        async function loadPaymentMethods() {
            try {
                let cartId = window.NivisCart?.getStoredCartId?.() || null;
                if (!cartId && window.NivisCart?.getCartId) cartId = await window.NivisCart.getCartId();
                if (!cartId) return;

                const response = await fetch('create_razorpay_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'payment_methods',
                        cart_id: cartId
                    })
                });
                const result = await response.json();
                if (!response.ok || !result.success || !Array.isArray(result.payment_methods)) return;

                paymentMethodsList.replaceChildren();
                const supportedMethods = result.payment_methods.filter((method) => ['razorpay', 'cod'].includes(method.code));
                supportedMethods.forEach((method, index) => {
                    const label = document.createElement('label');
                    label.className = 'border p-3 d-flex align-items-center gap-2';
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'checkout_payment_method';
                    radio.value = method.code;
                    radio.checked = method.code === 'razorpay' || (!supportedMethods.some((entry) => entry.code === 'razorpay') && index === 0);
                    const text = document.createElement('span');
                    text.textContent = method.name;
                    label.append(radio, text);
                    paymentMethodsList.append(label);
                });
                if (supportedMethods.length) paymentMethodsSection.hidden = false;
            } catch (error) {
                console.warn('Unable to load EverShop payment methods:', error);
            }
        }

        function formatPrice(value) {
            const amount = Number(value || 0);
            return `₹${amount.toFixed(0)}`;
        }

        function getCart() {
            return window.NivisCart ? window.NivisCart.toCart() : {
                items: [],
                total: 0,
                count: 0
            };
        }

        function priceText(price, fallback = 0) {
            return price?.text || formatPrice(price?.value ?? fallback);
        }

        function updateCartPricing(summary) {
            cartPricing = summary || null;
            const cart = getCart();
            subtotalEl.textContent = priceText(cartPricing?.subTotal, cart.total);
            const discount = Math.abs(Number(cartPricing?.discountAmount?.value || 0));
            discountRowEl.classList.toggle('d-none', discount <= 0);
            discountEl.textContent = `−${formatPrice(discount)}`;
            totalEl.textContent = priceText(cartPricing?.grandTotal, cart.total);
            appliedCoupon = String(cartPricing?.coupon || '');
            couponInput.value = appliedCoupon;
            applyCouponBtn.classList.toggle('d-none', Boolean(appliedCoupon));
            removeCouponBtn.classList.toggle('d-none', !appliedCoupon);
        }

        async function sendCouponAction(action, coupon = '') {
            let cartId = window.NivisCart?.getStoredCartId?.() || null;
            if (!cartId && window.NivisCart?.refresh) cartId = await window.NivisCart.refresh();
            if (!cartId) throw new Error('Your cart is unavailable. Refresh checkout and try again.');
            const response = await fetch('evershop_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    action,
                    coupon,
                    cart_id: cartId
                })
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Unable to update the coupon.');
            return result;
        }

        async function refreshCouponPricing() {
            const cartId = window.NivisCart?.getStoredCartId?.();
            if (!cartId) return;
            const response = await fetch('evershop_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    action: 'cart_summary',
                    cart_id: cartId
                })
            });
            const result = await response.json();
            if (response.ok && result.success) updateCartPricing(result.summary);
        }

        function getDraft() {
            try {
                return JSON.parse(localStorage.getItem(draftKey) || '{}') || {};
            } catch (error) {
                return {};
            }
        }

        function saveDraft() {
            if (!form) return;
            const data = Object.fromEntries(new FormData(form).entries());
            localStorage.setItem(draftKey, JSON.stringify(data));
        }

        function applyDraft() {
            const draft = getDraft();
            ['full_name', 'email', 'phone', 'country', 'address_1', 'address_2', 'city', 'state', 'postcode'].forEach((field) => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input && draft[field]) {
                    input.value = draft[field];
                }
            });
        }

        async function applySavedCustomerDetails() {
            try {
                const response = await fetch('checkout_customer.php', {
                    credentials: 'same-origin',
                    cache: 'no-store'
                });
                const result = await response.json();
                if (!response.ok || !result.authenticated || !result.customer) return;
                const customer = result.customer;
                ['full_name', 'email', 'phone', 'country', 'address_1', 'address_2', 'city', 'postcode'].forEach((field) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input && customer[field]) input.value = customer[field];
                });
                const state = form.querySelector('[name="state"]');
                if (state && customer.state) {
                    const matchingOption = Array.from(state.options).find(option =>
                        option.value.toLowerCase() === customer.state.toLowerCase() || option.textContent.trim().toLowerCase() === customer.state.toLowerCase()
                    );
                    if (matchingOption) state.value = matchingOption.value;
                }
                saveDraft();
            } catch (error) {
                console.warn('Unable to prefill saved customer details:', error);
            }
        }

        function renderSummary() {
            const cart = getCart();
            if (!cart.items.length) {
                layout.classList.add('d-none');
                emptyState.classList.remove('d-none');
                return;
            }

            layout.classList.remove('d-none');
            emptyState.classList.add('d-none');

            summaryItems.innerHTML = cart.items.map((item) => `
            <div class="nivis-checkout-item pb-3 border-bottom">
                <div class="nivis-checkout-item__image" style="width: 64px; min-width: 64px;">
                    <img src="${item.image || ''}" alt="${item.name || 'Product'}" class="img-fluid" style="width: 64px; height: 64px; object-fit: cover;">
                </div>
                <div class="nivis-checkout-item__copy">
                    <div class="fw-semibold">${item.name || 'Product'}</div>
                    <div class="text-muted small">${window.NivisPricing.html(item.price)} x ${item.quantity || 1}</div>
                </div>
                <div class="nivis-checkout-item__total fw-semibold">${window.NivisPricing.html(Number(item.price || 0) * Number(item.quantity || 1))}</div>
            </div>
        `).join('');

            subtotalEl.textContent = formatPrice(cart.total);
            totalEl.textContent = formatPrice(cart.total);
            if (cartPricing) updateCartPricing(cartPricing);
            itemCountEl.textContent = `${cart.count} item${cart.count === 1 ? '' : 's'}`;
        }

        function renderShippingConfirmation(cart, shippingAmount, summary = null) {
            shippingConfirmItemsEl.replaceChildren();
            cart.items.forEach((item) => {
                const row = document.createElement('div');
                row.className = 'd-flex justify-content-between align-items-start gap-3 border-bottom pb-2';

                const details = document.createElement('div');
                const name = document.createElement('div');
                name.className = 'fw-semibold';
                name.textContent = item.name || 'Product';
                const quantity = document.createElement('div');
                quantity.className = 'small text-muted';
                quantity.textContent = `${formatPrice(item.price)} × ${item.quantity || 1}`;
                details.append(name, quantity);

                const lineTotal = document.createElement('strong');
                lineTotal.className = 'text-nowrap';
                lineTotal.textContent = formatPrice(Number(item.price || 0) * Number(item.quantity || 1));
                row.append(details, lineTotal);
                shippingConfirmItemsEl.append(row);
            });

            const subtotal = Number(summary?.subTotal?.value ?? cart.total);
            const discount = Math.abs(Number(summary?.discountAmount?.value || 0));
            shippingConfirmSubtotalEl.textContent = priceText(summary?.subTotal, cart.total);
            shippingConfirmDiscountRowEl.classList.toggle('d-none', discount <= 0);
            shippingConfirmDiscountEl.textContent = `−${formatPrice(discount)}`;
            shippingConfirmAmountEl.textContent = shippingAmount === 0 ? 'Free' : formatPrice(shippingAmount);
            shippingConfirmTotalEl.textContent = priceText(summary?.grandTotal, Math.max(0, subtotal - discount + shippingAmount));
        }

        function buildAddress(formData) {
            return {
                full_name: formData.full_name,
                address_1: formData.address_1,
                address_2: formData.address_2,
                city: formData.city,
                province: formData.state,
                postcode: formData.postcode,
                country: formData.country,
                telephone: formData.phone,
                email: formData.email
            };
        }

        async function verifyPayment(orderId, response) {
            const verifyResponse = await fetch('verify_razorpay_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    items: getCart().items.map(item => ({name: item.name || item.product_name || item.sku || 'Product', quantity: item.quantity || item.qty || 1})),
                    amount_subunits: Number(orderResult.gateway.amount || 0)
                })
            });

            return await verifyResponse.json();
        }

        async function submitCheckout(event) {
            event.preventDefault();

            if (window.NivisCart?.refresh) {
                try {
                    await window.NivisCart.refresh({
                        customer_full_name: form.querySelector('[name="full_name"]')?.value || '',
                        customer_email: form.querySelector('[name="email"]')?.value || ''
                    });
                } catch (error) {
                    console.warn('Unable to refresh EverShop cart before checkout:', error);
                }
            }

            const cart = getCart();
            if (!cart.items.length || cart.total <= 0) {
                alert('Your cart is empty.');
                return;
            }

            const formData = Object.fromEntries(new FormData(form).entries());
            localStorage.setItem(draftKey, JSON.stringify(formData));

            const items = cart.items.map((item) => ({
                id: item.id || item.productId || '',
                sku: item.sku || item.productSku || item.productCode || '',
                name: item.name || item.product_name || '',
                product_name: item.product_name || item.name || '',
                productCode: item.productCode || item.id || '',
                productSku: item.productSku || item.sku || '',
                qty: Math.max(1, Number(item.quantity || 1))
            }));

            let cartId = window.NivisCart?.getStoredCartId ? window.NivisCart.getStoredCartId() : null;
            if (!cartId && window.NivisCart?.getCartId) {
                try {
                    cartId = await window.NivisCart.getCartId();
                } catch (error) {
                    console.warn('Unable to load EverShop cart id before checkout submit:', error);
                }
            }

            payBtn.disabled = true;
            const action = confirmingShipping ? 'create_order' : 'calculate_shipping';
            payBtn.textContent = action === 'calculate_shipping' ? 'Calculating Shipping...' : 'Preparing Payment...';

            try {
                const orderResponse = await fetch('create_razorpay_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action,
                        payment_method: selectedPaymentMethod(),
                        cart_id: cartId,
                        cart_total: cart.total,
                        items,
                        customer_full_name: formData.full_name,
                        customer_email: formData.email,
                        customer_phone: formData.phone,
                        billing_address: buildAddress(formData),
                        shipping_address: buildAddress(formData)
                    })
                });

                const orderText = await orderResponse.text();
                let orderResult = {};
                try {
                    orderResult = orderText ? JSON.parse(orderText) : {};
                } catch (parseError) {
                    orderResult = {
                        success: false,
                        message: orderText || `Unexpected response from create_razorpay_order.php (${orderResponse.status}).`
                    };
                }

                if (!orderResponse.ok) {
                    console.error('create_razorpay_order.php failed', {
                        status: orderResponse.status,
                        response: orderResult,
                        raw: orderText
                    });
                }

                if (!orderResult.success) {
                    const extraMessage = orderResult?.error?.catalog_hint ?
                        ` ${orderResult.error.catalog_hint}` :
                        '';
                    throw new Error((orderResult.message || 'Unable to create the payment order.') + extraMessage);
                }

                if (action === 'calculate_shipping') {
                    quotedShipping = orderResult.shipping;
                    const shippingAmount = Number(quotedShipping?.amount);
                    if (!Number.isFinite(shippingAmount)) {
                        throw new Error('Shipping was calculated, but the amount was missing. Please try again.');
                    }
                    shippingEl.textContent = shippingAmount === 0 ? 'Free' : formatPrice(shippingAmount);
                    shippingEl.title = quotedShipping?.name || '';
                    let summary = null;
                    try {
                        const pricingResult = await sendCouponAction('cart_summary');
                        summary = pricingResult.summary;
                        updateCartPricing(summary);
                    } catch (pricingError) {
                        console.warn('Unable to refresh EverShop totals after shipping calculation:', pricingError);
                    }
                    renderShippingConfirmation(cart, shippingAmount, summary);
                    const selectedLabel = form.querySelector('[name="checkout_payment_method"]:checked')?.parentElement?.querySelector('span')?.textContent || 'Payment method';
                    document.getElementById('shippingConfirmTitle').textContent = `Order summary · ${selectedLabel}`;
                    confirmPaymentBtn.textContent = selectedPaymentMethod() === 'cod' ? 'Place Order' : 'Proceed to Payment';
                    bootstrap.Modal.getOrCreateInstance(shippingModalEl).show();
                    return;
                }

                if (!orderResult.gateway?.razorpayOrderId || !orderResult.gateway?.keyId || !orderResult.order_id) {
                    if (orderResult.payment_method === 'cod' && orderResult.order_id) {
                        window.NivisCart.clear();
                        localStorage.removeItem(draftKey);
                        window.location.replace('index.php');
                        return;
                    }
                    throw new Error(orderResult.message || 'Unable to create the payment order.');
                }

                if (orderResult.shipping_warning && shippingWarningEl) {
                    shippingWarningEl.textContent = orderResult.shipping_warning;
                    shippingWarningEl.classList.remove('d-none');
                } else if (shippingWarningEl) {
                    shippingWarningEl.textContent = '';
                    shippingWarningEl.classList.add('d-none');
                }

                // Use EverShop's selected shipping method and Razorpay order amount
                // so the summary reflects the same total that will be paid.
                const shippingAmount = Number(orderResult.shipping?.amount);
                const payableAmount = Number(orderResult.gateway.amount) / 100;
                if (Number.isFinite(shippingAmount) && shippingEl) {
                    shippingEl.textContent = shippingAmount === 0 ?
                        'Free' :
                        formatPrice(shippingAmount);
                    shippingEl.title = orderResult.shipping?.name || '';
                }
                if (Number.isFinite(payableAmount) && payableAmount > 0) {
                    totalEl.textContent = formatPrice(payableAmount);
                }

                const razorpay = new Razorpay({
                    key: orderResult.gateway.keyId,
                    amount: orderResult.gateway.amount,
                    currency: <?= json_encode(RAZORPAY_CURRENCY) ?>,
                    name: orderResult.company || 'Nivis Labs',
                    description: `${cart.count} item${cart.count === 1 ? '' : 's'}`,
                    order_id: orderResult.gateway.razorpayOrderId,
                    prefill: {
                        name: formData.full_name,
                        email: formData.email,
                        contact: formData.phone
                    },
                    theme: {
                        color: '#0a2b4a'
                    },
                    handler: async function(response) {
                        const verifyResult = await verifyPayment(orderResult.order_id, response);

                        if (!verifyResult.success) {
                            alert(verifyResult.message || 'Payment verification failed.');
                            return;
                        }

                        window.NivisCart.clear();
                        localStorage.removeItem(draftKey);
                        window.location.replace('index.php');
                    },
                    modal: {
                        ondismiss: function() {
                            payBtn.disabled = false;
                            payBtn.textContent = 'Calculate Shipping';
                        }
                    }
                });

                razorpay.open();
            } catch (error) {
                alert(error.message || 'Unable to start payment.');
            } finally {
                payBtn.disabled = false;
                payBtn.textContent = 'Calculate Shipping';
                confirmingShipping = false;
            }
        }

        async function initCheckout() {
            applyDraft();
            await applySavedCustomerDetails();
            document.getElementById('continueAsGuest')?.addEventListener('click', () => {
                document.getElementById('checkoutAccountOptions')?.remove();
                document.getElementById('full_name')?.focus();
            });
            if (window.NivisCart?.refresh) {
                try {
                    await window.NivisCart.refresh();
                } catch (error) {
                    console.warn('Unable to refresh EverShop cart on checkout load:', error);
                }
            }
            renderSummary();
            await loadPaymentMethods();
            await refreshCouponPricing();
            form.addEventListener('submit', submitCheckout);
            form.addEventListener('input', saveDraft);
            applyCouponBtn.addEventListener('click', async () => {
                const coupon = couponInput.value.trim();
                if (!coupon) {
                    couponMessageEl.textContent = 'Enter a coupon code.';
                    couponMessageEl.className = 'small mt-2 text-danger';
                    return;
                }
                applyCouponBtn.disabled = true;
                couponMessageEl.textContent = 'Applying coupon…';
                couponMessageEl.className = 'small mt-2 text-muted';
                try {
                    const result = await sendCouponAction('apply_coupon', coupon);
                    updateCartPricing(result.summary);
                    couponMessageEl.textContent = `Coupon ${result.summary.coupon || coupon} applied.`;
                    couponMessageEl.className = 'small mt-2 text-success';
                } catch (error) {
                    couponMessageEl.textContent = error.message || 'Coupon could not be applied.';
                    couponMessageEl.className = 'small mt-2 text-danger';
                } finally {
                    applyCouponBtn.disabled = false;
                }
            });
            couponInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    applyCouponBtn.click();
                }
            });
            removeCouponBtn.addEventListener('click', async () => {
                removeCouponBtn.disabled = true;
                try {
                    const result = await sendCouponAction('remove_coupon', appliedCoupon);
                    updateCartPricing(result.summary);
                    couponMessageEl.textContent = 'Coupon removed.';
                    couponMessageEl.className = 'small mt-2 text-muted';
                } catch (error) {
                    couponMessageEl.textContent = error.message || 'Coupon could not be removed.';
                    couponMessageEl.className = 'small mt-2 text-danger';
                } finally {
                    removeCouponBtn.disabled = false;
                }
            });
            confirmPaymentBtn.addEventListener('click', () => {
                if (!quotedShipping) return;
                confirmingShipping = true;
                bootstrap.Modal.getOrCreateInstance(shippingModalEl).hide();
                form.requestSubmit();
            });
            window.addEventListener('nivis-cart:updated', renderSummary);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCheckout, {
                once: true
            });
        } else {
            initCheckout();
        }
    })();
</script>

<?php include 'footer.php'; ?>
