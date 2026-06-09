(function() {
    window.toggleFilter = toggleFilter;
    window.updatePrice = updatePrice;

    injectDynamicProductStyles();
    start();

    function start() {
        const grid = document.getElementById('productsGrid');
        const count = document.getElementById('productCount');
        const footer = document.querySelector('.footer_section');
        const categoryKey = getCategoryKey();

        bindFilterHeaders();

        if (!categoryKey) return;

        if (grid) {
            patchCaseInsensitiveFilters();
            showLoadingState(grid, count);
            document.body.classList.add('dynamic-products-loading');
            loadProductsForExistingGrid(categoryKey, grid, count);
            return;
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', start, { once: true });
            return;
        }

        loadProductsForContentPage(pageSlug, footer);
    }

    function getCategoryKey() {
        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('category');

        if (category) {
            return String(category).trim().toLowerCase().replace(/_/g, '-');
        }

        const file = window.location.pathname.split('/').pop() || '';
        return file.replace(/\.php$/i, '').replace(/_/g, '-').toLowerCase();
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function(char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function apiUrl(path) {
        return new URL(path, window.location.href).href;
    }

    async function fetchCategoryProducts(category) {
        const cacheKey = `category-products:v4:${category}`;
        const cacheTtl = 10000;
        const cached = sessionStorage.getItem(cacheKey);

        if (cached) {
            try {
                const cachedPayload = JSON.parse(cached);
                const isFresh = Date.now() - cachedPayload.savedAt < cacheTtl;

                if (isFresh && cachedPayload.result?.products?.length > 0) {
                    return cachedPayload.result;
                }

                sessionStorage.removeItem(cacheKey);
            } catch (error) {
                sessionStorage.removeItem(cacheKey);
            }
        }

        const response = await fetch(apiUrl(`fetch_category_products.php?category=${encodeURIComponent(category)}`));
        const result = await response.json();

        if (result.error) {
            throw new Error(result.error);
        }

        if (result.products?.length > 0) {
            sessionStorage.setItem(cacheKey, JSON.stringify({
                savedAt: Date.now(),
                result
            }));
        }

        return result;
    }

    async function loadProductsForExistingGrid(category, targetGrid, targetCount) {
        try {
            const result = await fetchCategoryProducts(category);
            renderIntoGrid(result.products || [], targetGrid, targetCount, result.category?.name || category);
        } catch (error) {
            document.body.classList.remove('dynamic-products-loading');
            document.body.classList.add('dynamic-products-ready');
            targetGrid.innerHTML = `<div class="alert alert-danger w-100">${escapeHtml(error.message || 'Products loading failed.')}</div>`;
            if (targetCount) targetCount.textContent = '0 products';
        }
    }

    function showLoadingState(targetGrid, targetCount) {
        targetGrid.innerHTML = `
            <div class="text-center w-100 py-5">
                <div class="spinner-border text-dark" role="status"></div>
                <p class="mt-2 mb-0">Loading products...</p>
            </div>
        `;

        if (targetCount) {
            targetCount.textContent = 'Loading products...';
        }
    }

    function slugify(value) {
        return String(value ?? '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function productTokens(value) {
        const text = String(value ?? '').toLowerCase();
        const slug = slugify(text);
        const words = text
            .replace(/[^a-z0-9]+/g, ' ')
            .trim()
            .split(/\s+/)
            .filter(Boolean);

        return Array.from(new Set([slug, ...words])).join(' ');
    }

    function checkedFilterValues(selector) {
        return [...document.querySelectorAll(`${selector} input:checked`)]
            .flatMap(input => productTokens(input.value).split(/\s+/))
            .filter(Boolean);
    }

    function datasetTokens(card, key) {
        return String(card.dataset[key] || '')
            .toLowerCase()
            .split(/\s+/)
            .filter(Boolean);
    }

    function patchCaseInsensitiveFilters() {
        if (!document.getElementById('priceRange') || window.__dynamicProductFiltersPatched) return;

        window.__dynamicProductFiltersPatched = true;
        window.applyFilters = function() {
            const priceRange = document.getElementById('priceRange');
            const productCount = document.getElementById('productCount');
            const maxPrice = parseInt(priceRange?.value || '999999', 10);
            const checkedConcerns = checkedFilterValues('#filter-concern');
            const checkedIngredients = checkedFilterValues('#filter-ingredient');
            const checkedTypes = checkedFilterValues('#filter-type');
            const cards = document.querySelectorAll('.product-card');
            let visible = 0;

            cards.forEach(card => {
                const price = parseInt(card.dataset.price || '0', 10);
                const concerns = datasetTokens(card, 'concern');
                const ingredients = datasetTokens(card, 'ingredient');
                const type = datasetTokens(card, 'type');
                const priceOk = price <= maxPrice;
                const concernOk = checkedConcerns.length === 0 || checkedConcerns.some(value => concerns.includes(value));
                const ingredientOk = checkedIngredients.length === 0 || checkedIngredients.some(value => ingredients.includes(value));
                const typeOk = checkedTypes.length === 0 || checkedTypes.some(value => type.includes(value));
                const show = priceOk && concernOk && ingredientOk && typeOk;

                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            if (productCount) {
                productCount.textContent = `${visible} product${visible !== 1 ? 's' : ''}`;
            }
        };
    }

    function toggleFilter(header) {
        const body = header.nextElementSibling;
        if (!body) return;
        const icon = header.querySelector('.toggle-icon');
        const isOpen = body.classList.toggle('open');
        if (icon) icon.textContent = isOpen ? '−' : '+';
    }

    function updatePrice(val) {
        const priceMax = document.getElementById('priceMax');
        if (priceMax) priceMax.value = val;
        if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        }
    }

    window.toggleFilter = toggleFilter;
    window.updatePrice = updatePrice;

    function bindFilterHeaders() {
        document.querySelectorAll('.filter-group-header').forEach(header => {
            if (header.dataset.filterBound === 'true') return;

            header.removeAttribute('onclick');
            header.dataset.filterBound = 'true';
            header.addEventListener('click', () => {
                const body = header.nextElementSibling;
                if (!body) return;

                const icon = header.querySelector('.toggle-icon');
                const group = header.closest('.filter-group');
                const isOpen = !body.classList.contains('open');

                body.classList.toggle('open', isOpen);
                if (group) group.classList.toggle('is-open', isOpen);
                if (icon) icon.textContent = isOpen ? '-' : '+';
            });
        });
    }

    function injectDynamicProductStyles() {
        if (document.getElementById('dynamicProductCardStyles')) return;

        const style = document.createElement('style');
        style.id = 'dynamicProductCardStyles';
        style.textContent = `
            .product-card { isolation: isolate; }
            .product-card a { color: inherit; text-decoration: none; }
            .product-card .product-info { min-height: 145px; }
            .product-img-wrap img {
                background: #f6f6f6;
            }
            .product-hover-popover {
                position: absolute;
                left: 10px;
                right: 10px;
                bottom: 54px;
                z-index: 4;
                padding: 12px;
                border: 1px solid rgba(10, 43, 74, .12);
                border-radius: 6px;
                background: rgba(255, 255, 255, .98);
                box-shadow: 0 14px 35px rgba(10, 43, 74, .16);
                opacity: 0;
                pointer-events: none;
                transform: translateY(12px) scale(.98);
                transition: opacity .22s ease, transform .22s ease;
            }
            .product-card:hover { z-index: 5; }
            .product-card:hover .product-hover-popover,
            .product-card:focus-within .product-hover-popover {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            .product-hover-popover__title {
                margin-bottom: 4px;
                color: #0a2b4a;
                font-size: 12px;
                font-weight: 700;
                line-height: 1.25;
            }
            .product-hover-popover__text {
                margin: 0 0 8px;
                color: #555;
                font-size: 11px;
                line-height: 1.35;
            }
            .product-hover-popover__meta {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }
            .product-hover-popover__meta span {
                padding: 3px 7px;
                border-radius: 999px;
                background: #f2f5f7;
                color: #0a2b4a;
                font-size: 10px;
                font-weight: 600;
            }
            @media (hover: none) {
                .product-hover-popover { display: none; }
                .product-card .product-info { min-height: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    async function loadProductsForContentPage(category, targetFooter) {
        if (!targetFooter) return;

        try {
            const result = await fetchCategoryProducts(category);
            const products = result.products || [];

            if (products.length === 0) return;

            const section = document.createElement('section');
            section.className = 'py-5';
            section.innerHTML = `
                <div class="container">
                    <div class="product-grid-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h2 class="fw-bold mb-0">${escapeHtml(result.category?.name || category)}</h2>
                            <div class="product-count">${products.length} product${products.length !== 1 ? 's' : ''}</div>
                        </div>
                        <div class="products-grid">${products.map(productCard).join('')}</div>
                    </div>
                </div>
            `;
            targetFooter.parentNode.insertBefore(section, targetFooter);
        } catch (error) {
            console.error(error);
        }
    }

    function renderIntoGrid(products, targetGrid, targetCount, categoryName) {
        document.body.classList.remove('dynamic-products-loading');
        document.body.classList.add('dynamic-products-ready');

        const titleEl = document.getElementById('categoryPageTitle');
        const sidebarTitleEl = document.getElementById('sidebarTitle');
        
        if (titleEl && categoryName) {
            titleEl.textContent = categoryName;
        }
        if (sidebarTitleEl && categoryName) {
            sidebarTitleEl.textContent = categoryName;
        }

        if (!products.length) {
            targetGrid.innerHTML = `<p class="text-center w-100">No ${escapeHtml(categoryName)} products found.</p>`;
            if (targetCount) targetCount.textContent = '0 products';
            return;
        }

        populateDynamicFilters(products);
        targetGrid.innerHTML = products.map(productCard).join('');
        if (targetCount) {
            targetCount.textContent = `${products.length} product${products.length !== 1 ? 's' : ''}`;
        }

        if (typeof window.applyFilters === 'function' && document.getElementById('priceRange')) {
            try {
                window.applyFilters();
            } catch (error) {
                console.warn('Product filters skipped:', error.message);
            }
        }
    }

    function labelFromProduct(product, field) {
        if (field === 'concern') {
            return product.displayConcern || product.concern || product.category || '';
        }

        return product[field] || '';
    }

    function addFilterOption(options, label) {
        const cleanLabel = String(label || '').trim();
        const key = slugify(cleanLabel);

        if (!key) return;

        if (!options.has(key)) {
            options.set(key, {
                label: cleanLabel,
                count: 0
            });
        }

        options.get(key).count++;
    }

    function renderFilterGroup(groupId, products, field) {
        const group = document.getElementById(groupId);
        if (!group || group.dataset.dynamicReady === 'true') return;

        const options = new Map();
        products.forEach(product => addFilterOption(options, labelFromProduct(product, field)));

        if (options.size === 0) return;

        group.innerHTML = [...options.entries()]
            .sort((a, b) => a[1].label.localeCompare(b[1].label))
            .map(([value, option]) => `
                <label>
                    <input type="checkbox" value="${escapeHtml(value)}" onchange="applyFilters()">
                    ${escapeHtml(option.label)}
                    <span class="count">(${option.count})</span>
                </label>
            `)
            .join('');

        group.dataset.dynamicReady = 'true';
    }

    function populateDynamicFilters(products) {
        renderFilterGroup('filter-concern', products, 'concern');
        renderFilterGroup('filter-ingredient', products, 'ingredient');
        renderFilterGroup('filter-type', products, 'type');
    }

    function productCard(product) {
        const concernTokens = productTokens(product.concern);
        const ingredientTokens = productTokens(product.ingredient);
        const typeTokens = productTokens(product.type);
        const popupText = product.subtitle || product.concern || product.category || 'Skincare';
        const fallbackImage = './assets/img/product.webp';
        const primaryImage = (product.images && product.images.length > 0) ? product.images[0] : (product.imageUrl || fallbackImage);
        const secondaryImage = (product.images && product.images.length > 1) ? product.images[1] : (product.secondaryImageUrl || primaryImage);
        const productKey = product.id || product.sku || product.urlKey || product.name || '';
        const detailHref = `product-detail.php?product=${encodeURIComponent(productKey)}`;

        return `
            <div class="product-card"
                data-price="${escapeHtml(product.priceNumber || 0)}"
                data-concern="${escapeHtml(concernTokens)}"
                data-ingredient="${escapeHtml(ingredientTokens)}"
                data-type="${escapeHtml(typeTokens)}">

                <a href="${escapeHtml(detailHref)}">
                    <div class="product-img-wrap">
                        <img class="img-primary" src="${escapeHtml(primaryImage)}" alt="${escapeHtml(product.name)}" loading="lazy" onerror="this.onerror=null;this.src='${fallbackImage}';" />
                        <img class="img-secondary" src="${escapeHtml(secondaryImage)}" alt="${escapeHtml(product.name)}" loading="lazy" onerror="this.onerror=null;this.src='${fallbackImage}';" />
                    </div>

                    <div class="product-info">
                        <div class="product-type-badge">${escapeHtml(product.type || 'Product')}</div>
                        <div class="product-name">${escapeHtml(product.name)}</div>
                        <div class="product-sub">/ ${escapeHtml(product.subtitle || product.concern || 'Skincare')} /</div>
                        <div>
                            <span class="stars">${escapeHtml(product.stars || '★★★★½')}</span>
                            <span class="review-count">(${escapeHtml(product.reviewsCount || 120)} reviews)</span>
                        </div>
                        <div class="product-price">${escapeHtml(product.price || '₹0')}</div>
                        <span class="bought-tag">${escapeHtml(product.boughtTag || '')}</span>
                    </div>

                    <div class="product-hover-popover">
                        <div class="product-hover-popover__title">${escapeHtml(product.name)}</div>
                        <p class="product-hover-popover__text">${escapeHtml(popupText)}</p>
                        <div class="product-hover-popover__meta">
                            <span>${escapeHtml(product.type || 'Product')}</span>
                            <span>${escapeHtml(product.price || '')}</span>
                        </div>
                    </div>
                </a>

                <button class="btn-cart">Add to Cart</button>
            </div>
        `;
    }
})();
