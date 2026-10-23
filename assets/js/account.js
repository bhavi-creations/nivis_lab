(() => {
    const drawer = document.getElementById('accountDrawer');
    if (!drawer) return;
    const content = document.getElementById('accountDrawerContent');
    const footer = document.getElementById('accountDrawerFooter');
    const escape = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    async function readJsonResponse(response) {
        const body = await response.text();
        if (!body.trim()) throw new Error(`Server returned an empty response (HTTP ${response.status}).`);
        try { return JSON.parse(body); }
        catch { throw new Error(`Server returned an invalid response (HTTP ${response.status}). Check the PHP/server error log.`); }
    }
    async function loadAccount() {
        content.innerHTML = '<p>Loading your accountï¿½</p>';
        footer.hidden = true;
        try {
            const response = await fetch('account_dashboard.php', {cache:'no-store', credentials:'same-origin'});
            const data = await readJsonResponse(response);
            if (!response.ok) throw new Error(data.error || 'Unable to load account details.');
            if (!data.authenticated) {
                content.innerHTML = '<div class="account-panel"><h3>Your Nivis account</h3><p>Log in to see your details and orders, or sign up to create an account.</p><a class="account-action" href="login.php?mode=login">Sign in / Login</a><a class="account-action account-secondary" href="login.php?mode=signup">Sign up / Create account</a></div>';
                return;
            }
            footer.hidden = false;
            const c = data.customer;
            const orders = data.orders || [];
            const paid = orders.filter(o => ['paid','captured'].includes(String(o.payment_status).toLowerCase()));
            const quantity = paid.reduce((sum, order) => sum + order.items.reduce((n, item) => n + Number(item.quantity || item.qty || 0), 0), 0);
            const spend = paid.reduce((sum, order) => sum + Number(order.amount_subunits || 0), 0);
            const spendText = new Intl.NumberFormat('en-IN', {style:'currency', currency: paid[0]?.currency || 'INR'}).format(spend / 100);
            content.innerHTML = `<section class="account-panel"><h3>Hello, ${escape(c.name)}</h3><dl>${[['Email',c.email],['Phone',c.phone],['Address',c.address]].map(([label,value])=>`<dt>${label}</dt><dd>${escape(value)}</dd>`).join('')}</dl><button type="button" class="account-retry" id="editAccountToggle">Edit Account Details</button><form id="editAccountForm" class="account-edit-form" hidden><p class="account-form-message" id="accountFormMessage" role="status"></p><label>Name<input name="full_name" maxlength="120" value="${escape(c.name)}" required></label><label>Email<input name="email" type="email" maxlength="254" value="${escape(c.email)}" required></label><label>Phone<input name="phone" type="tel" pattern="\\+?[0-9]{10,15}" value="${escape(c.phone)}" required></label><label>Address line 1<input name="address_1" maxlength="255" value="${escape(c.address_1)}" required></label><label>Address line 2<input name="address_2" maxlength="255" value="${escape(c.address_2)}"></label><label>City<input name="city" maxlength="120" value="${escape(c.city)}" required></label><label>State / Province<input name="state" maxlength="120" value="${escape(c.state_code || c.state)}" required></label><label>PIN / Postal code<input name="postcode" maxlength="20" value="${escape(c.postcode)}" required></label><label>Country code<input name="country" minlength="2" maxlength="2" value="${escape(c.country)}" required></label><label>New password (optional)<input name="password" type="password" minlength="8" maxlength="128" autocomplete="new-password"></label><button type="submit" class="account-retry">Save account details</button></form></section><div class="account-stats"><div><strong>${escape(spendText)}</strong><span>Total spent on paid orders (${paid.length})</span></div><div><strong>${quantity}</strong><span>Products purchased</span></div></div><h3>My orders (${orders.length})</h3>`;
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
                message.textContent = 'Saving your detailsâ€¦';
                try {
                    const values = Object.fromEntries(new FormData(form).entries());
                    values.csrf = data.csrf;
                    const saved = await fetch('account_update.php', {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json'}, body:JSON.stringify(values)});
                    const result = await readJsonResponse(saved);
                    if (!saved.ok) throw new Error(result.error || 'Unable to save your account details.');
                    await loadAccount();
                } catch (error) {
                    message.textContent = error.message;
                } finally {
                    button.disabled = false;
                }
            });
            if (!orders.length) content.innerHTML += '<div class="account-panel"><p>No orders linked to this account yet. Your new checkout orders will appear here.</p><button type="button" class="account-retry" data-bs-dismiss="offcanvas">Continue shopping</button></div>';
            for (const order of orders) {
                const total = new Intl.NumberFormat('en-IN', {style:'currency', currency:order.currency}).format(Number(order.amount_subunits)/100);
                content.innerHTML += `<article class="account-order"><h4>Order ${escape(order.order_id)}</h4><span class="account-status">${escape(order.payment_status_label || (order.payment_status === 'paid' ? 'Payment confirmed' : 'Payment pending'))}</span><p class="account-date">${escape(order.created_at)}</p><ul>${order.items.map(item=>`<li>${escape(item.name)} <strong>ï¿½ ${escape(item.quantity)}</strong></li>`).join('')}</ul><strong>Total: ${escape(total)}</strong></article>`;
            }
        } catch (error) {
            content.innerHTML = `<p role="alert">${escape(error.message)}</p><button type="button" class="account-retry" id="accountRetry">Try again</button>`;
            document.getElementById('accountRetry').addEventListener('click', loadAccount);
        }
    }
    drawer.addEventListener('show.bs.offcanvas', loadAccount);
})();
