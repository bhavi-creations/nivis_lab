<?php include 'navbar.php'; ?>


<img src="./assets/img/moisturizers.webp" alt="" class="img-fluid">

<!-- BREADCRUMB -->
<div class="breadcrumb-row">
  <a href="#">Home</a> /Moisturizers
</div>

<!-- PAGE TITLE -->
<h1 class="page-title">Moisturizers</h1>

<!-- SHOP LAYOUT -->
<div class="container">
  <div class="shop-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <h5>Filters</h5>

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
          <!-- <label><input type="checkbox" value="pigmentation" onchange="applyFilters()"> Pigmentation <span
                class="count">(1)</span></label> -->
          <label><input type="checkbox" value="acne" onchange="applyFilters()"> Acne <span
              class="count">(1)</span></label>
          <label><input type="checkbox" value="eczema" onchange="applyFilters()"> Eczema <span
              class="count">(1)</span></label>
          <label><input type="checkbox" value="brightening" onchange="applyFilters()"> Brightening <span
              class="count">(1)</span></label>
          <!-- <label><input type="checkbox" value="hydration" onchange="applyFilters()"> Hydration <span
                class="count">(1)</span></label> -->
        </div>
      </div>


      <div class="filter-group">
        <div class="filter-group-header" onclick="toggleFilter(this)">
          Ingredient <span class="toggle-icon">+</span>
        </div>
        <div class="filter-body" id="filter-ingredient">
          <label><input type="checkbox" value="glycerin" onchange="applyFilters()"> glycerin Asiatica (Cica) <span
              class="count">(1)</span></label>
          <label><input type="checkbox" value="ceramides" onchange="applyFilters()"> Ceramides <span
              class="count">(1)</span></label>
          <label><input type="checkbox" value="coenzyme" onchange="applyFilters()"> Coenzyme Q10 (CoQ10) <span
              class="count">(1)</span></label>

          <label><input type="checkbox" value="glycerin" onchange="applyFilters()"> Ferulic Acid <span
              class="count">(1)</span></label>


          <label><input type="checkbox" value="glycerin" onchange="applyFilters()"> Glycerin <span
              class="count">(2)</span></label>
          <!-- <label><input type="checkbox" value="hyaluronic-acid" onchange="applyFilters()"> Hyaluronic Acid <span
                class="count">(1)</span></label> -->
        </div>
      </div>

      <!-- PRODUCT TYPE -->
      <div class="filter-group">
        <div class="filter-group-header" onclick="toggleFilter(this)">
          Product Type <span class="toggle-icon">+</span>
        </div>
        <div class="filter-body" id="filter-type">
          <label><input type="checkbox" value="face-moisturizers" onchange="applyFilters()"> Face Moisturizers <span
              class="count">(2)</span></label>
          <label><input type="checkbox" value="lotus_moisturizers" onchange="applyFilters()"> Lotions & Moisturizers<span
              class="count">(6)</span></label>
        </div>
      </div>
    </aside>

    <!-- PRODUCT GRID -->
    <div class="product-grid-wrap">
      <div class="product-count" id="productCount">7 products</div>

      <div class="products-grid" id="productsGrid">

        <!-- PRODUCT 1 -->

        <div class="product-card" data-price="649" data-concern="acne pigmentation" data-ingredient="centella"
          data-type="face-serum">
          <a href="niacinamide_oil_free_moisturizer_serum.php">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&q=80"
                alt="Niacinamide Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=400&q=80"
                alt="Niacinamide Serum hover" />
            </div>
            <div class="product-info">
              <div class="product-name">3% Niacinamide Oil-Free Moisturizer</div>
              <div class="product-sub">/ Non-greasy moisturizer to regulate sebum production and promote healthy looking skin/</div>
              <div><span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span><span class="review-count">( 91 reviews)</span></div>
              <div class="product-price">₹549</div>
              <span class="bought-tag">196+ bought in past month</span>

            </div>
          </a>
          <button class="btn-cart">Add to Cart</button>
        </div>


        <!-- PRODUCT 2 -->
        <div class="product-card" data-price="699" data-concern="pigmentation" data-ingredient="alpha-arbutin arbutin"
          data-type="face-serum">
          <a href="vitamin_c_brightening_moisturizer.php">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1599305090598-fe179d501227?w=400&q=80"
                alt="Alpha Arbutin Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=400&q=80"
                alt="Alpha Arbutin hover" />
            </div>
            <div class="product-info">
              <div class="product-name">Vitamin C Brightening Moisturizer</div>
              <div class="product-sub">/ Lightweight moisturizer that brightens and hydrates skin /</div>
              <div><span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span><span class="review-count">( 106 reviews)</span></div>
              <div class="product-price">₹549</div>
              <span class="bought-tag">129+ bought in past month</span>


            </div>
          </a>
          <button class="btn-cart">Add to Cart</button>
        </div>

        <!-- PRODUCT 3 -->
        <div class="product-card" data-price="699" data-concern="brightening" data-ingredient="ferulic-acid"
          data-type="face-serum">
          <div class="product-img-wrap">
            <a href="ceramides_intensive_repair_cream.php">
              <img class="img-primary" src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&q=80"
                alt="Vitamin C Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=400&q=80"
                alt="Vitamin C hover" />
          </div>
          <div class="product-info">
            <div class="product-name">1% Ceramides Intensive Repair Cream</div>
            <div class="product-sub">/ Helps relieve the symptoms of dry, irritated, eczema-prone skin. /</div>
            <div><span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span><span class="review-count">( 22 reviews) </span></div>
            <div class="product-price">₹549</div>
            <span class="bought-tag">58+ bought in past month</span>

          </div> </a>
          <button class="btn-cart">Add to Cart</button>
        </div>

     

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

  /* ── Main filter logic ── */
  function applyFilters() {
    const maxPrice = parseInt(document.getElementById('priceRange').value);

    const checkedConcerns = [...document.querySelectorAll('#filter-concern input:checked')].map(i => i.value);
    const checkedIngredients = [...document.querySelectorAll('#filter-ingredient input:checked')].map(i => i.value);
    const checkedTypes = [...document.querySelectorAll('#filter-type input:checked')].map(i => i.value);

    const cards = document.querySelectorAll('.product-card');
    let visible = 0;

    cards.forEach(card => {
      const price = parseInt(card.dataset.price);
      const concerns = card.dataset.concern.split(' ');
      const ingredients = card.dataset.ingredient.split(' ');
      const type = card.dataset.type.split(' ');

      const priceOk = price <= maxPrice;
      const concernOk = checkedConcerns.length === 0 || checkedConcerns.some(c => concerns.includes(c));
      const ingredOk = checkedIngredients.length === 0 || checkedIngredients.some(i => ingredients.includes(i));
      const typeOk = checkedTypes.length === 0 || checkedTypes.some(t => type.includes(t));

      const show = priceOk && concernOk && ingredOk && typeOk;
      card.classList.toggle('hidden', !show);
      if (show) visible++;
    });

    document.getElementById('productCount').textContent = visible + ' product' + (visible !== 1 ? 's' : '');
  }
