<?php

/**
 * These storefront topics use the category assigned in the backend. Similar
 * words in a product name or description must not put it in another topic.
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

    return array_values(array_filter($products, function ($product) use ($aliases) {
        return in_array(productCategorySlug($product), $aliases, true);
    }));
}
