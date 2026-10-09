const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const pricing = require('../assets/js/product-pricing.js');

test('comparison price adds exactly 10 percent to the actual product price', () => {
    assert.equal(pricing.reference(1000), 1100);
    assert.equal(pricing.reference(500), 550);
    assert.equal(pricing.reference(649), 713.9);
    assert.equal(pricing.reference(1000.5), 1100.55);
});

test('rupee formats and decimals retain the actual sale amount', () => {
    assert.equal(pricing.amount('Rs. 1,000'), 1000);
    assert.equal(pricing.amount('INR 1,000.50'), 1000.5);
    assert.equal(pricing.amount('\u20b91,000'), 1000);
    assert.equal(pricing.format(1100), '\u20b91,100');
    assert.equal(pricing.format(713.9), '\u20b9713.90');
});

test('markup strikes the comparison price and keeps a separate sale value for cart parsing', () => {
    const html = pricing.html(1000);
    assert.match(html, /<s[^>]+>\u20b91,100<\/s>/);
    assert.match(html, /data-sale-price="1000"/);
    assert.match(html, />\u20b91,000<\/span>/);
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
    assert.equal(window.NivisPricing.reference(1000), 1100);
});
