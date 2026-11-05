<?php include 'navbar.php'; ?>

<main class="skinthesis-page care-products-page">
    <section class="index_straight-up_section skinthesis-hero care-compact-hero">
        <div class="container">
            <h5 class="text-uppercase ls-2 text-white">/Baby Care/</h5>
            <h2 class="fw-bold mb-4">Gentle care for little ones.</h2>

             <div class="search-box mx-auto nivis-inline-search">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0"><i class="fa fa-search"></i></span>
                    <input type="search" class="form-control border-0" id="indexGuideSearchInput"
                        autocomplete="off" placeholder="Search products and categories">
                </div>
                <div class="nivis-search-results nivis-search-results--inline" id="indexGuideSearchResults"></div>
            </div>
            <nav class="d-flex flex-wrap justify-content-center mt-4 index-guide-categories skinthesis-hero__topics" aria-label="Baby care categories">
                <a href="#baby-products" class="index_img_section__badge mx-1">Baby Care Products</a>
            </nav>
        </div>
    </section>

    <section id="baby-products" data-backend-category="baby-care" class="skinthesis_section skinthesis-article-section skinthesis-article-section--answers">
        <div class="container">
            <h2 class="text-center section-title mb-5">Baby Care</h2>
            <div class="row g-4 justify-content-start mb-5" data-category-products aria-live="polite">
                <p class="text-center">Loading products...</p>
            </div>
            <div class="text-center">
                <a href="category.php?category=baby-care" class="read-more-link">View all Baby Care products &rarr;</a>
            </div>
        </div>
    </section>
</main>

<script src="assets/js/care-category-sections.js?v=9" defer></script>

<?php include 'footer.php'; ?>
