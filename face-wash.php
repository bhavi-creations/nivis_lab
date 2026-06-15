<?php include 'navbar.php'; ?>

<img src="./assets/img/face-wash.webp" alt="" class="img-fluid">

<div class="breadcrumb-row">
  <a href="#">Home</a> / Face Wash
</div>

<h1 class="page-title">Face Wash</h1>

<div class="container">
  <div class="shop-layout">

    <aside class="sidebar">
      <h5>Filters</h5>

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

      <div class="filter-group">
        <div class="filter-group-header" onclick="toggleFilter(this)">
          Ingredient <span class="toggle-icon">+</span>
        </div>
        <div class="filter-body" id="filter-ingredient">
          <label><input type="checkbox" value="Ceramides" onchange="applyFilters()"> Ceramides <span class="count">(1)</span></label>
          <label><input type="checkbox" value="Glycerin" onchange="applyFilters()"> Glycerin <span class="count">(1)</span></label>
          <label><input type="checkbox" value="Hyaluronic" onchange="applyFilters()"> Hyaluronic Acid <span class="count">(1)</span></label>
          <label><input type="checkbox" value="panthenol" onchange="applyFilters()"> Panthenol <span class="count">(1)</span></label>
        </div>
      </div>

      <div class="filter-group">
        <div class="filter-group-header" onclick="toggleFilter(this)">
          Product Type <span class="toggle-icon">+</span>
        </div>
        <div class="filter-body" id="filter-type">
          <label><input type="checkbox" value="facial-cleansers" onchange="applyFilters()"> Facial Cleansers <span class="count">(2)</span></label>
        </div>
      </div>
    </aside>

    <div class="product-grid-wrap">
      <div class="product-count" id="productCount">Loading products...</div>

      <div class="products-grid" id="productsGrid">
        <div class="text-center w-100 py-4">
          <div class="spinner-border text-dark"></div>
          <p class="mt-2">Loading Face Wash products...</p>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    fetchFaceWashProducts();
  });

  // Backend నుండి కేవలం face-wash డేటాను తెచ్చే ఫంక్షన్
  async function fetchFaceWashProducts() {
    const grid = document.getElementById('productsGrid');
    if (!grid) return;

    try {
      // ఇక్కడ type=face-wash అని పంపుతున్నాం, మన డైనమిక్ బ్యాకెండ్ దీనికి తగిన ప్రొడక్ట్స్ మాత్రమే ఇస్తుంది
      const response = await fetch('fetch_data.php?type=face-wash');
      const rawData = await response.text();

      let result;
      try {
        result = JSON.parse(rawData);
      } catch (e) {
        console.log(rawData);
        grid.innerHTML = `<div class="alert alert-danger">Invalid JSON response</div>`;
        return;
      }

      if (result.errors) {
        console.error(result.errors);
        grid.innerHTML = `<div class="alert alert-danger">GraphQL error. Console check cheyyi.</div>`;
        return;
      }

      const products = result.data?.products?.items || [];
      console.log('FACE WASH PRODUCTS:', products);

      renderProducts(products);

    } catch (error) {
      grid.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
    }
  }

  function cleanText(value, defaultText = '') {
    if (!value) return defaultText;
    if (Array.isArray(value)) {
      return value.map(v => cleanText(v, '')).join(' ');
    }
    if (typeof value === 'object') {
      return value.name || value.value || value.label || value.url_key || defaultText;
    }
    return String(value);
  }

  function getNumberFromPrice(priceText) {
    return parseInt(String(priceText || '').replace(/[^\d]/g, '')) || 0;
  }

  // కేవలం Face Wash ప్రొడక్ట్స్‌ను డైనమిక్‌గా స్క్రీన్ మీద చూపించే ఫంక్షన్
  function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    const count = document.getElementById('productCount');

    grid.innerHTML = '';

    if (!products || products.length === 0) {
      grid.innerHTML = `<p class="text-center w-100">No Face Wash products found.</p>`;
      if (count) count.textContent = '0 products';
      return;
    }

    if (count) {
      count.textContent = products.length + ' product' + (products.length !== 1 ? 's' : '');
    }

    products.forEach(product => {
      const imageUrl = product.image?.url ?
        `http://localhost:3000${product.image.url}` :
        './assets/img/logo.jpeg';

      const productName = cleanText(product.name, 'Product Name');
      const productPrice = product.price?.regular?.text || '₹0';
      const priceNumber = getNumberFromPrice(productPrice);
      const concern = cleanText(product.concern, 'Face-Wash');
      const slug = product.url_key || '#';
      
      // EverShop నుండి వచ్చే డైనమిక్ కేటగిరీని ఫిల్టర్ కోసం వాడుతున్నాం
      const productType = cleanText(product.category, 'facial-cleansers').toLowerCase();

      grid.innerHTML += `
        <div class="product-card"
          data-price="${priceNumber}"
          data-concern="${concern.toLowerCase()}"
          data-ingredient="skincare"
          data-type="${productType}">

          <a href="${slug}.php">
            <div class="product-img-wrap">
              <img class="img-primary" src="${imageUrl}" alt="${productName}" />
              <img class="img-secondary" src="${imageUrl}" alt="${productName}" />
            </div>

            <div class="product-info">
              <div class="product-name">${productName}</div>
              <div class="product-sub">/ ${concern} /</div>

              <div>
                <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                <span class="review-count">(120 reviews)</span>
              </div>

              <div class="product-price">${productPrice}</div>

              <span class="bought-tag">
                196+ bought in past month
              </span>
            </div>
          </a>

          <button class="btn-cart">
            Add to Cart
          </button>
        </div>
      `;
    });
  }

  function toggleFilter(header) {
    const body = header.nextElementSibling;
    const icon = header.querySelector('.toggle-icon');
    if (!body || !icon) return;

    const isOpen = body.classList.toggle('open');
    icon.textContent = isOpen ? '−' : '+';
  }

  function updatePrice(val) {
    const priceMax = document.getElementById('priceMax');
    if (priceMax) priceMax.value = val;
    applyFilters();
  }

  function getCheckedValues(selector) {
    return [...document.querySelectorAll(selector)].map(input => input.value.toLowerCase());
  }

  function applyFilters() {
    const priceRange = document.getElementById('priceRange');
    const productCount = document.getElementById('productCount');
    const maxPrice = priceRange ? parseInt(priceRange.value) : 999999;

    const checkedIngredients = getCheckedValues('#filter-ingredient input:checked');
    const checkedTypes = getCheckedValues('#filter-type input:checked');

    const cards = document.querySelectorAll('.product-card');
    let visible = 0;

    cards.forEach(card => {
      const price = parseInt(card.dataset.price) || 0;
      const ingredients = (card.dataset.ingredient || '').toLowerCase().split(' ');
      const type = (card.dataset.type || '').toLowerCase().split(' ');

      const priceOk = price <= maxPrice;
      const ingredOk = checkedIngredients.length === 0 || checkedIngredients.some(i => ingredients.includes(i));
      const typeOk = checkedTypes.length === 0 || checkedTypes.some(t => type.includes(t));

      const show = priceOk && ingredOk && typeOk;
      card.classList.toggle('hidden', !show);

      if (show) visible++;
    });

    if (productCount) {
      productCount.textContent = visible + ' product' + (visible !== 1 ? 's' : '');
    }
  }
</script>

<?php include 'footer.php'; ?>
</body>
</html>