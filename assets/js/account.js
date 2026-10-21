(() => {
    const drawer = document.getElementById('accountDrawer');
    if (!drawer) return;
    const content = document.getElementById('accountDrawerContent');
    const footer = document.getElementById('accountDrawerFooter');
    const escape = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    async function loadAccount() {
        content.innerHTML = '<p>Loading your account…</p>';
        footer.hidden = true;
        try {
            const response = await fetch('account_dashboard.php', {cache:'no-store', credentials:'same-origin'});
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Unable to load account details.');
            if (!data.authenticated) {
                content.innerHTML = '<div class="account-panel"><h3>Your Nivis account</h3><p>Log in to see your details and orders, or sign up to create an account.</p><a class="account-action" href="login.php?mode=login">Sign in / Login</a><a class="account-action account-secondary" href="login.php?mode=signup">Sign up / Create account</a></div>';
                return;
            }
            footer.hidden = false;
            const c = data.customer;
            const orders = data.orders;
            const paid = orders.filter(o => o.payment_status === 'paid');
            const quantity = paid.reduce((sum, order) => sum + order.items.reduce((n, item) => n + Number(item.quantity), 0), 0);
            content.innerHTML = `<section class="account-panel"><h3>Hello, ${escape(c.name)}</h3><dl>${[['Email',c.email],['Phone',c.phone],['Address',c.address]].map(([label,value])=>`<dt>${label}</dt><dd>${escape(value)}</dd>`).join('')}</dl></section><div class="account-stats"><div><strong>${paid.length}</strong><span>Paid orders</span></div><div><strong>${quantity}</strong><span>Products purchased</span></div></div><h3>My orders (${orders.length})</h3>`;
            if (!orders.length) content.innerHTML += '<div class="account-panel"><p>No orders linked to this account yet. Your new checkout orders will appear here.</p><button type="button" class="account-retry" data-bs-dismiss="offcanvas">Continue shopping</button></div>';
            for (const order of orders) {
                const total = new Intl.NumberFormat('en-IN', {style:'currency', currency:order.currency}).format(Number(order.amount_subunits)/100);
                content.innerHTML += `<article class="account-order"><h4>Order ${escape(order.order_id)}</h4><span class="account-status">${order.payment_status === 'paid' ? 'Payment confirmed' : 'Payment pending'}</span><p class="account-date">${escape(order.created_at)}</p><ul>${order.items.map(item=>`<li>${escape(item.name)} <strong>× ${escape(item.quantity)}</strong></li>`).join('')}</ul><strong>Total: ${escape(total)}</strong></article>`;
            }
        } catch (error) {
            content.innerHTML = `<p role="alert">${escape(error.message)}</p><button type="button" class="account-retry" id="accountRetry">Try again</button>`;
            document.getElementById('accountRetry').addEventListener('click', loadAccount);
        }
    }
    drawer.addEventListener('show.bs.offcanvas', loadAccount);
})();
