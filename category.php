<?php include 'navbar.php'; ?>

<!-- PAGE TITLE & DESCRIPTION -->
<div class="container my-5">
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
                        <input type="range" id="priceRange" min="0" max="1500" value="1500" />
                        <div class="price-inputs">
                            <input type="number" id="priceMin" value="0" />
                            <input type="number" id="priceMax" value="1500" />
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
                    <p class="filter-empty">Loading filters...</p>
                </div>
            </div>

            <!-- INGREDIENT FILTER -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Ingredient <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-ingredient">
                    <p class="filter-empty">Loading filters...</p>
                </div>
            </div>

            <!-- PRODUCT TYPE FILTER -->
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilter(this)">
                    Product Type <span class="toggle-icon">+</span>
                </div>
                <div class="filter-body" id="filter-type">
                    <p class="filter-empty">Loading filters...</p>
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
