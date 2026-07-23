<?php
require_once __DIR__ . '/razorpay_config.php';
include 'navbar.php';
?>

<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="mb-1">Checkout</h1>
            <p class="text-muted mb-0">Review your order and add shipping details before payment.</p>
        </div>
        <a href="products.php" class="btn btn-outline-dark">Continue Shopping</a>
    </div>

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
                    <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 fw-semibold" id="payNowBtn">
                        Proceed to Payment
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
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong id="checkoutSubtotal">₹0</strong>
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

<script>
(() => {
    const draftKey = 'nivis_checkout_details';
    const form = document.getElementById('checkoutForm');
    const layout = document.getElementById('checkoutLayout');
    const emptyState = document.getElementById('checkoutEmptyState');
    const summaryItems = document.getElementById('checkoutSummaryItems');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotal');
    const itemCountEl = document.getElementById('checkoutItemCount');
    const payBtn = document.getElementById('payNowBtn');
    const shippingWarningEl = document.getElementById('shippingWarning');

    function formatPrice(value) {
        const amount = Number(value || 0);
        return `₹${amount.toFixed(0)}`;
    }

    function getCart() {
        return window.NivisCart ? window.NivisCart.toCart() : { items: [], total: 0, count: 0 };
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
            <div class="d-flex gap-3 pb-3 border-bottom">
                <div style="width: 64px; min-width: 64px;">
                    <img src="${item.image || ''}" alt="${item.name || 'Product'}" class="img-fluid" style="width: 64px; height: 64px; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold">${item.name || 'Product'}</div>
                    <div class="text-muted small">${formatPrice(item.price)} x ${item.quantity || 1}</div>
                </div>
                <div class="fw-semibold">${formatPrice((Number(item.price || 0) * Number(item.quantity || 1)))}</div>
            </div>
        `).join('');

        subtotalEl.textContent = formatPrice(cart.total);
        totalEl.textContent = formatPrice(cart.total);
        itemCountEl.textContent = `${cart.count} item${cart.count === 1 ? '' : 's'}`;
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
                razorpay_signature: response.razorpay_signature
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
        payBtn.textContent = 'Preparing Payment...';

        try {
            const orderResponse = await fetch('create_razorpay_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
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

            if (!orderResult.success || !orderResult.gateway?.razorpayOrderId || !orderResult.gateway?.keyId || !orderResult.order_id) {
                const extraMessage = orderResult?.error?.catalog_hint
                    ? ` ${orderResult.error.catalog_hint}`
                    : '';
                throw new Error((orderResult.message || 'Unable to create the payment order.') + extraMessage);
            }

            if (orderResult.shipping_warning && shippingWarningEl) {
                shippingWarningEl.textContent = orderResult.shipping_warning;
                shippingWarningEl.classList.remove('d-none');
            } else if (shippingWarningEl) {
                shippingWarningEl.textContent = '';
                shippingWarningEl.classList.add('d-none');
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
                    renderSummary();
                    alert('Payment successful. Thank you for your order.');
                },
                modal: {
                    ondismiss: function() {
                        payBtn.disabled = false;
                        payBtn.textContent = 'Proceed to Payment';
                    }
                }
            });

            razorpay.open();
        } catch (error) {
            alert(error.message || 'Unable to start payment.');
        } finally {
            payBtn.disabled = false;
            payBtn.textContent = 'Proceed to Payment';
        }
    }

    async function initCheckout() {
        applyDraft();
        if (window.NivisCart?.refresh) {
            try {
                await window.NivisCart.refresh();
            } catch (error) {
                console.warn('Unable to refresh EverShop cart on checkout load:', error);
            }
        }
        renderSummary();
        form.addEventListener('submit', submitCheckout);
        form.addEventListener('input', saveDraft);
        window.addEventListener('nivis-cart:updated', renderSummary);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCheckout, { once: true });
    } else {
        initCheckout();
    }
})();
</script>

<?php include 'footer.php'; ?>
