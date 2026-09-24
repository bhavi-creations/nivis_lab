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
