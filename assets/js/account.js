(() => {
    const drawer = document.getElementById('accountDrawer');
    if (!drawer) return;

    const content = document.getElementById('accountDrawerContent');
    const footer = document.getElementById('accountDrawerFooter');
    let currentOrders = [];

    const escape = value => String(value ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));

    function money(value, currency = 'INR') {
        try {
            return new Intl.NumberFormat('en-IN', { style: 'currency', currency }).format(Number(value || 0));
        } catch {
            return `${currency} ${Number(value || 0).toFixed(2)}`;
        }
    }

    function trackingUrl(order) {
        const awb = order.shipment?.awb;
        if (!awb) return '';
        const raw = order.shipment?.tracking_url || `https://shiprocket.co/tracking/${encodeURIComponent(awb)}`;
        try {
            const url = new URL(raw, window.location.origin);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
        } catch {
            return '';
        }
    }

    function renderOrderRow(order) {
        const awb = order.shipment?.awb || '';
        const href = trackingUrl(order);
        const awbMarkup = awb && href
            ? `<a class="account-order-awb" href="${escape(href)}" target="_blank" rel="noopener noreferrer">AWB: ${escape(awb)}</a>`
            : `<span class="account-order-awb account-order-awb-pending">${awb ? `AWB: ${escape(awb)}` : 'AWB pending'}</span>`;
        return `<div class="account-order-row">
            <button type="button" class="account-order-open" data-account-order="${escape(order.uuid)}">Order #${escape(order.order_id)}</button>
            ${awbMarkup}
        </div>`;
    }

    function renderAddress(address) {
        if (!address) return '<span class="account-detail-muted">Address not available</span>';
        const lines = [
            address.full_name, address.address_1, address.address_2,
            address.city, address.province, address.postcode, address.country,
            address.telephone
        ].filter(Boolean);
        return lines.length ? lines.map(line => `<div>${escape(line)}</div>`).join('') : '<span class="account-detail-muted">Address not available</span>';
    }

    function renderOrderDetail(order) {
        const currency = order.currency || 'INR';
        const items = Array.isArray(order.items) ? order.items : [];
        const shipment = order.shipment || {};
        const href = trackingUrl(order);
        const weight = order.total_weight || {};
        const activities = Array.isArray(order.activities) ? order.activities : [];
        const itemRows = items.length ? items.map(item => {
            const image = String(item.thumbnail || '');
            const safeImage = /^(https?:\/\/|\/)/i.test(image) ? image : '';
            return `<div class="account-detail-item">
                <div class="account-detail-thumb">${safeImage ? `<img src="${escape(safeImage)}" alt="${escape(item.name)}" onerror="this.onerror=null;this.src='/assets/img/product.webp'">` : ''}<span>${escape(item.quantity)}</span></div>
                <div class="account-detail-product"><strong>${escape(item.name)}</strong><small>SKU: ${escape(item.sku || '—')}</small></div>
                <div class="account-detail-unit">${escape(money(item.unit_price, currency))} × ${escape(item.quantity)}</div>
                <strong class="account-detail-line-total">${escape(money(item.line_total, currency))}</strong>
            </div>`;
        }).join('') : '<p class="account-detail-muted">No item details available.</p>';
        const activityRows = activities.length ? activities.map(activity => `<div class="account-detail-activity"><time>${escape(activity.created_at)}</time><p>${escape(activity.comment || 'Order updated')}</p></div>`).join('') : '<p class="account-detail-muted">No order activity yet.</p>';

        return `<div class="account-detail-overlay" role="presentation">
            <section class="account-detail-shell" role="dialog" aria-modal="true" aria-labelledby="accountOrderTitle" tabindex="-1">
                <div class="account-detail-heading">
                    <button type="button" class="account-detail-back" data-close-order-detail>← Back to orders</button>
                    <div class="account-detail-heading-main">
                        <h1 id="accountOrderTitle">Order #${escape(order.order_id)}</h1>
                        <span class="account-detail-badge">${escape(order.status_label || order.payment_status_label || 'Processing')}</span>
                    </div>
                </div>
                <div class="account-detail-grid">
                    <main class="account-detail-main">
                        <section class="account-detail-card">
                            <h2><span class="account-detail-status-dot"></span>${escape(order.shipment_status_label || 'Order items')}</h2>
                            <div class="account-detail-items">${itemRows}</div>
                        </section>
                        <section class="account-detail-card">
                            <h2><span class="account-detail-status-dot account-detail-status-paid"></span>${escape(order.payment_status_label || 'Payment')} – ${escape(order.payment_method || 'Payment method')}</h2>
                            <div class="account-detail-totals">
                                <div><span>Subtotal</span><strong>${escape(money(order.subtotal, currency))}</strong></div>
                                <div><span>Total product weight</span><strong>${escape(weight.value ?? 0)} ${escape(weight.unit || '')}</strong></div>
                                <div><span>Shipping${order.shipping_method ? ` · ${escape(order.shipping_method)}` : ''}</span><strong>${escape(money(order.shipping_amount, currency))}</strong></div>
                                ${Number(order.discount_amount) ? `<div><span>Discount${order.coupon ? ` · ${escape(order.coupon)}` : ''}</span><strong class="account-detail-discount">−${escape(money(Math.abs(Number(order.discount_amount)), currency))}</strong></div>` : ''}
                                <div><span>Tax</span><strong>${escape(money(order.tax_amount, currency))}</strong></div>
                                <div class="account-detail-total"><span>Total</span><strong>${escape(money(Number(order.amount_subunits || 0) / 100, currency))}</strong></div>
                            </div>
                        </section>
                        <section class="account-detail-card">
                            <h2>Activities</h2>
                            <div class="account-detail-activities">${activityRows}</div>
                        </section>
                    </main>
                    <aside class="account-detail-aside">
                        <section class="account-detail-card">
                            <h2>Shiprocket shipping</h2>
                            ${shipment.awb ? `<dl><dt>AWB</dt><dd>${href ? `<a href="${escape(href)}" target="_blank" rel="noopener noreferrer">${escape(shipment.awb)} ↗</a>` : escape(shipment.awb)}</dd>${shipment.courier ? `<dt>Courier</dt><dd>${escape(shipment.courier)}</dd>` : ''}${shipment.status ? `<dt>Status</dt><dd>${escape(shipment.status)}</dd>` : ''}</dl>` : '<p class="account-detail-muted">AWB pending assignment</p>'}
                        </section>
                        <section class="account-detail-card">
                            <h2>Customer notes</h2>
                            <p>${order.shipping_note ? escape(order.shipping_note) : '<span class="account-detail-muted">No notes from customer</span>'}</p>
                        </section>
                        <section class="account-detail-card">
                            <h2>Customer Information</h2>
                            <p>${escape(order.customer_name)}</p>
                            <div class="account-detail-divider"><h3>Contact Information</h3><p>${escape(order.customer_email)}</p>${order.shipping_address?.telephone ? `<p>${escape(order.shipping_address.telephone)}</p>` : ''}</div>
                            ${!order.no_shipping_required ? `<div class="account-detail-divider"><h3>Shipping Address</h3>${renderAddress(order.shipping_address)}</div>` : ''}
                            <div class="account-detail-divider"><h3>Billing address</h3>${renderAddress(order.billing_address)}</div>
                        </section>
                    </aside>
                </div>
            </section>
        </div>`;
    }

    function openOrderDetail(order) {
        document.querySelector('.account-detail-overlay')?.remove();
        const wrapper = document.createElement('div');
        wrapper.innerHTML = renderOrderDetail(order);
        const overlay = wrapper.firstElementChild;
        document.body.append(overlay);
        document.body.classList.add('account-detail-open');
        const close = () => {
            overlay.remove();
            document.body.classList.remove('account-detail-open');
            document.removeEventListener('keydown', onKeydown);
        };
        const onKeydown = event => { if (event.key === 'Escape') close(); };
        overlay.addEventListener('click', event => {
            if (event.target === overlay || event.target.closest('[data-close-order-detail]')) close();
        });
        document.addEventListener('keydown', onKeydown);
        overlay.querySelector('.account-detail-shell').focus();
    }

    content.addEventListener('click', event => {
        const button = event.target.closest('[data-account-order]');
        if (!button) return;
        const order = currentOrders.find(entry => entry.uuid === button.dataset.accountOrder);
        if (order) openOrderDetail(order);
    });

    async function loadAccount() {
        content.innerHTML = '<p>Loading your account…</p>';
        footer.hidden = true;
        try {
            const response = await fetch('/account_dashboard', {
                cache: 'no-store',
                credentials: 'same-origin'
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Unable to load account details.');
            if (!data.authenticated) {
                content.innerHTML = '<div class="account-panel"><h3>Your Nivis account</h3><p>Log in to see your details and orders, or sign up to create an account.</p><a class="account-action" href="/login?mode=login">Sign in / Login</a><a class="account-action account-secondary" href="/login?mode=signup">Sign up / Create account</a></div>';
                return;
            }

            footer.hidden = false;
            const customer = data.customer || {};
            currentOrders = Array.isArray(data.orders) ? data.orders : [];
            const paid = Array.isArray(data.paid_orders)
                ? data.paid_orders
                : currentOrders.filter(order => order.payment_status === 'paid');
            const paidCount = Number.isFinite(Number(data.paid_orders_count))
                ? Number(data.paid_orders_count)
                : paid.length;
            const quantity = Number.isFinite(Number(data.products_purchased))
                ? Number(data.products_purchased)
                : paid.reduce((sum, order) => sum + Number(order.quantity || (order.items || []).reduce((n, item) => n + Number(item.quantity || 0), 0)), 0);

            content.innerHTML = `<section class="account-panel">
                <h3>Hello, ${escape(customer.name)}</h3>
                <dl>${[['Email', customer.email], ['Phone', customer.phone], ['Address', customer.address]].map(([label, value]) => `<dt>${label}</dt><dd>${escape(value)}</dd>`).join('')}</dl>
                <button type="button" class="account-retry" id="editAccountToggle">Edit Account Details</button>
                <form id="editAccountForm" class="account-edit-form" hidden>
                    <p class="account-form-message" id="accountFormMessage" role="status"></p>
                    <label>Name<input name="full_name" maxlength="120" value="${escape(customer.name)}" required></label>
                    <label>Email<input name="email" type="email" maxlength="254" value="${escape(customer.email)}" required></label>
                    <label>Phone<input name="phone" type="tel" pattern="\\+?[0-9]{10,15}" value="${escape(customer.phone)}" required></label>
                    <label>Address line 1<input name="address_1" maxlength="255" value="${escape(customer.address_1)}" required></label>
                    <label>Address line 2<input name="address_2" maxlength="255" value="${escape(customer.address_2)}"></label>
                    <label>City<input name="city" maxlength="120" value="${escape(customer.city)}" required></label>
                    <label>State / Province<input name="state" maxlength="120" value="${escape(customer.state_code || customer.state)}" required></label>
                    <label>PIN / Postal code<input name="postcode" maxlength="20" value="${escape(customer.postcode)}" required></label>
                    <label>Country code<input name="country" minlength="2" maxlength="2" value="${escape(customer.country || 'IN')}" required></label>
                    <label>New password (optional)<input name="password" type="password" minlength="8" maxlength="128" autocomplete="new-password"></label>
                    <button type="submit" class="account-retry">Save account details</button>
                </form>
            </section>
            <div class="account-stats"><div><strong>${paidCount}</strong><span>Paid orders</span></div><div><strong>${quantity}</strong><span>Products purchased</span></div></div>
            <section class="account-order-section"><h3>Paid orders (${paidCount})</h3>${paid.length ? paid.map(renderOrderRow).join('') : '<div class="account-panel"><p>No paid orders yet.</p></div>'}</section>
            <section class="account-order-section"><h3>My orders (${currentOrders.length})</h3>${currentOrders.length ? currentOrders.map(renderOrderRow).join('') : '<div class="account-panel"><p>No orders linked to this account yet. New checkout orders will appear here.</p></div>'}</section>`;

            document.getElementById('editAccountToggle').addEventListener('click', () => {
                const form = document.getElementById('editAccountForm');
                form.hidden = !form.hidden;
            });
            document.getElementById('editAccountForm').addEventListener('submit', async event => {
                event.preventDefault();
                const form = event.currentTarget;
                const button = form.querySelector('button[type="submit"]');
                const message = document.getElementById('accountFormMessage');
                button.disabled = true;
                message.textContent = 'Saving your details…';
                try {
                    const values = Object.fromEntries(new FormData(form).entries());
                    values.csrf = data.csrf;
                    const saved = await fetch('/account_update', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(values)
                    });
                    const result = await saved.json();
                    if (!saved.ok) throw new Error(result.error || 'Unable to save your account details.');
                    await loadAccount();
                } catch (error) {
                    message.textContent = error.message;
                } finally {
                    button.disabled = false;
                }
            });
        } catch (error) {
            content.innerHTML = `<p role="alert">${escape(error.message)}</p><button type="button" class="account-retry" id="accountRetry">Try again</button>`;
            document.getElementById('accountRetry').addEventListener('click', loadAccount);
        }
    }

    drawer.addEventListener('show.bs.offcanvas', loadAccount);
})();
