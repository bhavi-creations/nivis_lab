(function () {
    'use strict';
    const toggle = document.getElementById('nivis-support-toggle');
    const panel = document.getElementById('nivis-support-panel');
    if (!toggle || !panel) return;

    function setOpen(open) {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        if (open) panel.querySelector('h2').focus({ preventScroll: true });
        else toggle.focus({ preventScroll: true });
    }

    toggle.addEventListener('click', () => setOpen(panel.hidden));
    panel.querySelector('#nivis-support-close').addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !panel.hidden) setOpen(false);
    });

    panel.querySelector('#nivis-support-start').addEventListener('click', event => {
        event.currentTarget.hidden = true;
        panel.querySelector('#nivis-support-questions').hidden = false;
        panel.querySelector('[data-support-question]').focus({ preventScroll: true });
    });

    const answers = {
        match: '<h3>Find your product matches</h3><p>Start the Skin Assessment, choose your skin type, then select up to three concerns. Add an optional photo or skip it to see products matched to your answers. Open a product to explore its full details.</p><button type="button" class="nivis-support-primary" data-support-assessment>Start skin assessment &rarr;</button>',
        details: '<h3>Get to know a product</h3><p>Choose View Product from your assessment results, or open a product in our collection. The product page includes its description, ingredients and available usage information so you can learn more before ordering.</p><a class="nivis-support-primary" href="products.php">Explore our products &rarr;</a>',
        order: '<h3>Shop your skin care routine</h3><p>Open the product you like, choose Add to Cart and review your items in the cart. Continue to checkout to enter your delivery details and complete your order. Our team can help with product or order questions.</p><a class="nivis-support-primary" href="products.php?category=all">Browse products &rarr;</a>'
    };

    panel.addEventListener('click', event => {
        const question = event.target.closest('[data-support-question]');
        if (question && answers[question.dataset.supportQuestion]) {
            panel.querySelectorAll('[data-support-question]').forEach(button => button.setAttribute('aria-pressed', String(button === question)));
            const answer = panel.querySelector('#nivis-support-answer');
            answer.innerHTML = answers[question.dataset.supportQuestion];
            answer.hidden = false;
            panel.querySelector('#nivis-support-contact').hidden = false;
            answer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        if (event.target.closest('[data-support-assessment]')) {
            setOpen(false);
            if (document.getElementById('skin-assessment')) document.dispatchEvent(new Event('nivis:start-assessment'));
            else window.location.href = 'index.php#skin-assessment';
        }
    });
}());
