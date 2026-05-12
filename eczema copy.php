<?php include 'navbar.php'; ?>

<img src="./assets/img/lines-and-wrinkles.webp" alt="" class="img-fluid">

<!-- <section class="salicylic_acid_first_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-text">
                    Home / Hyaluronic Acid
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <h1 data-aos="fade-up">Hyaluronic Acid</h1>
                <p data-aos="fade-up" data-aos-delay="200">
                    Discover the hydrating power of Hyaluronic Acid with our exclusive /PHD/ collection. Our advanced formulations deliver deep, lasting moisture to plump and revitalize your skin, reducing fine lines and promoting a smooth, radiant complexion. Ideal for all skin types, experience a refreshed and youthful glow with every application.
                </p>
            </div>
        </div>
    </div>
</section> -->

<!-- BREADCRUMB -->
<!-- <div class="breadcrumb-row">
        <a href="#">Home</a> /Salicylic Acid
    </div> -->

<!-- PAGE TITLE -->
<!-- <h1 class="page-title">Sunscreens</h1> -->

<!-- SHOP LAYOUT -->
<div class="container mt-5">
    <div class="shop-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h5>Eczema</h5>

            <!-- PRICE -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Price <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body">
                    <div class="price-range-wrap">
                        <input type="range" id="priceRange" min="0" max="1500" value="1500" oninput="updatePrice(this.value)" />
                        <div class="price-inputs">
                            <input type="number" id="priceMin" value="0" readonly />
                            <input type="number" id="priceMax" value="1500" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKIN CONCERN -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Skin Concern <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-concern">

                    <label><input type="checkbox" value="Acne" onchange="applyFilters()"> Acne <span
                            class="count">(1)</span></label>

                </div>
            </div>

            <!-- INGREDIENT -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Ingredient <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-ingredient">
                    <label><input type="checkbox" value="Centella-Asiatica" onchange="applyFilters()"> Centella Asiatica (Cica) <span
                            class="count">(1)</span></label>
                    <label><input type="checkbox" value="Niacinamide" onchange="applyFilters()"> Niacinamide <span
                            class="count">(1)</span></label>
                    <label><input type="checkbox" value="Peptazin™" onchange="applyFilters()"> Peptazin™ <span
                            class="count">(1)</span></label>
                    <label><input type="checkbox" value="Peptide" onchange="applyFilters()"> Peptide <span
                            class="count">(2)</span></label>
                    <label><input type="checkbox" value="Salicylic-Acid" onchange="applyFilters()"> Salicylic Acid <span
                            class="count">(1)</span></label>
                    <label><input type="checkbox" value="Zinc-Pca" onchange="applyFilters()"> Zinc PCA <span
                            class="count">(1)</span></label>
                </div>
            </div>


            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Product type
                    <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-ingredient">
                    <label><input type="checkbox" value="" onchange="applyFilters()"> Acne Treatments & Kits <span
                            class="count">(1)</span></label>
                    <label><input type="checkbox" value="New-gen" onchange="applyFilters()"> Face Serums <span
                            class="count">(1)</span></label>


                </div>
            </div>

            <!-- INGREDIENT -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Country of Origin <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-ingredient">
                    <label><input type="checkbox" value="Centella-Asiatica" onchange="applyFilters()"> india <span
                            class="count">(1)</span></label>


                </div>
            </div>


        </aside>

        <!-- PRODUCT GRID -->
        <div class="product-grid-wrap">
            <h1 class="text-center">Eczema</h1>
            <div class="product-count" id="productCount">7 products</div>

            <div class="products-grid" id="productsGrid">
                <!-- Products will be loaded from GraphQL backend -->
            </div><!-- /products-grid -->
        </div><!-- /product-grid-wrap -->
    </div>
</div>
<!-- /shop-layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ── Toggle filter sections ── */
    function toggleFilter(header) {
        const body = header.nextElementSibling;
        const icon = header.querySelector('.toggle-icon');
        const isOpen = body.classList.toggle('open');
        icon.textContent = isOpen ? '−' : '+';
    }

    /* ── Price range ── */
    function updatePrice(val) {
        document.getElementById('priceMax').value = val;
        applyFilters();
    }

    let allProducts = [];

    async function loadProductsPage() {
        try {
            const data = await loadProducts({ concern: 'eczema' });
            allProducts = data.products || [];
            renderProducts(allProducts);
            document.getElementById('productCount').textContent = `${allProducts.length} product${allProducts.length !== 1 ? 's' : ''}`;
        } catch (error) {
            console.error('Error loading products:', error);
            document.getElementById('productsGrid').innerHTML = '<p class="text-center">Unable to load products from backend.</p>';
        }
    }

    function renderProducts(products) {
        const grid = document.getElementById('productsGrid');
        if (!products.length) {
            grid.innerHTML = '<p class="text-center">No products found.</p>';
            return;
        }

        grid.innerHTML = products.map(product => `
            <div class="product-card" data-price="${product.price}" data-concern="${product.concern}" data-ingredient="${product.ingredients.join(' ')}" data-type="${product.type}" data-product-id="${product.id}">
                <a href="${product.detailPage}">
                    <div class="product-img-wrap">
                        <img class="img-primary" src="${product.imageUrl}" alt="${product.name}" />
                    </div>
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-sub">${product.description}</div>
                        <div><span class="stars">★★★★½</span><span class="review-count">(Reviews)</span></div>
                        <div class="product-price">₹${product.price}</div>
                    </div>
                </a>
                <button class="btn-cart">Add to Cart</button>
            </div>
        `).join('');

        attachAddToCartButtons();
    }

    function attachAddToCartButtons() {
        document.querySelectorAll('.product-card .btn-cart').forEach(button => {
            button.removeEventListener('click', handleAddToCartButton);
            button.addEventListener('click', handleAddToCartButton);
        });
    }

    async function handleAddToCartButton(event) {
        const card = event.currentTarget.closest('.product-card');
        if (!card) return;

        const productId = card.dataset.productId;
        try {
            const result = await addToCart(productId, 1);
            if (result.addToCart.success) {
                alert('Product added to cart');
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge();
                }
            } else {
                alert('Unable to add product: ' + result.addToCart.message);
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            alert('Unable to add product to cart');
        }
    }

    /* ── Main filter logic ── */
    function applyFilters() {
        const maxPrice = parseInt(document.getElementById('priceRange').value, 10);
        const checkedConcerns = [...document.querySelectorAll('#filter-concern input:checked')].map(i => i.value);
        const checkedIngredients = [...document.querySelectorAll('#filter-ingredient input:checked')].map(i => i.value);
        const checkedTypes = [...document.querySelectorAll('#filter-type input:checked')].map(i => i.value);

        const filtered = allProducts.filter(product => {
            const priceOk = product.price <= maxPrice;
            const concernOk = checkedConcerns.length === 0 || checkedConcerns.some(c => product.concern.toLowerCase().includes(c.toLowerCase()));
            const ingredientOk = checkedIngredients.length === 0 || checkedIngredients.some(i => product.ingredients.some(pi => pi.toLowerCase().includes(i.toLowerCase())));
            const typeOk = checkedTypes.length === 0 || checkedTypes.some(t => product.type.toLowerCase().includes(t.toLowerCase()));
            return priceOk && concernOk && ingredientOk && typeOk;
        });

        renderProducts(filtered);
        document.getElementById('productCount').textContent = `${filtered.length} product${filtered.length !== 1 ? 's' : ''}`;
    }

    document.addEventListener('DOMContentLoaded', loadProductsPage);
</script>
<?php include 'footer.php'; ?>
</body>

</html>