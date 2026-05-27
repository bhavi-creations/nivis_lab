<?php include 'navbar.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div id="homeCategorySections">
        <div class="text-center py-5" id="mainSpinner">
            <div class="spinner-border text-dark"></div>
            <p class="mt-2">Loading categories and products...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCategorySections();
    });

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

    function productHref(product) {
        if (product.link) return product.link;
        if (product.urlKey) return `${product.urlKey}.php`;
        if (product.url_key) return `${product.url_key}.php`;
        return '#';
    }

    async function loadCategorySections() {
        const mainContainer = document.getElementById('homeCategorySections');

        try {
            const response = await fetch('fetch_home_sections.php');
            const result = await response.json();

            if (result.error || result.errors) {
                console.error(result.error || result.errors);
                mainContainer.innerHTML = `<div class="alert alert-danger">Categories/products loading failed. Console check cheyyi.</div>`;
                return;
            }

            const categories = result.data?.categories?.items || [];

            if (categories.length === 0) {
                mainContainer.innerHTML = `<p class="text-center">No categories found.</p>`;
                return;
            }

            const sectionsHtml = categories
                .map(category => {
                    const products = category.products?.items || [];
                    const categorySlug = category.urlKey || category.url_key || '';

                    if (products.length === 0) return '';

                    return `
                        <section class="category-section mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                <h2 class="section-title text-capitalize mb-0" style="font-weight: 600; color: #222;">
                                    ${escapeHtml(category.name)}
                                </h2>
                                <a href="${categorySlug ? `${escapeHtml(categorySlug)}.php` : '#'}" class="btn btn-sm btn-outline-dark">View All</a>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                                ${products.map(product => {
                                    const imageUrl = product.imageUrl || product.primaryImage || './assets/img/logo.jpeg';
                                    const productName = product.name || 'Product Name';
                                    const productPrice = product.price?.regular?.text || product.price || '₹0';
                                    const concern = product.concern || 'Skincare';
                                    const stars = product.stars || '★★★★★';
                                    const reviews = product.reviewsCount || 120;

                                    return `
                                        <div class="col">
                                            <div class="card h-100 shadow-sm border-0 product-card">
                                                <a href="${escapeHtml(productHref(product))}" class="text-decoration-none text-dark">
                                                    <div class="position-relative overflow-hidden" style="height: 250px; background: #f8f9fa;">
                                                        <img src="${escapeHtml(imageUrl)}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="${escapeHtml(productName)}">
                                                    </div>
                                                    <div class="card-body d-flex flex-column justify-content-between p-3">
                                                        <div>
                                                            <p class="text-muted small mb-1 text-uppercase">/ ${escapeHtml(concern)} /</p>
                                                            <h5 class="card-title fs-6 mb-2" style="font-weight: 500; min-height: 40px;">
                                                                ${escapeHtml(productName)}
                                                            </h5>
                                                        </div>
                                                        <div>
                                                            <div class="mb-2">
                                                                <span class="text-warning small">${escapeHtml(stars)}</span>
                                                                <span class="text-muted small">(${escapeHtml(reviews)})</span>
                                                            </div>
                                                            <p class="card-text fw-bold text-dark mb-0">${escapeHtml(productPrice)}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="p-3 pt-0">
                                                    <button class="btn btn-dark w-100 btn-sm rounded-0">Add to Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </section>
                    `;
                })
                .join('');

            mainContainer.innerHTML = sectionsHtml.trim() || `<p class="text-center">Categories found, but no related products found.</p>`;
        } catch (error) {
            console.error('Error:', error);
            mainContainer.innerHTML = `<div class="alert alert-danger">Error: ${escapeHtml(error.message)}</div>`;
        }
    }
</script>

<?php include 'footer.php'; ?>
