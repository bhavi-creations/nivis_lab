(function (root, factory) {
    const matching = factory();
    if (typeof module === 'object' && module.exports) module.exports = matching;
    else root.NivisSkinMatching = matching;
}(typeof globalThis !== 'undefined' ? globalThis : this, function () {
    'use strict';

    const concernRules = {
        acne: { label: 'Acne & breakouts', pattern: /\bacne\b|breakouts?|pimples?|salicylic|excess sebum/ },
        pigmentation: { label: 'Pigmentation', pattern: /pigment|dark spots?|uneven.{0,12}(?:tone|skin)|brightening|alpha arbutin/ },
        aging: { label: 'Fine lines & aging', pattern: /anti[ -]?ag(?:e?ing)|fine lines?|wrinkles?|skin elasticity|retinol|peptide/ },
        'dark-circles': { label: 'Dark circles', pattern: /dark circles?|under[ -]?eye|eye (?:cream|serum)/ },
        pores: { label: 'Open pores', pattern: /pores?|salicylic/ },
        sun: { label: 'Sun damage', pattern: /sunscreen|\bspf\b|sun damage|\buva\b|\buvb\b/ },
        dryness: { label: 'Dryness & dehydration', pattern: /hydrat|dry skin|dryness|ceramide|hyaluronic|moisturiz|moisturis/ }
    };
    const skinLabels = { oily: 'Oily', dry: 'Dry', combination: 'Combination', sensitive: 'Sensitive', normal: 'Normal', unsure: 'Not sure' };

    function flatten(value) {
        if (Array.isArray(value)) return value.map(flatten).join(' ');
        if (value && typeof value === 'object') return Object.values(value).map(flatten).join(' ');
        return String(value == null ? '' : value);
    }

    function productText(product) {
        return [product.name, product.category, product.concern, product.displayConcern, product.ingredient,
            product.type, product.subtitle, product.description, flatten(product.attributes), flatten(product.details)]
            .filter(Boolean).join(' ').replace(/<[^>]*>/g, ' ').toLowerCase();
    }

    function isFacialProduct(product) {
        const identity = [product.name, product.urlKey, product.url_key, product.category, product.type].filter(Boolean).join(' ').toLowerCase();
        const excluded = /\bhair\b|\bscalp\b|\bfoot\b|\bfeet\b|\bbaby\b|\bshampoo\b|\btablets?\b|\bcapsules?\b|\bsoftgels?\b|\bpowder\b|\bsupplements?\b|nutraceutical|body (?:wash|lotion|cream)/;
        return !excluded.test(identity) && !/nutraceutical|\bsupplements?\b|oral (?:use|supplement)|taken (?:by mouth|orally)/.test(productText(product));
    }

    function explicitSkinTypes(product, text) {
        const attributes = Array.isArray(product.attributes) ? product.attributes : [];
        const details = Array.isArray(product.details) ? product.details : [];
        const declared = [product.skinType, product.skin_type, product.skinTypes,
            ...attributes.filter(item => /skin[ _-]?types?/i.test(item.name || item.attributeName || item.attributeCode || '')).map(item => item.value || item.optionText),
            ...details.filter(item => /skin[ _-]?types?/i.test(item.label || '')).map(item => item.value)]
            .filter(Boolean).map(flatten).join(' ').toLowerCase();
        const positiveText = text.replace(/not (?:suitable|recommended) for [^.]+/g, '');
        const suitability = declared || (positiveText.match(/(?:suitable|ideal|perfect|formulated|designed) for (?:people with )?((?:(?:oily|dry|combination|sensitive|normal|all|acne[ -]prone)\b[ ,&-]*(?:and\s+)?){1,6})skin(?:\s+types?)?/g) || []).join(' ');
        if (/\ball\b/.test(suitability)) return Object.keys(skinLabels).filter(type => type !== 'unsure');
        return ['oily', 'dry', 'combination', 'sensitive', 'normal'].filter(type => new RegExp('\\b' + type + '\\b').test(suitability));
    }

    function skinPreferenceScore(type, text) {
        const preferences = {
            oily: [/oil[ -]?free|non[ -]?greasy|matte/, /lightweight|foaming/, /sebum/],
            dry: [/nourish|rich cream/, /hydrat/, /ceramide|shea butter/],
            combination: [/lightweight|non[ -]?greasy/, /balance/, /hydrat/],
            sensitive: [/sooth|calming/, /gentle|fragrance[ -]?free/, /barrier/],
            normal: [/daily|all skin types/, /hydrat/]
        };
        return (preferences[type] || []).reduce((score, pattern) => score + (pattern.test(text) ? 2 : 0), 0);
    }

    function recommend(products, skinType, selectedConcerns) {
        const concerns = [...new Set(selectedConcerns)].filter(key => concernRules[key]);
        const seen = new Set();
        const matches = [];
        if (!skinLabels[skinType] || !concerns.length) return { matches, unmatched: concerns };

        products.forEach(product => {
            if (!product || !isFacialProduct(product)) return;
            const key = String(product.urlKey || product.url_key || product.sku || product.id || product.name || '').trim().toLowerCase();
            if (!key || seen.has(key)) return;
            const text = productText(product);
            const types = explicitSkinTypes(product, text);
            if (skinType !== 'unsure' && types.length && !types.includes(skinType)) return;
            if (skinType !== 'unsure' && new RegExp('not (?:suitable|recommended) for (?:[a-z]+ and )?' + skinType + '\\b').test(text)) return;
            const acneText = text.replace(/acne[ -]prone|acne (?:scars?|marks?|sequelae)/g, '');
            const matchedConcerns = concerns.filter(key => concernRules[key].pattern.test(key === 'acne' ? acneText : text));
            if (!matchedConcerns.length) return;
            seen.add(key);
            matches.push({ product, matchedConcerns, explicitSkinMatch: types.includes(skinType),
                score: matchedConcerns.length * 10 + (types.includes(skinType) ? (types.length === 5 ? 1 : 5) : 0) + skinPreferenceScore(skinType, text) });
        });

        matches.sort((a, b) => b.score - a.score || String(a.product.name).localeCompare(String(b.product.name)));
        return { matches, unmatched: concerns.filter(key => !matches.some(match => match.matchedConcerns.includes(key))) };
    }

    return { recommend, concernRules, skinLabels };
}));
