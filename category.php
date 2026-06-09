<?php include 'navbar.php'; ?>

<!-- PAGE TITLE & DESCRIPTION -->
<div class="container mt-5">
    <div class="shop-layout">

        <!-- SIDEBAR FILTERS -->
        <aside class="sidebar">
            <h5 id="sidebarTitle">Category</h5>

            <!-- PRICE FILTER -->
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

            <!-- SKIN CONCERN FILTER -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Skin Concern <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-concern">
                    <label><input type="checkbox" value="acne" onchange="applyFilters()"> Acne <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="brightening" onchange="applyFilters()"> Brightening <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="pigmentation" onchange="applyFilters()"> Pigmentation <span class="count">(0)</span></label>
                </div>
            </div>

            <!-- INGREDIENT FILTER -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Ingredient <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-ingredient">
                    <label><input type="checkbox" value="Salicylic-Acid" onchange="applyFilters()"> Salicylic Acid <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="Niacinamide" onchange="applyFilters()"> Niacinamide <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="Hyaluronic" onchange="applyFilters()"> Hyaluronic Acid <span class="count">(0)</span></label>
                </div>
            </div>

            <!-- PRODUCT TYPE FILTER -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Product Type <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-type">
                    <label><input type="checkbox" value="serum" onchange="applyFilters()"> Serum <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="moisturizer" onchange="applyFilters()"> Moisturizer <span class="count">(0)</span></label>
                    <label><input type="checkbox" value="cleanser" onchange="applyFilters()"> Cleanser <span class="count">(0)</span></label>
                </div>
            </div>
        </aside>

        <!-- PRODUCT GRID -->
        <div class="product-grid-wrap">
            <h1 class="text-center" id="categoryPageTitle">Category</h1>
            <div class="product-count" id="productCount">Loading products...</div>

            <div class="products-grid" id="productsGrid">
                <div class="text-center w-100 py-4">
                    <div class="spinner-border text-dark"></div>
                    <p class="mt-2">Loading products...</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