</script>

<script>
const API_URL = "fetch_moisturizers.php";

async function loadMoisturizerProducts() {
  const grid = document.getElementById("productsGrid");

  try {
    const response = await fetch(API_URL);
    const result = await response.json();

    console.log("Moisturizers Result:", result);

    const products = result.data.productsByCategory;

    grid.innerHTML = "";

    if (!products || products.length === 0) {
      grid.innerHTML = "<p>No moisturizers found.</p>";
      document.getElementById("productCount").textContent = "0 products";
      return;
    }

    products.forEach(product => {
      grid.innerHTML += `
        <div class="product-card"
          data-price="${product.price || 0}"
          data-concern="${product.concern || ''}"
          data-ingredient="${product.ingredient || ''}"
          data-type="${product.type || ''}">

          <a href="${product.link || '#'}">
            <div class="product-img-wrap">
              <img class="img-primary"
                src="${product.primaryImage || './assets/img/logo.jpeg'}"
                alt="${product.name}">

              <img class="img-secondary"
                src="${product.secondaryImage || product.primaryImage || './assets/img/logo.jpeg'}"
                alt="${product.name}">
            </div>

            <div class="product-info">
              <div class="product-name">${product.name}</div>

              <div class="product-sub">
                / ${product.subtitle || ''} /
              </div>

              <div>
                <span class="stars">${product.stars || '?????'}</span>
                <span class="review-count">( ${product.reviewsCount || 0} reviews)</span>
              </div>

              <div class="product-price">₹${product.price}</div>

              <span class="bought-tag">
                ${product.boughtTag || ''}
              </span>
            </div>
          </a>

          <button class="btn-cart">Add to Cart</button>
        </div>
      `;
    });

    document.getElementById("productCount").textContent =
      products.length + " products";

  } catch (error) {
    console.error(error);
    grid.innerHTML = "<p>Products loading failed.</p>";
  }
}

// Common assets/js/category-products.js handles this page.
</script>
<?php include 'footer.php'; ?>
</body>

</html>
