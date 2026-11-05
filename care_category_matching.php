<?php

/**
 * These storefront topics use the category assigned in the backend. Sunscreen
 * also accepts an explicit sunscreen product name because some sunscreen
 * products currently have another category or no category in the feed.
 */
function careCategoryAliases()
{
    return [
        'sunscreen' => ['sunscreen', 'sunscreens'],
        'brightening' => ['brightening'],
        'acne' => ['acne'],
        'hyper-pigmentation' => ['hyper-pigmentation', 'hyperpigmentation', 'pigmentation'],
        'anti-ageing' => ['anti-ageing', 'anti-aging'],
        'dehydration' => ['dehydration'],
        'grey-hair' => ['grey-hair'],
        'thin-hair' => ['thin-hair'],
        'hair-fall' => ['hair-fall']
    ];
}

function canonicalCareCategory($slug)
{
    foreach (careCategoryAliases() as $canonical => $aliases) {
        if (in_array($slug, $aliases, true)) {
            return $canonical;
        }
    }

    return null;
}

function productsInCareCategory($products, $canonical)
{
    $aliases = careCategoryAliases()[$canonical] ?? [];

    return array_values(array_filter($products, function ($product) use ($aliases, $canonical) {
        if (in_array(productCategorySlug($product), $aliases, true)) {
            return true;
        }

        // Several sunscreen products have an empty or unrelated backend
        // category, so use the product's explicit sunscreen name for this one
        // collection only. Do not match descriptions or other care topics.
        return $canonical === 'sunscreen'
            && preg_match('/\bsun[\s-]*screens?\b/i', normalizeText($product['name'] ?? '')) === 1;
    }));
}

function canonicalSeeTheMagicCollection($slug)
{
    if ($slug === 'pollution') {
        return 'pollutions';
    }

    return in_array($slug, ['hot-humid', 'cold-dry', 'pollutions', 'changing-environments'], true)
        ? $slug
        : null;
}

function canonicalClimateCollection($slug)
{
    return in_array($slug, ['climate-tropical-humid', 'climate-hot-dry', 'climate-cold-dry'], true)
        ? $slug
        : null;
}

function productsInClimateCollection($products, $collection)
{
    $collectionProducts = [
        'climate-tropical-humid' => [
            'nluv01',
            'nivis-labs-hydrofresh-hydrating-light-moisturiser-lotion',
            'nivis-labs-hydromist-revitalizing-face-calming-mist',
            'nivis-bright-glutathione-vit-c-face-wash'
        ],
        'climate-hot-dry' => [
            'nivis-labs-hydrofresh-hydrating-light-moisturiser-lotion',
            'nivis-labs-hydromist-revitalizing-face-calming-mist',
            'nivis-labs-korean-moon-light-night-cream-dehydration',
            'nluv01',
            'nivis-labs-uv-aqua-sunscreen-spray-spf-50'
        ],
        'climate-cold-dry' => [
            'nivis-labs-korean-moon-light-night-cream-dehydration',
            'nivis-labs-hydrofresh-hydrating-light-moisturiser-lotion',
            'nivis-labs-hydromist-revitalizing-face-calming-mist',
            'nivis-bright-glutathione-vit-c-face-wash',
            'nivis-labs-uv-aqua-sunscreen-spray-spf-50'
        ]
    ];
    $productKeys = $collectionProducts[$collection] ?? [];
    $displayNames = [
        'nluv01' => 'UV BEST Sunscreen Gel',
        'nivis-labs-hydrofresh-hydrating-light-moisturiser-lotion' => 'HYDROFRESH Moisturising Lotion',
        'nivis-labs-hydromist-revitalizing-face-calming-mist' => 'HYDROMIST Face Mist',
        'nivis-bright-glutathione-vit-c-face-wash' => 'Bright Glutathione + Vit C Face Wash',
        'nivis-labs-uv-aqua-sunscreen-spray-spf-50' => 'UV AQUA Sunscreen Spray'
    ];
    $matchedProducts = array_values(array_filter($products, function ($product) use ($productKeys) {
        $identifiers = [
            slugifyValue(normalizeText($product['urlKey'] ?? $product['url_key'] ?? '')),
            slugifyValue(normalizeText($product['sku'] ?? '')),
            slugifyValue(normalizeText($product['name'] ?? ''))
        ];

        return count(array_intersect($productKeys, $identifiers)) > 0;
    }));

    foreach ($matchedProducts as &$product) {
        $urlKey = slugifyValue(normalizeText($product['urlKey'] ?? $product['url_key'] ?? ''));
        if (isset($displayNames[$urlKey])) {
            $product['name'] = $displayNames[$urlKey];
        }
    }
    unset($product);

    return $matchedProducts;
}

function productsInSeeTheMagicCollection($products, $categoryKeys)
{
    return array_values(array_filter($products, function ($product) use ($categoryKeys) {
        return in_array(productCategorySlug($product), $categoryKeys, true);
    }));
}
