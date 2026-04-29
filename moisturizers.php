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

        <!-- INGREDIENT -->
        <!-- <div class="filter-group">
          <div class="filter-group-header" onclick="toggleFilter(this)">
            Ingredient <span class="toggle-icon">+</span>
          </div>
          <div class="filter-body" id="filter-ingredient">
            <label><input type="checkbox" value="alpglycerin" onchange="applyFilters()"> Alpha ceramides <span
                class="count">(1)</span></label>
            <label><input type="checkbox" value="ceramides" onchange="applyFilters()"> ceramides <span
                class="count">(1)</span></label>
            <label><input type="checkbox" value="coenzyme" onchange="applyFilters()"> coenzyme <span
                class="count">(1)</span></label>
            <label><input type="checkbox" value="glycerin" onchange="applyFilters()"> glycerin Asiatica (Cica) <span
                class="count">(2)</span></label>
            <label><input type="checkbox" value="glycerin" onchange="applyFilters()"> Ferulic Acid <span
                class="count">(1)</span></label>
            <label><input type="checkbox" value="hyaluronic-acid" onchange="applyFilters()"> Hyaluronic Acid <span
                class="count">(1)</span></label>
          </div>
        </div> -->


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
            <label><input type="checkbox" value="face-moisturizers" onchange="applyFilters()"> Face Moisturizers  <span
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
          <div class="product-card" data-price="649" data-concern="acne pigmentation" data-ingredient="glycerin"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&q=80"
                alt="Niacinamide Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=400&q=80"
                alt="Niacinamide Serum hover" />
            </div>
            <div class="product-info">
              <div class="product-name">10% Niacinamide Spot Correcting Serum</div>
              <div class="product-sub">/ Solution for pore, acne marks and blemishes /</div>
              <div><span class="stars">★★★★½</span><span class="review-count">(344 reviews)</span></div>
              <div class="product-price">₹649</div>
              <span class="bought-tag">196+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 2 -->
          <div class="product-card" data-price="699" data-concern="pigmentation" data-ingredient="alpglycerin ceramides"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1599305090598-fe179d501227?w=400&q=80"
                alt="Alpha ceramides Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=400&q=80"
                alt="Alpha ceramides hover" />
            </div>
            <div class="product-info">
              <div class="product-name">2% Alpha ceramides Depigmentation Serum</div>
              <div class="product-sub">/ Solution for hyperpigmentation, acne marks and uneven skin tone /</div>
              <div><span class="stars">★★★★½</span><span class="review-count">(327 reviews)</span></div>
              <div class="product-price">₹699</div>
              <span class="bought-tag">129+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 3 -->
          <div class="product-card" data-price="699" data-concern="brightening" data-ingredient="glycerin"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&q=80"
                alt="Vitamin C Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=400&q=80"
                alt="Vitamin C hover" />
            </div>
            <div class="product-info">
              <div class="product-name">10% Vitamin C Brightening Serum</div>
              <div class="product-sub">/ Solution for dark spots, uneven skin tone, dullness /</div>
              <div><span class="stars">★★★★½</span><span class="review-count">(128 reviews)</span></div>
              <div class="product-price">₹699</div>
              <span class="bought-tag">58+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 4 -->
          <div class="product-card" data-price="699" data-concern="eczema" data-ingredient="coenzyme"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=400&q=80"
                alt="Retinol Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1582095133179-bfd08e2fb6b8?w=400&q=80"
                alt="Retinol hover" />
            </div>
            <div class="product-info">
              <div class="product-name">0.3% Pure Retinol Face Serum</div>
              <div class="product-sub">/ Solution for visible signs of ageing like fine lines, wrinkles /</div>
              <div><span class="stars">★★★★½</span><span class="review-count">(128 reviews)</span></div>
              <div class="product-price">₹699</div>
              <span class="bought-tag">58+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 5 -->
          <div class="product-card" data-price="649" data-concern="acne" data-ingredient="glycerin"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&q=80"
                alt="Salicylic Acid Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1576426863848-c21f53c60b19?w=400&q=80"
                alt="Salicylic Acid hover" />
            </div>
            <div class="product-info">
              <div class="product-name">2% Salicylic Acid Anti-Acne Serum</div>
              <div class="product-sub">/ Solution for acne, clogged pores and sebum regulation /</div>
              <div><span class="stars">★★★★</span><span class="review-count">(83 reviews)</span></div>
              <div class="product-price">₹649</div>
              <span class="bought-tag">58+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 6 -->
          <div class="product-card" data-price="495" data-concern="acne" data-ingredient="glycerin"
            data-type="face-moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=400&q=80"
                alt="Acne Spot Gel" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=400&q=80"
                alt="Acne Spot Gel hover" />
            </div>
            <div class="product-info">
              <div class="product-name">Salicylic Acid Acne Spot Treatment Gel</div>
              <div class="product-sub">/ Solution for rapid healing of acne, pimples and breakouts /</div>
              <div><span class="stars">★★★★★</span><span class="review-count">(47 reviews)</span></div>
              <div class="product-price">₹495</div>
              <span class="bought-tag">46+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
          </div>

          <!-- PRODUCT 7 -->
          <div class="product-card" data-price="649" data-concern="hydration" data-ingredient="hyaluronic-acid"
            data-type="lotus_moisturizers">
            <div class="product-img-wrap">
              <img class="img-primary" src="https://images.unsplash.com/photo-1567721913486-6585f069b3b0?w=400&q=80"
                alt="Hyaluronic Acid Serum" />
              <img class="img-secondary" src="https://images.unsplash.com/photo-1579722821273-0f6c7d44362f?w=400&q=80"
                alt="Hyaluronic Acid hover" />
            </div>
            <div class="product-info">
              <div class="product-name">2% Hyaluronic Acid Dewy Skin Serum</div>
              <div class="product-sub">/ Solution for compromised skin barrier, dull, dry and dehydrated skin /</div>
              <div><span class="stars">★★★★</span><span class="review-count">(9 reviews)</span></div>
              <div class="product-price">₹649</div>
              <span class="bought-tag">500+ bought in past month</span>
              <button class="btn-cart">Add to Cart</button>
            </div>
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
  <?php include 'footer.php'; ?>
</body>

</html>