<?php include 'navbar.php'; ?>

<main class="skinthesis-page care-products-page">
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
            <div class="row g-4 justify-content-center mb-5" data-category-products aria-live="polite">
                <p class="text-center">Loading products...</p>
            </div>
            <div class="text-center">
                <a href="category.php?category=baby-care" class="read-more-link">View all Baby Care products &rarr;</a>
            </div>
        </div>
    </section>
</main>

<script src="assets/js/care-category-sections.js?v=4" defer></script>

<?php include 'footer.php'; ?>
