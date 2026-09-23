<?php include 'navbar.php'; ?>

<main class="skinthesis-page">
    <section class="index_straight-up_section skinthesis-hero">
        <div class="container">
            <h5 class="text-uppercase ls-2 text-white">/Baby Care/</h5>
            <h2 class="fw-bold mb-4">Gentle care for little ones.</h2>
            <div class="d-flex flex-wrap justify-content-center mt-4 index-guide-categories skinthesis-hero__topics">
                <a href="category.php?category=baby-care" class="index_img_section__badge mx-1">Baby Care Products</a>
            </div>
        </div>
    </section>

    <section id="baby-products" data-backend-category="baby-care" class="skinthesis_section skinthesis-article-section skinthesis-article-section--answers">
        <div class="container">
            <h2 class="text-center section-title mb-5">Baby Care</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4"><article class="skinthesis-card h-100"><div class="card-img-wrapper"><img src="./assets/img/products.png" class="card-img-top" alt="Baby care products"></div><div class="card-body p-4"><h4 class="card-title">Baby Care Products</h4><p class="card-text text-muted small">Gentle products selected for your baby care routine.</p><a href="category.php?category=baby-care" class="read-more-link">View Products &rarr;</a></div></article></div>
            </div>
        </div>
    </section>
</main>

<script>
(function () {
    const section = document.querySelector('[data-backend-category]');
    if (!section) return;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[character]));
    }

    fetch(`fetch_category_products.php?category=${encodeURIComponent(section.dataset.backendCategory)}`)
        .then(response => response.json())
        .then(result => {
            if (!Array.isArray(result.products) || !result.products.length) return;
            section.querySelector('.row').innerHTML = result.products.slice(0, 3).map(product => {
                const image = product.imageUrl || './assets/img/product.webp';
                const link = product.link && product.link !== '#' ? product.link : '#';
                return `<div class="col-md-4"><article class="skinthesis-card h-100"><a class="skincare-product-image-link" href="${escapeHtml(link)}"><div class="card-img-wrapper"><img src="${escapeHtml(image)}" class="card-img-top skincare-product-image" alt="${escapeHtml(product.name || 'Baby care product')}" loading="lazy" onerror="this.onerror=null;this.src='./assets/img/product.webp';"></div></a><div class="card-body p-4"><h4 class="card-title">${escapeHtml(product.name || 'Baby care product')}</h4><p class="card-text text-muted small">${escapeHtml(product.subtitle || product.description || 'Baby care product for your routine.')}</p><a href="${escapeHtml(link)}" class="read-more-link">View Product &rarr;</a></div></article></div>`;
            }).join('');
        })
        .catch(() => {});
}());
</script>

<?php include 'footer.php'; ?>
