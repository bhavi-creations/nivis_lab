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

    function spotlightProductCard(product) {
        const name = product.name || 'Product';
        const image = product.imageUrl || './assets/img/product.webp';
        const key = product.urlKey || product.url_key || product.sku || product.id || product.name || '';
        const link = `product-detail.php?product=${encodeURIComponent(key)}`;
        const subtitle = product.subtitle || product.displayConcern || product.concern || product.category || 'Skincare';
        const size = product.size ? `<span class="spotlight-product-size">${escapeHtml(product.size)}</span>` : '';
        const priceNumber = Number(product.priceNumber) || Number(String(product.price || '0').replace(/,/g, '').replace(/[^0-9.]/g, '')) || 0;
        const priceLabel = product.price || `₹${priceNumber.toLocaleString('en-IN')}`;

        return `
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="spotlight-card"
                    data-product-id="${escapeHtml(key)}"
                    data-product-name="${escapeHtml(name)}"
                    data-product-price="${escapeHtml(priceNumber)}"
                    data-product-image="${escapeHtml(image)}"
                    data-price="${escapeHtml(priceNumber)}">
                    <a href="${escapeHtml(link)}">
                        <img src="${escapeHtml(image)}" class="w-100 mb-3" alt="${escapeHtml(name)}" loading="lazy"
                            onerror="this.onerror=null;this.src='./assets/img/product.webp';">
                        <h6 class="fw-bold">${escapeHtml(name)} ${size}</h6>
                        <p class="small text-muted mb-2">/ ${escapeHtml(subtitle)} /</p>
                        <div class="spotlight-card__meta small mb-2"><span class="text-warning">★★★★☆</span> (${escapeHtml(product.reviewsCount || 120)} reviews)</div>
                        <div class="spotlight-card__price mb-3"><span class="badge-b1g1">${escapeHtml(product.boughtTag || 'B1G1')}</span> <span class="ms-1">${escapeHtml(priceLabel)}</span></div>
                    </a>
                    <div class="spotlight-card__popover">
                        <div class="spotlight-card__popover-title">${escapeHtml(name)}</div>
                        <p class="spotlight-card__popover-text">${escapeHtml(subtitle)}</p>
                        <div class="spotlight-card__popover-meta">${escapeHtml(product.type || 'Product')} ${product.size ? `• ${escapeHtml(product.size)}` : ''} • ${escapeHtml(priceLabel)}</div>
                    </div>
                    <button type="button" class="btn btn-dark spotlight-card__btn w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>`;
    }

    async function loadSection(section) {
        const grid = section.querySelector('[data-category-products]');
        const category = section.dataset.backendCategory;
        if (!grid || !category) return;

        try {
            const response = await fetch(`fetch_category_products.php?category=${encodeURIComponent(category)}`, {
                headers: { Accept: 'application/json' },
                cache: 'no-store'
            });
            if (!response.ok) throw new Error('Product request failed');

            const result = await response.json();
            if (result.error || !Array.isArray(result.products)) {
                throw new Error(result.error || 'Invalid product response');
            }

            const renderCard = section.closest('.skin-care-page') ? spotlightProductCard : productCard;
            grid.innerHTML = result.products.length
                ? result.products.map(renderCard).join('')
                : '<p class="text-center">No products in this category yet.</p>';
        } catch (error) {
            grid.innerHTML = '<p class="text-center">Unable to load products right now. Please try again later.</p>';
        }
    }

    const skinCarePage = document.querySelector('.skin-care-page');
    skinCarePage?.addEventListener('click', event => {
        const button = event.target.closest('.spotlight-card__btn');
        if (!button || !window.NivisCart) return;

        const card = button.closest('[data-product-id]');
        const product = card && window.NivisCart.fromCard(card);
        if (!product) return;

        if (typeof window.addProductToCart === 'function') {
            window.addProductToCart(product);
            return;
        }

        window.NivisCart.add(product, 1);
        const drawer = document.getElementById('cartDrawer');
        if (drawer && window.bootstrap) {
            bootstrap.Offcanvas.getOrCreateInstance(drawer).show();
        }
    });

    sections.forEach(loadSection);
}());
