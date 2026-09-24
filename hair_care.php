<?php include 'navbar.php'; ?>

<main class="skinthesis-page care-products-page">
    <section class="index_straight-up_section skinthesis-hero">
        <div class="container">
            <h5 class="text-uppercase ls-2 text-white">/Hair Care/</h5>
            <h2 class="fw-bold mb-4">Care made for your hair goals.</h2>
            <nav class="d-flex flex-wrap justify-content-center mt-4 index-guide-categories skinthesis-hero__topics" aria-label="Hair care categories">
                <a href="category.php?category=grey-hair" class="index_img_section__badge mx-1">Grey Hair</a>
                <a href="category.php?category=thin-hair" class="index_img_section__badge mx-1">Thin Hair</a>
                <a href="category.php?category=hair-fall" class="index_img_section__badge mx-1">Hair Fall</a>
            </nav>
        </div>
    </section>

    <?php
    $hairCategories = [
        ['grey-hair', 'Grey Hair', 'answers'],
        ['thin-hair', 'Thin Hair', 'concerns'],
        ['hair-fall', 'Hair Fall', 'ingredients']
    ];
    foreach ($hairCategories as [$slug, $title, $style]):
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

<script src="assets/js/care-category-sections.js?v=4" defer></script>
<?php include 'footer.php'; ?>
