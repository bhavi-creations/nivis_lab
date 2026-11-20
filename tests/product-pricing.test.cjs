const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const pricing = require('../assets/js/product-pricing.js');

test('catalogue price is the struck-through original and the sale price is 20 percent lower', () => {
    assert.equal(pricing.DISCOUNT_PERCENT, 20);
    assert.equal(pricing.reference(1000), 1000);
    assert.equal(pricing.sale(100), 80);
    assert.equal(pricing.sale(1000), 800);
    assert.equal(pricing.sale(649), 519.2);
    assert.equal(pricing.sale(1424.5), 1139.6);
    assert.equal(pricing.sale('Rs. 1,000'), 800);
    assert.equal(pricing.sale(0), 0);
});

test('rupee formats and decimals retain the actual sale amount', () => {
    assert.equal(pricing.amount('Rs. 1,000'), 1000);
    assert.equal(pricing.amount('INR 1,000.50'), 1000.5);
    assert.equal(pricing.amount('\u20b91,000'), 1000);
    assert.equal(pricing.format(1100), '\u20b91,100');
    assert.equal(pricing.format(713.9), '\u20b9713.90');
});

test('markup strikes the original price and shows the 20 percent discounted price', () => {
    const html = pricing.html(1000);
    assert.match(html, /<s[^>]+>\u20b91,000<\/s>/);
    assert.match(html, /data-list-price="1000"/);
    assert.match(html, /data-sale-price="800"/);
    assert.match(html, />\u20b9800<\/span>/);
    assert.match(html, />20% OFF<\/span>/);
    for (const price of [0, -100, null, undefined, Infinity, 'Unavailable', '<script>1000</script>']) {
        assert.equal(pricing.html(price), '');
    }
});

test('legacy price observation starts during parsing before the body exists', () => {
    const document = { body: null, documentElement: {} };
    let observed;
    const window = { document };
    vm.runInNewContext(fs.readFileSync(require.resolve('../assets/js/product-pricing.js'), 'utf8'), {
        document, window,
        MutationObserver: class {
            observe(target, options) { observed = { target, options }; }
        }
    });
    assert.equal(observed.target, document.documentElement);
    assert.equal(observed.options.subtree, true);
    assert.equal(observed.options.childList, true);
    assert.equal(window.NivisPricing.sale(1000), 800);
});
