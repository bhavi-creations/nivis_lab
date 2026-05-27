<?php include 'navbar.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div id="homeCategorySections">
        <div class="text-center py-5" id="mainSpinner">
            <div class="spinner-border text-dark"></div>
            <p class="mt-2">Loading Categories and Products...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCategorySections();
    });

    async function loadCategorySections() {
        const mainContainer = document.getElementById('homeCategorySections');

        try {
            const response = await fetch('fetch_home_sections.php');
            const result = await response.json();

            if (result.errors) {
                console.error(result.errors);
                mainContainer.innerHTML = `<div class="alert alert-danger">GraphQL Error. Check Console.</div>`;
                return;
            }

            const categories = result.data?.categories?.items || [];

            if (categories.length === 0) {
                mainContainer.innerHTML = `<p class="text-center">No categories found.</p>`;
                return;
            }

            // స్పిన్నర్ తీసేయడానికి కంటైనర్ ఖాళీ చేస్తున్నాం
            mainContainer.innerHTML = '';

            // 1. ప్రతి కేటగిరీ పైన లూప్ తిరుగుతుంది
            categories.forEach(category => {
                const products = category.products?.items || [];

                // ప్రొడక్ట్స్ లేని కేటగిరీ సెక్షన్‌ను చూపించకుండా స్కిప్ చేయాలనుకుంటే ఈ కండిషన్ వాడవచ్చు
                if (products.length === 0) return;

                // కేటగిరీ పేరుతో ఒక సెక్షన్ హెడ్డింగ్ మరియు ప్రొడక్ట్స్ గ్రిడ్ క్రియేట్ చేస్తున్నాం
                let sectionHtml = `
                <div class="category-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h2 class="section-title text-capitalize" style="font-weight: 600; color: #222;">
                            ${category.name}
                        </h2>
                        <a href="category.php?type=${category.url_key}" class="btn btn-sm btn-outline-dark">View All</a>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            `;

                // 2. ఆ కేటగిరీ లోపల ఉన్న ప్రొడక్ట్స్ పై లూప్ తిరుగుతుంది
                products.forEach(product => {
                    const imageUrl = product.image?.url ? `http://localhost:3000${product.image.url}` : './assets/img/logo.jpeg';
                    const productName = product.name || 'Product Name';
                    const productPrice = product.price?.regular?.text || '₹0';
                    const concern = product.concern || 'Skincare';
                    const slug = product.url_key || '#';

                    sectionHtml += `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 product-card" style="transition: transform 0.2s;">
                            <a href="${slug}.php" class="text-decoration-none text-dark">
                                <div class="position-relative overflow-hidden" style="height: 250px; background: #f8f9fa;">
                                    <img src="${imageUrl}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="${productName}">
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <p class="text-muted small mb-1 text-uppercase">/ ${concern} /</p>
                                        <h5 class="card-title fs-6 mb-2 text-truncate-2" style="font-weight: 500; height: 40px; overflow: hidden;">
                                            ${productName}
                                        </h5>
                                    </div>
                                    <div>
                                        <div class="mb-2">
                                            <span class="text-warning small">★★★★½</span>
                                            <span class="text-muted small">(120)</span>
                                        </div>
                                        <p class="card-text fw-bold text-dark mb-0">${productPrice}</p>
                                    </div>
                                </div>
                            </a>
                            <div class="p-3 pt-0">
                                <button class="btn btn-dark w-100 btn-sm rounded-0">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `;
                });

                sectionHtml += `
                    </div>
                </div>
            `;

                // తయారు చేసిన సెక్షన్‌ను మెయిన్ పేజీ కంటైనర్‌లో యాడ్ చేస్తున్నాం
                mainContainer.innerHTML += sectionHtml;
            });

        } catch (error) {
            console.error('Error:', error);
            mainContainer.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    }
</script>

<?php include 'footer.php'; ?>