(function () {
    const sections = document.querySelectorAll('[data-backend-category]');
    if (!sections.length) return;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[character]));
    }

    function productCard(product) {
        const name = product.name || 'Product';
        const image = product.imageUrl || './assets/img/product.webp';
        const key = product.urlKey || product.url_key || product.sku || product.id;
        const link = key ? `product-detail.php?product=${encodeURIComponent(key)}` : '#';
        const description = product.subtitle || product.description || '';
        const price = product.price ? `<div class="product-price">${escapeHtml(product.price)}</div>` : '';

        return `
            <div class="col-md-4">
                <article class="skinthesis-card h-100">
                    <a class="skincare-product-image-link" href="${escapeHtml(link)}">
                        <div class="card-img-wrapper">
                            <img src="${escapeHtml(image)}" class="card-img-top skincare-product-image"
                                alt="${escapeHtml(name)}" loading="lazy"
                                onerror="this.onerror=null;this.src='./assets/img/product.webp';">
                        </div>
                    </a>
                    <div class="card-body p-4">
                        <h4 class="card-title">${escapeHtml(name)}</h4>
                        <p class="card-text text-muted small">${escapeHtml(description)}</p>
                        ${price}
                        <a href="${escapeHtml(link)}" class="read-more-link">View Product &rarr;</a>
                    </div>
                </article>
            </div>`;
    }

    async function loadSection(section) {
        const grid = section.querySelector('[data-category-products]');
        const category = section.dataset.backendCategory;
        if (!grid || !category) return;

        try {
            const response = await fetch(`fetch_category_products.php?category=${encodeURIComponent(category)}`, {
                headers: { Accept: 'application/json' }
            });
            if (!response.ok) throw new Error('Product request failed');

            const result = await response.json();
            if (result.error || !Array.isArray(result.products)) {
                throw new Error(result.error || 'Invalid product response');
            }

            grid.innerHTML = result.products.length
                ? result.products.map(productCard).join('')
                : '<p class="text-center">No products in this category yet.</p>';
        } catch (error) {
            grid.innerHTML = '<p class="text-center">Unable to load products right now. Please try again later.</p>';
        }
    }

    sections.forEach(loadSection);
}());
