<?php include 'navbar.php'; ?>

<main class="skinthesis-page">
    <section class="index_straight-up_section skinthesis-hero">
        <div class="container">
            <h5 class="text-uppercase ls-2 text-white">/Hair Care/</h5>
            <h2 class="fw-bold mb-4">Care made for your hair goals.</h2>
            <div class="d-flex flex-wrap justify-content-center mt-4 index-guide-categories skinthesis-hero__topics">
                <a href="category.php?category=grey-hair" class="index_img_section__badge mx-1">Grey Hair</a>
                <a href="category.php?category=thin-hair" class="index_img_section__badge mx-1">Thin Hair</a>
                <a href="category.php?category=hair-fall" class="index_img_section__badge mx-1">Hair Fall</a>
            </div>
        </div>
    </section>

    <section id="grey-hair" data-backend-category="grey-hair" class="skinthesis_section skinthesis-article-section skinthesis-article-section--answers">
        <div class="container">
            <h2 class="text-center section-title mb-5">Grey Hair</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4"><article class="skinthesis-card h-100"><div class="card-img-wrapper"><img src="./assets/img/products.png" class="card-img-top" alt="Grey hair care"></div><div class="card-body p-4"><h4 class="card-title">Grey Hair Care</h4><p class="card-text text-muted small">Products selected for grey hair care and a healthy-looking scalp.</p><a href="category.php?category=grey-hair" class="read-more-link">View Products &rarr;</a></div></article></div>
            </div>
        </div>
    </section>

    <section id="thin-hair" data-backend-category="thin-hair" class="skinthesis_section skinthesis-article-section skinthesis-article-section--concerns">
        <div class="container">
            <h2 class="text-center section-title mb-5 mt-5 text-white">Thin Hair</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4"><article class="skinthesis-card h-100"><div class="card-img-wrapper"><img src="./assets/img/products.png" class="card-img-top" alt="Thin hair care"></div><div class="card-body p-4"><h4 class="card-title">Thin Hair Care</h4><p class="card-text text-muted small">Explore products for lightweight nourishment and fuller-looking hair.</p><a href="category.php?category=thin-hair" class="read-more-link">View Products &rarr;</a></div></article></div>
            </div>
        </div>
    </section>

    <section id="hair-fall" data-backend-category="hair-fall" class="skinthesis_section skinthesis-article-section skinthesis-article-section--ingredients">
        <div class="container">
            <h2 class="text-center section-title mb-5 mt-5">Hair Fall</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4"><article class="skinthesis-card h-100"><div class="card-img-wrapper"><img src="./assets/img/products.png" class="card-img-top" alt="Hair fall care"></div><div class="card-body p-4"><h4 class="card-title">Hair Fall Care</h4><p class="card-text text-muted small">Find products added for hair fall care and scalp support.</p><a href="category.php?category=hair-fall" class="read-more-link">View Products &rarr;</a></div></article></div>
            </div>
        </div>
    </section>
</main>

<script>
(function () {
    const sections = document.querySelectorAll('[data-backend-category]');

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[character]));
    }

    function productCard(product) {
        const image = product.imageUrl || './assets/img/product.webp';
        const link = product.link && product.link !== '#' ? product.link : '#';
        return `<div class="col-md-4"><article class="skinthesis-card h-100"><a class="skincare-product-image-link" href="${escapeHtml(link)}"><div class="card-img-wrapper"><img src="${escapeHtml(image)}" class="card-img-top skincare-product-image" alt="${escapeHtml(product.name || 'Hair care product')}" loading="lazy" onerror="this.onerror=null;this.src='./assets/img/product.webp';"></div></a><div class="card-body p-4"><h4 class="card-title">${escapeHtml(product.name || 'Hair care product')}</h4><p class="card-text text-muted small">${escapeHtml(product.subtitle || product.description || 'Hair care product for your routine.')}</p><a href="${escapeHtml(link)}" class="read-more-link">View Product &rarr;</a></div></article></div>`;
    }

    sections.forEach(async section => {
        try {
            const response = await fetch(`fetch_category_products.php?category=${encodeURIComponent(section.dataset.backendCategory)}`);
            const result = await response.json();
            if (Array.isArray(result.products) && result.products.length) {
                section.querySelector('.row').innerHTML = result.products.slice(0, 3).map(productCard).join('');
            }
        } catch (error) {
            // Keep the default card if the backend is unavailable.
        }
    });
}());
</script>

<?php include 'footer.php'; ?>
