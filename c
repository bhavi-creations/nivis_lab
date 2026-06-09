<?php include 'navbar.php'; ?>

<div class="container product-detail-page py-5">
    <div id="loadingMessage" class="text-center py-5">
        <div class="spinner-border text-dark" role="status"></div>
        <p class="mt-3">Loading product details...</p>
    </div>

    <div id="productDetail" style="display: none;">
        <!-- PRODUCT IMAGE GALLERY + DETAILS -->
        <div class="row mb-5">
            <!-- LEFT: IMAGE GALLERY -->
            <div class="col-lg-5">
                <div class="product-gallery">
                    <div class="main-image-container mb-3">
                        <img id="mainImage" src="" alt="Product" class="img-fluid rounded" style="width: 100%; aspect-ratio: 1; object-fit: cover;">
                    </div>
                    <div class="thumbnail-gallery d-flex gap-2" id="thumbnailGallery"></div>
                </div>
            </div>

            <!-- RIGHT: PRODUCT INFO -->
            <div class="col-lg-7">
                <div class="product-info-section">
                    <h1 id="productName" class="mb-3"></h1>
                    <p id="productSubtitle" class="text-muted mb-3" style="font-size: 1.1rem;"></p>

                    <!-- RATINGS -->
                    <div class="mb-3">
                        <span id="productStars" class="stars" style="font-size: 1.2rem;">★★★★★</span>
                        <span id="productReviews" class="ms-2">(<span id="reviewCount">0</span> reviews)</span>
                    </div>

                    <!-- PRICE -->
                    <div class="price-section mb-4">
                        <h2 id="productPrice" class="text-primary" style="font-size: 2rem; font-weight: bold;">₹0</h2>
                        <p id="boughtTag" class="text-success">196+ bought in past month</p>
                    </div>

                    <!-- ADD TO CART -->
                    <button class="btn btn-dark btn-lg w-100 mb-4" onclick="addToCart()">Add to Cart</button>

                    <!-- KEY DETAILS -->
                    <div class="key-details border-top pt-4">
                        <h5 class="mb-3">Key Details</h5>
                        <dl class="row" id="keyDetails"></dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- FULL DESCRIPTION SECTION -->
        <div class="row mt-5 border-top pt-5">
            <div class="col-lg-8">
                <!-- WHAT IS THIS PRODUCT -->
                <section class="mb-5">
                    <h3 class="mb-3">What is this product?</h3>
                    <p id="whatIsText" class="text-muted" style="line-height: 1.8;"></p>
                </section>

                <!-- BENEFITS -->
                <section class="mb-5">
                    <h3 class="mb-3">Benefits</h3>
                    <ul id="benefitsList" class="list-unstyled">
                        <li class="mb-2">• Loading benefits...</li>
                    </ul>
                </section>

                <!-- HOW TO USE -->
                <section class="mb-5">
                    <h3 class="mb-3">How to Use</h3>
                    <p id="howToUseText" class="text-muted">Use as directed on the product label. Patch test before first use and apply consistently as part of your skincare routine.</p>
                </section>

                <!-- INGREDIENTS -->
                <section class="mb-5">
                    <h3 class="mb-3">Key Ingredients</h3>
                    <p id="ingredientText" class="text-muted">Loading ingredients...</p>
                </section>

                <!-- ATTRIBUTES/SPECS -->
                <section class="mb-5">
                    <h3 class="mb-3">Specifications</h3>
                    <table class="table" id="attributesTable">
                        <tbody></tbody>
                    </table>
                </section>

                <!-- FAQs -->
                <section class="mb-5">
                    <h3 class="mb-3">FAQ</h3>
                    <div id="faqsContainer"></div>
                </section>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <div class="card p-4 bg-light">
                    <h5 class="mb-3">Product Info</h5>
                    <dl style="font-size: 0.95rem;">
                        <dt>SKU</dt>
                        <dd id="skuInfo" class="mb-2">-</dd>
                        
                        <dt>Category</dt>
                        <dd id="categoryInfo" class="mb-2">-</dd>
                        
                        <dt>Skin Concern</dt>
                        <dd id="concernInfo" class="mb-2">-</dd>
                        
                        <dt>Product Type</dt>
                        <dd id="typeInfo" class="mb-2">-</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div id="errorMessage" style="display: none;" class="alert alert-danger mt-5">
        <h4>Product Not Found</h4>
        <p id="errorText"></p>
    </div>
</div>

