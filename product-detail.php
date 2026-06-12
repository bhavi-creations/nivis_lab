<?php include 'navbar.php'; ?>

<div class="breadcrumb-bar">
  <a href="index.php">Home</a> /
  <a href="products.php">Products</a> /
  <a id="breadcrumbProduct" href="#">Product</a>
</div>

<style>
  .product-detail-page .info-tab {
    background: transparent;
    border: 0;
    border-bottom: 2px solid transparent;
  }

  .product-detail-page .info-tab.active {
    border-bottom-color: #1a1a1a;
  }
</style>

<main class="container">
  <section class="product-wrapper product-detail-page" id="productDetail" aria-live="polite">
    <div class="product_img_section" id="imgSection">
      <div class="thumb-col" id="thumbCol"></div>

      <div class="main-img-wrap" id="mainImgWrap">
        <div class="arrow-zone left" id="arrowLeft">
          <div class="arrow-btn"><i class="fa fa-chevron-left"></i></div>
        </div>

        <img class="main-img" id="mainImg" src="./assets/img/product.webp" alt="Product" />

        <div class="arrow-zone right" id="arrowRight">
          <div class="arrow-btn"><i class="fa fa-chevron-right"></i></div>
        </div>
      </div>
    </div>

    <div class="right_side_product">
      <div class="badge-stamp" id="productBadge">NIVIS<br>LABS<br>*</div>
      <div class="product-brand">Nivis Labs</div>
      <h1 class="product-title" id="productTitle">Loading product...</h1>
      <div class="product-subtitle" id="productSubtitle"></div>

      <div class="star-row">
        <span class="stars" id="productStars"></span>
        <span class="review-count" id="productReviews"></span>
      </div>

      <div class="spf-badge" id="typeBadge" style="display:none"></div>
      <div class="product-desc" id="productDesc"></div>

      <div class="price-row">
        <span class="price-mrp-label">MRP:</span>
        <span class="price-main" id="productPrice">Rs. 0</span>
      </div>

      <button class="btn-buy" id="addToCartBtn" type="button">ADD TO CART</button>
      <div class="bought-note" id="boughtNote"></div>

      <hr class="sec-divider" />

      <section id="benefitsBlock" style="display:none">
        <div class="sec-title">BENEFITS</div>
        <div id="benefitsList"></div>
        <hr class="sec-divider" />
      </section>

      <section>
        <div class="sec-title">PRODUCT INFORMATION</div>
        <div class="info-tabs">
          <button class="info-tab active" type="button" data-tab="desc">Description</button>
          <button class="info-tab" type="button" data-tab="how">How to Use</button>
          <button class="info-tab" type="button" data-tab="details">Other Details</button>
        </div>

        <div class="product-desc-text" id="tab-desc"></div>
        <div class="product-desc-text" id="tab-how" style="display:none"></div>
        <div class="product-desc-text" id="tab-details" style="display:none"></div>
      </section>
    </div>
  </section>
</main>

<section class="container my-5" id="relatedProductsSection"></section>

