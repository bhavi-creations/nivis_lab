(function (root, factory) {
    const pricing = factory();
    if (typeof module === 'object' && module.exports) module.exports = pricing;
    if (!root || !root.document) return;
    root.NivisPricing = pricing;

    // Includes older product templates as well as cards added by AJAX/carousels.
    const selector = [
        '.product-price', '.price-main', '.skin-product-price', '.hair-result-price',
        '.dermat-routine-step-price', '.dermat-selected-actions > strong',
        '.spotlight-card__price .ms-1', '.doctor_bio_prod_price', '.step-price',
        '.product-card .card-text.fw-bold', '.nivis-product-price'
    ].join(',');

    function decorate(element) {
        if (element.querySelector('.nivis-price-pair')) return;
        const copy = element.cloneNode(true);
        copy.querySelectorAll('.badge-b1g1').forEach(badge => badge.remove());
        const amount = pricing.amount(copy.textContent);
        if (!amount) return;
        const badges = Array.from(element.querySelectorAll('.badge-b1g1'));
        const template = document.createElement('template');
        template.innerHTML = pricing.html(amount);
        element.replaceChildren(...badges, template.content);
        const label = element.matches('.price-main') && element.closest('.price-row')?.querySelector('.price-mrp-label');
        if (label && label.textContent !== 'Price:') label.textContent = 'Price:';
    }

    function decorateWithin(container) {
        if (container.matches?.(selector)) decorate(container);
        container.querySelectorAll?.(selector).forEach(decorate);
    }

    function start() {
        decorateWithin(document.body);
        const observer = new MutationObserver(records => {
            const containers = new Set();
            for (const record of records) {
                const element = record.target.nodeType === 1 ? record.target : record.target.parentElement;
                if (element) containers.add(element.closest(selector) || element);
            }
            containers.forEach(decorateWithin);
        });
        observer.observe(document.body, { childList: true, characterData: true, subtree: true });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, { once: true });
    else start();
}(typeof window === 'undefined' ? null : window, function () {
    function amount(value) {
        const clean = typeof value === 'string'
            ? value.trim().replace(/^(?:Rs\.?|INR)\s*/i, '').replace(/[,\s\u20b9]/g, '')
            : value;
        const number = Number(clean);
        return Number.isFinite(number) && number > 0 ? number : 0;
    }
    function reference(value) {
        return Math.round((amount(value) * 1.1 + Number.EPSILON) * 100) / 100;
    }
    function format(value) {
        const price = amount(value);
        return '\u20b9' + price.toLocaleString('en-IN', {
            minimumFractionDigits: Number.isInteger(price) ? 0 : 2,
            maximumFractionDigits: 2
        });
    }
    function html(value) {
        const price = amount(value);
        if (!price) return '';
        const oldLabel = format(reference(price));
        const saleLabel = format(price);
        return '<span class="nivis-price-pair">' +
            '<s class="nivis-price-reference" aria-label="Reference price ' + oldLabel + '">' + oldLabel + '</s>' +
            '<span class="nivis-price-current" data-sale-price="' + price + '" aria-label="Price ' + saleLabel + '">' + saleLabel + '</span></span>';
    }
    return { amount, reference, format, html };
}));
