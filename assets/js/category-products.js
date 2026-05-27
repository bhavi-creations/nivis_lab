(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }

    function start() {
        const grid = document.getElementById('productsGrid');
        const count = document.getElementById('productCount');
        const footer = document.querySelector('.footer_section');
        const pageSlug = getPageSlug();

        if (!pageSlug) return;

        if (grid) {
            loadProductsForExistingGrid(pageSlug, grid, count);
            return;
        }

        loadProductsForContentPage(pageSlug, footer);
    }

    function getPageSlug() {
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

    async function fetchCategoryProducts(category) {
        const response = await fetch(`fetch_category_products.php?category=${encodeURIComponent(category)}`);
        const result = await response.json();

        if (result.error) {
            throw new Error(result.error);
        }

        return result;
    }

    async function loadProductsForExistingGrid(category, targetGrid, targetCount) {
        try {
            targetGrid.innerHTML = `
                <div class="text-center w-100 py-4">
                    <div class="spinner-border text-dark"></div>
                    <p class="mt-2">Loading products...</p>
                </div>
            `;

            const result = await fetchCategoryProducts(category);
            renderIntoGrid(result.products || [], targetGrid, targetCount, result.category?.name || category);
        } catch (error) {
            targetGrid.innerHTML = `<div class="alert alert-danger w-100">${escapeHtml(error.message)}</div>`;
            if (targetCount) targetCount.textContent = '0 products';
        }
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
        if (!products.length) {
            targetGrid.innerHTML = `<p class="text-center w-100">No ${escapeHtml(categoryName)} products found.</p>`;
            if (targetCount) targetCount.textContent = '0 products';
            return;
        }

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

    function productCard(product) {
        return `
            <div class="product-card"
                data-price="${escapeHtml(product.priceNumber || 0)}"
                data-concern="${escapeHtml((product.concern || '').toLowerCase())}"
                data-ingredient="${escapeHtml((product.ingredient || '').toLowerCase())}"
                data-type="${escapeHtml((product.type || '').toLowerCase())}">

                <a href="${escapeHtml(product.link || '#')}">
                    <div class="product-img-wrap">
                        <img class="img-primary" src="${escapeHtml(product.imageUrl)}" alt="${escapeHtml(product.name)}" />
                        <img class="img-secondary" src="${escapeHtml(product.secondaryImageUrl || product.imageUrl)}" alt="${escapeHtml(product.name)}" />
                    </div>

                    <div class="product-info">
                        <div class="product-name">${escapeHtml(product.name)}</div>
                        <div class="product-sub">/ ${escapeHtml(product.subtitle || product.concern || 'Skincare')} /</div>
                        <div>
                            <span class="stars">${escapeHtml(product.stars || '★★★★½')}</span>
                            <span class="review-count">(${escapeHtml(product.reviewsCount || 120)} reviews)</span>
                        </div>
                        <div class="product-price">${escapeHtml(product.price || '₹0')}</div>
                        <span class="bought-tag">${escapeHtml(product.boughtTag || '')}</span>
                    </div>
                </a>

                <button class="btn-cart">Add to Cart</button>
            </div>
        `;
    }
})();
