const test = require('node:test');
const assert = require('node:assert/strict');
const { recommend } = require('../assets/js/skin-product-matching.js');

const products = [
    { id: 'wash', name: 'Foaming Face Wash', description: 'Salicylic acid helps unclog pores. Formulated for oily skin.', type: 'Face Wash' },
    { id: 'cream', name: 'Repair Cream', description: 'A rich ceramide cream for hydration.', skinType: 'Dry', type: 'Cream' },
    { id: 'mist', name: 'Calming Face Mist', description: 'Hydrating, soothing and lightweight.', skinType: 'All skin types', type: 'Mist' },
    { id: 'serum', name: 'Dark Spot Face Serum', description: 'Alpha arbutin targets pigmentation and acne scars.', type: 'Serum' },
    { id: 'spf', name: 'Sunscreen SPF 50', description: 'Non-greasy sunscreen. Ideal for oily and acne-prone skin.', type: 'Sunscreen' },
    { id: 'hair', name: 'Hair Serum', description: 'Niacinamide and hyaluronic acid for hydration.', type: 'Serum' },
    { id: 'foot', name: 'Foot Cream', description: 'Salicylic acid and ceramides.', type: 'Cream' },
    { id: 'baby', name: 'Baby Oil', description: 'Moisturizes dry skin.', type: 'Oil' },
    { id: 'oral', name: 'Marine Collagen Powde', description: 'A nutraceutical supplement for hydration, pigmentation and fine lines.' }
];

test('oily acne returns the targeted face wash, excluding acne scars and sunscreen suitability mentions', () => {
    assert.deepEqual(recommend(products, 'oily', ['acne']).matches.map(match => match.product.id), ['wash']);
});

test('dry skin excludes products explicitly listed for oily skin', () => {
    assert.deepEqual(recommend(products, 'dry', ['acne']).matches, []);
});

test('skin type changes hydration results and ranks the specific dry formula above an all-skin formula', () => {
    const dry = recommend(products, 'dry', ['dryness']).matches.map(match => match.product.id);
    const oily = recommend(products, 'oily', ['dryness']).matches.map(match => match.product.id);
    assert.deepEqual(dry, ['cream', 'mist']);
    assert.deepEqual(oily, ['mist']);
});

test('multiple concerns return relevant unique products and report unavailable concerns', () => {
    const result = recommend([...products, products[0]], 'oily', ['acne', 'pigmentation', 'dark-circles']);
    assert.deepEqual(result.matches.map(match => match.product.id), ['wash', 'serum']);
    assert.deepEqual(result.unmatched, ['dark-circles']);
});

test('hair, foot, baby and ingestible products never appear in the face routine', () => {
    const ids = recommend(products, 'unsure', ['dryness', 'pigmentation', 'aging']).matches.map(match => match.product.id);
    for (const id of ['hair', 'foot', 'baby', 'oral']) assert.ok(!ids.includes(id));
});

test('unknown skin type and concerns do not produce arbitrary products', () => {
    assert.deepEqual(recommend(products, '', ['acne']).matches, []);
    assert.deepEqual(recommend(products, 'oily', []).matches, []);
    assert.deepEqual(recommend(products, 'oily', ['invalid']).matches, []);
});

test('skin type metadata in attributes is respected', () => {
    const product = { id: 'sensitive', name: 'Hydration Serum', attributes: [{ name: 'Skin Type', value: 'Sensitive' }] };
    assert.equal(recommend([product], 'sensitive', ['dryness']).matches.length, 1);
    assert.equal(recommend([product], 'oily', ['dryness']).matches.length, 0);
});

test('explicit unsuitable skin types are rejected without excluding other types', () => {
    const product = { id: 'hydration', name: 'Hydration Cream', description: 'Not suitable for oily skin.' };
    assert.equal(recommend([product], 'oily', ['dryness']).matches.length, 0);
    assert.equal(recommend([product], 'dry', ['dryness']).matches.length, 1);
});
