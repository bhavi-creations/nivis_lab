(() => {
    const drawer = document.getElementById('accountDrawer');
    if (!drawer) return;

    const content = document.getElementById('accountDrawerContent');
    const footer = document.getElementById('accountDrawerFooter');
    const escape = value => String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[char]));

    const formatMoney = (subunits, currency) => {
        try {
            return new Intl.NumberFormat('en-IN', { style: 'currency', currency: currency || 'INR' })
                .format(Number(subunits || 0) / 100);
        } catch (_) {
            return `${currency || 'INR'} ${(Number(subunits || 0) / 100).toFixed(2)}`;
        }
    };

    async function loadAccount() {
        content.innerHTML = '<p>Loading your account…</p>';
        footer.hidden = true;

        try {
            const response = await fetch('account_dashboard.php', { cache: 'no-store', credentials: 'same-origin' });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Unable to load account details.');

            if (!data.authenticated) {
                content.innerHTML = '<div class="account-panel"><h3>Your Nivis account</h3><p>Log in to see your details and orders, or sign up to create an account.</p><a class="account-action" href="login.php?mode=login">Sign in / Login</a><a class="account-action account-secondary" href="login.php?mode=signup">Sign up / Create account</a></div>';
                return;
            }

            footer.hidden = false;
            const customer = data.customer || {};
            const orders = Array.isArray(data.orders) ? data.orders : [];
            const paidOrders = orders.filter(order => order.payment_status === 'paid');
            const productsCount = paidOrders.reduce((sum, order) => sum + (order.items || []).reduce((count, item) => count + Number(item.quantity || 0), 0), 0);
            const spentSubunits = paidOrders.reduce((sum, order) => sum + Number(order.amount_subunits || 0), 0);
            const savedSubunits = paidOrders.reduce((sum, order) => sum + Number(order.discount_subunits || 0), 0);
            const currency = paidOrders[0]?.currency || orders[0]?.currency || 'INR';
            const savedText = data.discount_totals_available ? formatMoney(savedSubunits, currency) : 'Not available';

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
            <div class="account-stats">
                <div><strong>${paidOrders.length}</strong><span>Paid orders</span></div>
                <div><strong>${productsCount}</strong><span>Products purchased</span></div>
                <div><strong>${escape(formatMoney(spentSubunits, currency))}</strong><span>Amount spent</span></div>
                <div><strong>${escape(savedText)}</strong><span>Amount saved</span></div>
            </div>
            <h3>My orders (${orders.length})</h3>`;

            document.getElementById('editAccountToggle').addEventListener('click', () => {
                const form = document.getElementById('editAccountForm');
                form.hidden = !form.hidden;
                const message = document.getElementById('accountFormMessage');
                const saveButton = form.querySelector('button[type="submit"]');
                if (!data.account_edit_enabled) {
                    message.textContent = 'Secure account editing is not enabled on the store server yet. Please contact the store administrator.';
                    saveButton.disabled = true;
                }
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
                    const saved = await fetch('account_update.php', {
                        method: 'POST', credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(values)
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

            if (!orders.length) {
                content.insertAdjacentHTML('beforeend', '<div class="account-panel"><p>No orders linked to this account yet. New signed-in orders will appear here.</p><button type="button" class="account-retry" data-bs-dismiss="offcanvas">Continue shopping</button></div>');
            }
            for (const order of orders) {
                const items = Array.isArray(order.items) ? order.items : [];
                const itemList = items.length
                    ? `<ul>${items.map(item => `<li>${escape(item.name || 'Product')} <strong>× ${escape(item.quantity)}</strong></li>`).join('')}</ul>`
                    : '<p>Product details are unavailable for this order.</p>';
                content.insertAdjacentHTML('beforeend', `<article class="account-order">
                    <h4>Order ${escape(order.order_id)}</h4>
                    <span class="account-status">${escape(order.payment_status_label || (order.payment_status === 'paid' ? 'Payment confirmed' : 'Payment pending'))}</span>
                    <p class="account-date">${escape(order.created_at)}</p>${itemList}
                    <strong>Total: ${escape(formatMoney(order.amount_subunits, order.currency))}</strong>
                </article>`);
            }
        } catch (error) {
            content.innerHTML = `<p role="alert">${escape(error.message)}</p><button type="button" class="account-retry" id="accountRetry">Try again</button>`;
            document.getElementById('accountRetry').addEventListener('click', loadAccount);
        }
    }

    drawer.addEventListener('show.bs.offcanvas', loadAccount);
})();