<script>
  let currentProduct = null;
  let galleryImages = [];
  let currentImageIndex = 0;

  function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, char => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    }[char]));
  }

  function getProductKey() {
    const productParam = new URLSearchParams(window.location.search).get('product');
    if (productParam) return productParam;
    return window.location.pathname.split('/').pop().replace('.php', '');
  }

  function priceNumber(value) {
    return Number(String(value || '0').replace(/[^0-9.]/g, '')) || 0;
  }

  function priceLabel(value) {
    const amount = priceNumber(value);
    return amount ? `₹${amount.toLocaleString('en-IN')}` : '₹0';
  }

  function productId(product) {
    return String(product.id || product.sku || product.urlKey || product.url_key || product.name || 'product')
      .trim()
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '');
  }

  function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value || '';
  }

  function setImage(index) {
    if (!galleryImages.length) return;
    currentImageIndex = (index + galleryImages.length) % galleryImages.length;
    const image = galleryImages[currentImageIndex];
    const mainImg = document.getElementById('mainImg');
    mainImg.src = image;
    mainImg.alt = currentProduct?.name || 'Product';

    document.querySelectorAll('#thumbCol img').forEach((thumb, thumbIndex) => {
      thumb.classList.toggle('active', thumbIndex === currentImageIndex);
    });
  }

  function renderGallery(product) {
    galleryImages = Array.isArray(product.images) && product.images.length
      ? product.images
      : [product.imageUrl || './assets/img/product.webp'];

    const thumbCol = document.getElementById('thumbCol');
    thumbCol.innerHTML = galleryImages.map((image, index) => `
      <img class="thumb-item${index === 0 ? ' active' : ''}" src="${escapeHtml(image)}" alt="${escapeHtml(product.name || 'Product')} image ${index + 1}" />
    `).join('');

    thumbCol.querySelectorAll('img').forEach((thumb, index) => {
      thumb.addEventListener('click', () => setImage(index));
    });

    setImage(0);
  }

  function renderBenefits(product) {
    const benefits = Array.isArray(product.benefits) ? product.benefits.filter(Boolean) : [];
    const block = document.getElementById('benefitsBlock');
    const list = document.getElementById('benefitsList');

    if (!benefits.length) {
      block.style.display = 'none';
      return;
    }

    block.style.display = '';
    list.innerHTML = benefits.map(benefit => `
      <div class="benefit-item">
        <div class="benefit-icon"><i class="fa fa-check"></i></div>
        <div class="benefit-text">${escapeHtml(benefit)}</div>
      </div>
    `).join('');
  }

  function renderDetails(product) {
    const details = (Array.isArray(product.details) ? product.details : [])
      .filter(item => item && item.value)
      .map(item => `<strong>${escapeHtml(item.label)}:</strong> ${escapeHtml(item.value)}`)
      .join('<br>');

    document.getElementById('tab-details').innerHTML = details || 'No additional details available.';
  }

  function renderProduct(product) {
    currentProduct = product;
    document.title = 'Nivis Labs';

    setText('breadcrumbProduct', product.name || 'Product');
    setText('productTitle', product.name || 'Product');
    setText('productSubtitle', product.subtitle || product.concern ? `/${product.subtitle || product.concern}/` : '');
    setText('productStars', product.stars || '★★★★½');
    setText('productReviews', `${product.reviewsCount || 120} reviews`);
    setText('productPrice', priceLabel(product.priceNumber || product.price));
    setText('productDesc', product.description || product.subtitle || '');
    setText('boughtNote', product.boughtTag || '');

    const typeBadge = document.getElementById('typeBadge');
    if (product.type || product.category) {
      typeBadge.style.display = '';
      typeBadge.textContent = product.type || product.category;
    } else {
      typeBadge.style.display = 'none';
    }

    document.getElementById('tab-desc').textContent = product.whatIs || product.description || product.subtitle || '';
    document.getElementById('tab-how').textContent = product.howToUse || 'Use as directed on the product label.';

    renderGallery(product);
    renderBenefits(product);
    renderDetails(product);
  }

  function addCurrentProductToCart() {
    if (!currentProduct || !window.NivisCart) return;

    const cartItem = {
      id: productId(currentProduct),
      name: currentProduct.name || 'Product',
      price: currentProduct.priceNumber || priceNumber(currentProduct.price),
      image: (currentProduct.images && currentProduct.images[0]) || currentProduct.imageUrl || './assets/img/product.webp',
      quantity: 1
    };

    if (typeof window.addProductToCart === 'function') {
      window.addProductToCart(cartItem);
      return;
    }

    window.NivisCart.add(cartItem, 1);

    const drawer = document.getElementById('cartDrawer');
    if (drawer && window.bootstrap) {
      bootstrap.Offcanvas.getOrCreateInstance(drawer).show();
    }
  }

  async function loadProductData() {
    const productKey = getProductKey();

    try {
      const response = await fetch(new URL(`fetch_product_detail.php?product=${encodeURIComponent(productKey)}`, window.location.href).href);
      const result = await response.json();

      if (result.error || !result.product) {
        document.getElementById('productTitle').textContent = 'Product not found';
        document.getElementById('productDesc').textContent = 'Please go back to products and choose another item.';
        return;
      }

      renderProduct(result.product);
      loadRelatedProducts(result.product.category || result.product.type || 'products');
    } catch (error) {
      console.error('Error loading product data:', error);
      document.getElementById('productTitle').textContent = 'Unable to load product';
      document.getElementById('productDesc').textContent = 'Please refresh the page or try again later.';
    }
  }

  async function loadRelatedProducts(categoryName) {
    try {
      const response = await fetch(new URL(`fetch_category_products.php?category=${encodeURIComponent(categoryName)}`, window.location.href).href);
      const result = await response.json();
      const products = (result.data?.products || result.products || [])
        .filter(product => productId(product) !== productId(currentProduct))
        .slice(0, 4);

      renderRelatedProducts(products);
    } catch (error) {
      console.error('Error loading related products:', error);
    }
  }

  function renderRelatedProducts(products) {
    const section = document.getElementById('relatedProductsSection');
    if (!section || !products.length) {
      if (section) section.innerHTML = '';
      return;
    }

    section.innerHTML = `
      <h3 class="mb-4" style="font-size:20px;font-weight:700;">Related Products</h3>
      <div class="row">
        ${products.map(product => {
          const image = (product.images && product.images[0]) || product.imageUrl || './assets/img/product.webp';
          const key = product.id || product.sku || product.urlKey || product.url_key || product.name || '';
          const priceValue = product.priceNumber || priceNumber(product.price);

          return `
            <div class="col-6 col-md-4 col-lg-3 mb-4">
              <div class="product-card"
                data-product-id="${escapeHtml(key)}"
                data-product-name="${escapeHtml(product.name || 'Product')}"
                data-product-price="${escapeHtml(priceValue)}"
                data-product-image="${escapeHtml(image)}"
                data-price="${escapeHtml(priceValue)}"
                style="border:1px solid #eee;border-radius:8px;padding:12px;text-align:center;height:100%;">
                <a href="product-detail.php?product=${encodeURIComponent(key)}" style="text-decoration:none;color:inherit;">
                  <img src="${escapeHtml(image)}" alt="${escapeHtml(product.name || 'Product')}" style="width:100%;height:180px;object-fit:contain;border-radius:6px;margin-bottom:10px;">
                  <div class="product-name" style="font-weight:600;font-size:13px;color:#333;margin-bottom:6px;">${escapeHtml(product.name || 'Product')}</div>
                  <div style="color:#999;font-size:11px;margin-bottom:8px;">${escapeHtml(product.type || product.category || 'Product')}</div>
                  <div class="product-price" style="color:#1a73e8;font-weight:600;font-size:14px;">${escapeHtml(priceLabel(product.priceNumber || product.price))}</div>
                </a>
                <button class="btn-cart mt-3" type="button">Add to Cart</button>
              </div>
            </div>
          `;
        }).join('')}
      </div>
    `;
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('arrowLeft')?.addEventListener('click', () => setImage(currentImageIndex - 1));
    document.getElementById('arrowRight')?.addEventListener('click', () => setImage(currentImageIndex + 1));
    document.getElementById('addToCartBtn')?.addEventListener('click', addCurrentProductToCart);

    document.querySelectorAll('.info-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.info-tab').forEach(item => item.classList.remove('active'));
        tab.classList.add('active');

        ['desc', 'how', 'details'].forEach(name => {
          document.getElementById(`tab-${name}`).style.display = tab.dataset.tab === name ? '' : 'none';
        });
      });
    });

    loadProductData();
  });
</script>

<?php include 'footer.php'; ?>
