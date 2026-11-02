(() => {
    const form = document.getElementById('orderTrackingForm');
    const message = document.getElementById('orderTrackingMessage');
    if (!form || !message) return;

    const orderIdInput = document.getElementById('orderTrackingId');
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    }[character]));

    function showSignIn(orderId) {
        message.textContent = 'Sign in to your account to view this order.';
        const link = document.createElement('a');
        const loginUrl = new URL('login.php', window.location.href);
        loginUrl.searchParams.set('mode', 'login');
        loginUrl.searchParams.set('return', 'tracking.php');
        loginUrl.searchParams.set('order_id', orderId);
        link.href = loginUrl.href;
        link.textContent = 'Sign in';
        link.className = 'order-tracking-sign-in';
        message.append(' ', link);
    }

    async function findOrder(orderId) {
        message.textContent = 'Checking your account orders…';

        try {
            const response = await fetch('account_dashboard.php', {
                cache: 'no-store',
                credentials: 'same-origin'
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Unable to load your orders. Please try again.');
            }
            if (!data.authenticated) {
                showSignIn(orderId);
                return;
            }

            const order = (Array.isArray(data.orders) ? data.orders : []).find(entry =>
                String(entry.order_id ?? '').trim().toLowerCase() === orderId.toLowerCase()
            );
            if (!order) {
                message.textContent = 'No order with that ID was found in your account.';
                return;
            }
            if (!window.NivisAccountOrders?.open) {
                throw new Error('Order details could not be displayed. Please refresh and try again.');
            }

            message.textContent = `Order #${order.order_id} found.`;
            window.NivisAccountOrders.open(order, 'Back to tracking');
        } catch (error) {
            message.textContent = error instanceof Error
                ? error.message
                : 'Unable to load your order. Please try again.';
        }
    }

    form.addEventListener('submit', event => {
        event.preventDefault();
        const orderId = orderIdInput.value.trim();
        if (!orderId || orderId.length > 100) {
            message.textContent = 'Please enter a valid order ID.';
            return;
        }
        findOrder(orderId);
    });

    if (form.dataset.autoTrack === 'true') {
        findOrder(orderIdInput.value.trim());
    }
})();
