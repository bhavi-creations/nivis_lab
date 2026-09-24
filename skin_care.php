<?php include 'navbar.php'; ?>

<main class="skinthesis-page">
    <section class="index_straight-up_section skinthesis-hero">
        <div class="container">
            <h5 class="text-uppercase ls-2 text-white">/Skin Care/</h5>
            <h2 class="fw-bold mb-4">Beautiful skin begins with the right care.</h2>
            <div class="search-box mx-auto nivis-inline-search">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0"><i class="fa fa-search"></i></span>
                    <input type="search" class="form-control border-0" id="indexGuideSearchInput"
                        autocomplete="off" placeholder="Search products and categories">
                </div>
                <div class="nivis-search-results nivis-search-results--inline" id="indexGuideSearchResults"></div>
            </div>
            <nav class="d-flex flex-wrap justify-content-center mt-4 index-guide-categories skinthesis-hero__topics" aria-label="Skin care categories">
                <a href="#sunscreen" class="index_img_section__badge mx-1">Sunscreen</a>
                <a href="#brightening" class="index_img_section__badge mx-1">Brightening</a>
                <a href="#acne" class="index_img_section__badge mx-1">Acne</a>
                <a href="#hyper-pigmentation" class="index_img_section__badge mx-1">Hyper pigmentation</a>
                <a href="#anti-ageing" class="index_img_section__badge mx-1">Anti-Aging</a>
                <a href="#dehydration" class="index_img_section__badge mx-1">Dehydration</a>
            </nav>
        </div>
    </section>

    <?php
    $skinCategories = [
        ['sunscreen', 'Sunscreen', 'answers'],
        ['brightening', 'Brightening', 'concerns'],
        ['acne', 'Acne', 'ingredients'],
        ['hyper-pigmentation', 'Hyper pigmentation', 'answers'],
        ['anti-ageing', 'Anti-Aging', 'concerns'],
        ['dehydration', 'Dehydration', 'ingredients']
    ];
    foreach ($skinCategories as [$slug, $title, $style]):
    ?>
    <section id="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>"
        data-backend-category="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>"
        class="skinthesis_section skinthesis-article-section skinthesis-article-section--<?= htmlspecialchars($style, ENT_QUOTES, 'UTF-8') ?>">
        <div class="container">
            <h2 class="text-center section-title mb-5<?= $style === 'concerns' ? ' text-white' : '' ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
            <div class="row g-4 justify-content-center mb-5" data-category-products aria-live="polite">
                <p class="text-center">Loading products...</p>
            </div>
            <div class="text-center">
                <a href="category.php?category=<?= rawurlencode($slug) ?>" class="read-more-link">View all <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> products &rarr;</a>
            </div>
        </div>
    </section>
    <?php endforeach; ?>
</main>

<script src="assets/js/care-category-sections.js?v=2" defer></script>
<?php include 'footer.php'; ?>