<script>
    async function loadProductDetail() {
        const urlParams = new URLSearchParams(window.location.search);
        const productKey = urlParams.get('product') || urlParams.get('id') || '';

        if (!productKey) {
            showError('No product specified in URL');
            return;
        }

        try {
            const response = await fetch(`fetch_product_detail.php?product=${encodeURIComponent(productKey)}`);
            const result = await response.json();

            if (result.error) {
                showError(result.error);
                return;
            }

            const product = result.product;
            if (!product) {
                showError('Product data not found');
                return;
            }

            displayProduct(product);
        } catch (error) {
            showError(error.message || 'Failed to load product');
            console.error(error);
        }
    }

    function displayProduct(product) {
        const loading = document.getElementById('loadingMessage');
        const detail = document.getElementById('productDetail');
        const error = document.getElementById('errorMessage');

        loading.style.display = 'none';
        error.style.display = 'none';
        detail.style.display = 'block';

        const images = product.images || [product.imageUrl];

        document.getElementById('productName').textContent = product.name || 'Product Name';
        document.getElementById('productSubtitle').textContent = product.subtitle || product.description || '';
        document.getElementById('productPrice').textContent = product.price || '₹0';
        document.getElementById('productStars').textContent = product.stars || '★★★★½';
        document.getElementById('reviewCount').textContent = product.reviewsCount || '120';
        document.getElementById('boughtTag').textContent = product.boughtTag || '196+ bought in past month';
        document.getElementById('whatIsText').textContent = product.whatIs || product.description || '';
        document.getElementById('howToUseText').textContent = product.howToUse || 'Use as directed on the product label.';
        document.getElementById('ingredientText').textContent = product.ingredient || product.displayIngredient || 'N/A';

        document.getElementById('skuInfo').textContent = product.sku || '-';
        document.getElementById('categoryInfo').textContent = product.category || '-';
        document.getElementById('concernInfo').textContent = product.concern || product.displayConcern || '-';
        document.getElementById('typeInfo').textContent = product.type || '-';

        // Image Gallery
        document.getElementById('mainImage').src = images[0] || './assets/img/product.webp';
        const thumbnailGallery = document.getElementById('thumbnailGallery');
        thumbnailGallery.innerHTML = '';
        
        images.forEach((img, index) => {
            const thumb = document.createElement('img');
            thumb.src = img;
            thumb.alt = `View ${index + 1}`;
            thumb.className = 'rounded';
            thumb.style.cssText = 'width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;';
            thumb.onclick = () => {
                document.getElementById('mainImage').src = img;
                document.querySelectorAll('#thumbnailGallery img').forEach(t => t.style.borderColor = 'transparent');
                thumb.style.borderColor = '#000';
            };
            if (index === 0) thumb.style.borderColor = '#000';
            thumbnailGallery.appendChild(thumb);
        });

        // Benefits
        const benefits = product.benefits || [];
        document.getElementById('benefitsList').innerHTML = benefits.length > 0
            ? benefits.map(b => `<li class="mb-2">✓ ${b}</li>`).join('')
            : '<li>Premium skincare benefits included</li>';

        // Attributes/Specs
        const attributesTable = document.getElementById('attributesTable').querySelector('tbody');
        attributesTable.innerHTML = '';
        if (product.attributes && product.attributes.length > 0) {
            product.attributes.forEach(attr => {
                const row = `<tr><td><strong>${attr.name}</strong></td><td>${attr.value}</td></tr>`;
                attributesTable.innerHTML += row;
            });
        } else {
            attributesTable.innerHTML = '<tr><td colspan="2" class="text-muted">No additional specs</td></tr>';
        }

        // FAQs
        const faqsContainer = document.getElementById('faqsContainer');
        faqsContainer.innerHTML = '';
        if (product.faqs && product.faqs.length > 0) {
            product.faqs.forEach((faq, index) => {
                const faqHtml = `
                    <div class="mb-3 border-bottom pb-3">
                        <h6 class="mb-2">${faq.question}</h6>
                        <p class="text-muted mb-0">${faq.answer}</p>
                    </div>
                `;
                faqsContainer.innerHTML += faqHtml;
            });
        }

        document.title = `${product.name} | /PHD/`;
    }

    function showError(message) {
        document.getElementById('loadingMessage').style.display = 'none';
        document.getElementById('productDetail').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'block';
        document.getElementById('errorText').textContent = message;
    }

    function addToCart() {
        alert('Add to cart functionality coming soon!');
    }

    document.addEventListener('DOMContentLoaded', loadProductDetail);
</script>

<style>
    .product-gallery img {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-info-section h1 {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .price-section {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .key-details dt {
        font-weight: 600;
        color: #333;
    }

    .key-details dd {
        color: #666;
    }

    .stars {
        color: #ffc107;
    }

    @media (max-width: 768px) {
        .product-info-section h1 {
            font-size: 1.5rem;
        }
        #productPrice {
            font-size: 1.5rem !important;
        }
    }
</style>

<?php include 'footer.php'; ?>
