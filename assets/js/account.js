(() => {
    const drawer = document.getElementById('accountDrawer');
    if (!drawer) return;

    const content = document.getElementById('accountDrawerContent');
    const footer = document.getElementById('accountDrawerFooter');
    const escape = value => String(value ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));

    function renderOrder(order) {
        const total = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: order.currency || 'INR'
        }).format(Number(order.amount_subunits || 0) / 100);
        const items = Array.isArray(order.items) ? order.items : [];
        return `<article class="account-order">
            <h4>Order ${escape(order.order_id)}</h4>
            <span class="account-status">${escape(order.payment_status_label || 'Payment pending')}</span>
            <p class="account-date">${escape(order.created_at)}</p>
            <ul>${items.map(item => `<li>${escape(item.name)} <strong>× ${escape(item.quantity)}</strong></li>`).join('')}</ul>
            <strong>Total: ${escape(total)}</strong>
        </article>`;
    }

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
            const orders = Array.isArray(data.orders) ? data.orders : [];
            const paid = orders.filter(order => order.payment_status === 'paid');
            const quantity = paid.reduce((sum, order) => sum + (order.items || []).reduce((n, item) => n + Number(item.quantity || 0), 0), 0);

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
            <div class="account-stats"><div><strong>${paid.length}</strong><span>Paid orders</span></div><div><strong>${quantity}</strong><span>Products purchased</span></div></div>
            <section><h3>Paid orders (${paid.length})</h3>${paid.length ? paid.map(renderOrder).join('') : '<div class="account-panel"><p>No paid orders yet.</p></div>'}</section>
            <section><h3>My orders (${orders.length})</h3>${orders.length ? orders.map(renderOrder).join('') : '<div class="account-panel"><p>No orders linked to this account yet. New checkout orders will appear here.</p></div>'}</section>`;

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
