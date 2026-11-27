<?php include 'navbar.php'; ?>

<div class="breadcrumb-bar product-detail-breadcrumb">
  <a href="index.php">Home</a> /
  <a href="products.php">Products</a> /
  <a id="breadcrumbProduct" href="#">Product</a>
</div>

<style>
  .product-detail-page .benefit-icon {
    font-size: 18px;
  }

  .product-detail-page .info-tab {
    background: #075563;
    /* background: #3d33335e; */
    border: 0;
    border-bottom: 2px solid transparent;
  }

  .product-detail-page .info-tab.active {
    border-bottom-color: #e2b373;
  }

  .product-detail-page .arrow-zone.is-hidden {
    display: none;
  }

  .product-detail-page .main-img-wrap {
    min-height: 300px;
  }

  .product-detail-page .image-status {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    color: #06243a;
  }

  .product-detail-page .image-status[hidden] {
    display: none;
  }

  /* ---- Rating summary (score, stars, count) with hover breakdown ---- */
  .product-detail-page .pd-rating {
    position: relative;
    margin: 4px 0 16px;
  }

  .product-detail-page .pd-rating[hidden] {
    display: none;
  }

  .product-detail-page .pd-rating__trigger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 2px 0;
    border: 0;
    background: none;
    color: #257aa0;
    font-weight: 700;
    font-family: Arial, sans-serif;
    font-size: 16px;
    cursor: pointer;
  }

  .product-detail-page .pd-rating__trigger:focus-visible {
    outline: 2px solid #e2b373;
    outline-offset: 3px;
    border-radius: 4px;
  }

  .product-detail-page .pd-rating__chevron {
    font-size: 12px;
    transition: transform .2s ease;
  }

  .product-detail-page .pd-rating__trigger[aria-expanded="true"] .pd-rating__chevron {
    transform: rotate(180deg);
  }

  .product-detail-page .pd-rating__count {
    color: #257aa0;
  }

  .product-detail-page .pd-rating__trigger:hover .pd-rating__count {
    text-decoration: underline;
  }

  /* Five stars: an outlined base with a solid layer clipped to the rating. */
  .pd-stars {
    position: relative;
    display: inline-block;
    font-size: 20px;
    line-height: 1;
    letter-spacing: 1px;
    color: transparent;
    -webkit-text-stroke: 1.2px #ff6a00;
    white-space: nowrap;
  }

  .pd-stars::before {
    content: "★★★★★";
  }

  .pd-stars::after {
    content: "★★★★★";
    position: absolute;
    inset: 0 auto 0 0;
    width: var(--fill, 0%);
    overflow: hidden;
    color: #ff6a00;
  }

  .pd-stars--lg {
    font-size: 24px;
  }

  .pd-rating__popover {
    position: fixed;
    z-index: 1060;
    width: min(340px, calc(100vw - 24px));
    padding: 20px 20px 14px;
    border: 1px solid #d5d9d9;
    border-radius: 10px;
    background: #fff;
    color: #0f1111;
    font-family: Arial, sans-serif;
    box-shadow: 0 8px 28px rgba(15, 17, 17, .22);
  }

  .pd-rating__popover[hidden] {
    display: none;
  }

  .pd-rating__close {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border: 1px solid #888c8c;
    border-radius: 6px;
    background: #fff;
    font-size: 20px;
    line-height: 1;
    color: #0f1111;
    cursor: pointer;
  }

  .pd-rating__head {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-right: 34px;
  }

  .pd-rating__head strong {
    font-size: 18px;
  }

  .pd-rating__total {
    margin: 8px 0 14px;
    color: #565959;
    font-size: 14px;
  }

  .pd-rating__row {
    display: grid;
    grid-template-columns: 52px 1fr 42px;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    color: #2162a1;
    font-size: 14px;
  }

  .pd-rating__row span:last-child {
    text-align: right;
  }

  .pd-rating__bar {
    height: 20px;
    border: 1px solid #888c8c;
    border-radius: 5px;
    background: #fff;
    overflow: hidden;
  }

  .pd-rating__bar i {
    display: block;
    height: 100%;
    background: #ff6a00;
  }

  @media (min-width: 768px) {
    .product-detail-page {
      min-height: 0;
    }

    .product-detail-page .thumb-col {
      flex-shrink: 0;
      min-height: 0;
    }

    .product-detail-page .main-img-wrap {
      min-width: 0;
      min-height: 0;
      height: 100%;
      padding: 12px;
      box-sizing: border-box;
    }

    .product-detail-page .main-img-wrap img.main-img {
      width: 100%;
      height: 100%;
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      object-position: center;
    }
  }

  @media (min-width: 901px) {
    .product-detail-page .product_img_section,
    .product-detail-page .right_side_product {
      height: clamp(360px, 45vw, 560px);
    }

    .product-detail-page .right_side_product {
      min-width: 0;
      padding: 24px;
    }
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

        <span class="image-status" id="imageStatus" role="status">Loading image...</span>
        <img class="main-img" id="mainImg" style="visibility: hidden" alt="Product" />

        <div class="arrow-zone right" id="arrowRight">
          <div class="arrow-btn"><i class="fa fa-chevron-right"></i></div>
        </div>
      </div>
    </div>

    <div class="right_side_product">
      <!-- <div class="badge-stamp" id="productBadge">NIVIS<br>LABS<br>*</div> -->
      <div class="product-brand">Nivis Labs International</div>
      <h1 class="product-title" id="productTitle">Loading product...</h1>
      <div class="product-subtitle" id="productSubtitle"></div>

      <div class="pd-rating" id="productRating" hidden>
        <button type="button" class="pd-rating__trigger" id="productRatingTrigger" aria-expanded="false" aria-controls="productRatingPopover">
          <span class="pd-rating__value" id="productRatingValue"></span>
          <span class="pd-stars" id="productRatingStars" aria-hidden="true"></span>
          <i class="fa fa-chevron-down pd-rating__chevron" aria-hidden="true"></i>
          <span class="pd-rating__count" id="productRatingCount"></span>
        </button>

        <div class="pd-rating__popover" id="productRatingPopover" role="dialog" aria-label="Customer ratings" hidden>
          <button type="button" class="pd-rating__close" id="productRatingClose" aria-label="Close ratings">&times;</button>
          <div class="pd-rating__head">
            <span class="pd-stars pd-stars--lg" id="productRatingPopStars" aria-hidden="true"></span>
            <strong id="productRatingOutOf"></strong>
          </div>
          <div class="pd-rating__total" id="productRatingTotal"></div>
          <div class="pd-rating__bars" id="productRatingBars"></div>
        </div>
      </div>

      <div class="spf-badge" id="typeBadge" style="display:none"></div>
      <div class="product-desc" id="productDesc"></div>

      <div class="price-row">
        <span class="price-mrp-label">Price:</span>
        <span class="price-main" id="productPrice">Rs. 0</span>
      </div>

      <button class="btn-buy" id="addToCartBtn" type="button">ADD TO CART</button>
      <!-- <div class="bought-note" id="boughtNote"></div> -->

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

<section class="container my-5 product-detail-related" id="relatedProductsSection"></section>

<script>
  let currentProduct = null;
  let galleryImages = [];
  let currentImageIndex = 0;
  let galleryTimer = null;

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
    return String(product.sku || product.id || product.urlKey || product.url_key || product.name || 'product')
      .trim()
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '');
  }

  function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value || '';
  }

  // Stable hash, so each product always gets the same rating.
  function hashString(value) {
    let hash = 2166136261;
    for (const char of String(value)) {
      hash ^= char.charCodeAt(0);
      hash = Math.imul(hash, 16777619);
    }
    return hash >>> 0;
  }

  // Rating between 4.5 and 4.9 (different per product) plus a star breakdown
  // in whole percentages that sums to 100 and averages exactly to the rating.
  function buildProductRating(product) {
    const key = product.urlKey || product.url_key || product.sku || product.id || product.name || 'product';
    const hash = hashString(key);
    const average = 4.5 + (hash % 5) / 10;
    const total = 150 + (Math.floor(hash / 5) % 800);

    const spread = (5 - average) / 0.5;
    const one = Math.round(4 * spread);
    const two = Math.round(2 * spread);
    const three = Math.round(5 * spread);
    const rest = 100 - one - two - three;
    const target = Math.round(average * 100) - (one + 2 * two + 3 * three);
    const five = target - 4 * rest;
    const four = rest - five;

    return {
      average,
      total,
      percents: { 5: five, 4: four, 3: three, 2: two, 1: one }
    };
  }

  function compactCount(value) {
    if (value >= 1000) {
      return (Math.round(value / 100) / 10).toLocaleString('en-IN') + 'K';
    }
    return String(value);
  }

  function setStarFill(el, average) {
    if (el) el.style.setProperty('--fill', `${(average / 5) * 100}%`);
  }

  function setRatingStars(product) {
    const rating = buildProductRating(product);
    const label = rating.average.toFixed(1);

    setText('productRatingValue', label);
    setText('productRatingCount', `(${compactCount(rating.total)})`);
    setText('productRatingOutOf', `${label} out of 5`);
    setText('productRatingTotal', `${rating.total.toLocaleString('en-IN')} global ratings`);
    setStarFill(document.getElementById('productRatingStars'), rating.average);
    setStarFill(document.getElementById('productRatingPopStars'), rating.average);

    document.getElementById('productRatingTrigger')
      ?.setAttribute('aria-label', `Rated ${label} out of 5 from ${rating.total.toLocaleString('en-IN')} ratings. Show rating breakdown`);

    document.getElementById('productRatingBars').innerHTML = [5, 4, 3, 2, 1].map(star => `
      <div class="pd-rating__row">
        <span>${star} star</span>
        <span class="pd-rating__bar" role="img" aria-label="${rating.percents[star]}% of ratings are ${star} star"><i style="width:${rating.percents[star]}%"></i></span>
        <span>${rating.percents[star]}%</span>
      </div>
    `).join('');

    document.getElementById('productRating').hidden = false;
  }

  function initRatingPopover() {
    const wrap = document.getElementById('productRating');
    const trigger = document.getElementById('productRatingTrigger');
    const popover = document.getElementById('productRatingPopover');
    const closeBtn = document.getElementById('productRatingClose');
    if (!wrap || !trigger || !popover) return;

    let hideTimer = null;
    const canHover = window.matchMedia('(hover: hover)').matches;

    function place() {
      const rect = trigger.getBoundingClientRect();
      const width = popover.offsetWidth;
      const left = Math.max(12, Math.min(rect.left, window.innerWidth - width - 12));
      let top = rect.bottom + 8;
      if (top + popover.offsetHeight > window.innerHeight - 12) {
        top = Math.max(12, rect.top - popover.offsetHeight - 8);
      }
      popover.style.left = `${left}px`;
      popover.style.top = `${top}px`;
    }

    function open() {
      clearTimeout(hideTimer);
      popover.hidden = false;
      trigger.setAttribute('aria-expanded', 'true');
      place();
    }

    function close() {
      clearTimeout(hideTimer);
      popover.hidden = true;
      trigger.setAttribute('aria-expanded', 'false');
    }

    function closeSoon() {
      clearTimeout(hideTimer);
      hideTimer = setTimeout(close, 200);
    }

    if (canHover) {
      [trigger, popover].forEach(el => {
        el.addEventListener('mouseenter', open);
        el.addEventListener('mouseleave', closeSoon);
      });
    }

    trigger.addEventListener('click', () => (popover.hidden ? open() : close()));
    closeBtn?.addEventListener('click', () => {
      close();
      trigger.focus();
    });

    document.addEventListener('click', event => {
      if (!popover.hidden && !wrap.contains(event.target)) close();
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && !popover.hidden) {
        close();
        trigger.focus();
      }
    });
    window.addEventListener('resize', () => !popover.hidden && place());
    document.addEventListener('scroll', () => !popover.hidden && place(), true);
  }

  function setImage(index) {
    if (!galleryImages.length) return;
    currentImageIndex = (index + galleryImages.length) % galleryImages.length;
    const image = galleryImages[currentImageIndex];
    const mainImg = document.getElementById('mainImg');
    const imageStatus = document.getElementById('imageStatus');
    mainImg.style.visibility = 'hidden';
    imageStatus.textContent = 'Loading image...';
    imageStatus.hidden = false;
    mainImg.onload = () => {
      mainImg.style.visibility = 'visible';
      imageStatus.hidden = true;
    };
    mainImg.onerror = () => {
      mainImg.style.visibility = 'hidden';
      imageStatus.textContent = 'Image unavailable';
      imageStatus.hidden = false;
    };
    mainImg.src = image;
    mainImg.alt = currentProduct?.name || 'Product';

    document.querySelectorAll('#thumbCol img').forEach((thumb, thumbIndex) => {
      thumb.classList.toggle('active', thumbIndex === currentImageIndex);
    });
  }

  function stopGalleryAutoSlide() {
    clearInterval(galleryTimer);
    galleryTimer = null;
  }

  // True while the mouse cursor is over the image area: auto-slide stays paused.
  let galleryHovered = false;

  function isCursorOnGallery() {
    const section = document.getElementById('imgSection');
    return galleryHovered ||
      (!!section && window.matchMedia('(hover: hover)').matches && section.matches(':hover'));
  }

  function startGalleryAutoSlide() {
    stopGalleryAutoSlide();
    if (isCursorOnGallery()) return;
    if (galleryImages.length > 1) {
      galleryTimer = setInterval(() => setImage(currentImageIndex + 1), 3000);
    }
  }

  function goToGalleryImage(index) {
    setImage(index);
    startGalleryAutoSlide();
  }

  function pauseGalleryOnFocus() {
    stopGalleryAutoSlide();
  }

  function isPlaceholderImage(image) {
    return String(image || '').toLowerCase().includes('/assets/img/product.webp') ||
      String(image || '').toLowerCase().endsWith('assets/img/product.webp');
  }

  function renderGallery(product) {
    galleryImages = [
      ...(Array.isArray(product.images) ? product.images : []),
      product.imageUrl,
      product.primaryImage,
      product.secondaryImageUrl
    ].filter(Boolean).filter((image, index, list) => list.indexOf(image) === index);

    const realImages = galleryImages.filter(image => !isPlaceholderImage(image));
    if (realImages.length) {
      galleryImages = realImages;
    }

    if (!galleryImages.length) {
      galleryImages = ['./assets/img/product.webp'];
    }

    const thumbCol = document.getElementById('thumbCol');
    thumbCol.innerHTML = galleryImages.map((image, index) => `
      <img class="thumb-item${index === 0 ? ' active' : ''}" src="${escapeHtml(image)}" alt="${escapeHtml(product.name || 'Product')} image ${index + 1}" onerror="this.onerror=null;this.src='./assets/img/product.webp';" />
    `).join('');

    thumbCol.querySelectorAll('img').forEach((thumb, index) => {
      thumb.addEventListener('click', () => goToGalleryImage(index));
    });

    document.getElementById('arrowLeft')?.classList.toggle('is-hidden', galleryImages.length < 2);
    document.getElementById('arrowRight')?.classList.toggle('is-hidden', galleryImages.length < 2);

    setImage(0);
    startGalleryAutoSlide();
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
        
        <div class="benefit-icon" >✦</div>
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
    document.title = 'Nivis Labs ';

    setText('breadcrumbProduct', product.name || 'Product');
    setText('productTitle', product.name || 'Product');
    const description = product.whatIs || product.description || product.subtitle || '';
    const subtitle = product.subtitle || product.description || product.concern || '';
    setText('productSubtitle', subtitle);
    document.getElementById('productSubtitle').style.display = subtitle ? '' : 'none';
    setRatingStars(product);

    document.getElementById('productPrice').innerHTML = window.NivisPricing.html(product.priceNumber || product.price);
    // Show the summary once beneath the product name, without a second copy here.
    setText('productDesc', '');
    setText('boughtNote', product.boughtTag || '');

    const typeBadge = document.getElementById('typeBadge');
    if (product.type || product.category) {
      typeBadge.style.display = '';
      typeBadge.textContent = product.type || product.category;
    } else {
      typeBadge.style.display = 'none';
    }

    document.getElementById('tab-desc').textContent = description;
    document.getElementById('tab-how').textContent = product.howToUse || 'Use as directed on the product label.';

    renderGallery(product);
    renderBenefits(product);
    renderDetails(product);
  }

  function addCurrentProductToCart() {
    if (!currentProduct || !window.NivisCart) return;

    const cartItem = {
      id: productId(currentProduct),
      sku: currentProduct.sku || currentProduct.productCode || currentProduct.id || '',
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
        document.getElementById('imageStatus').textContent = 'Image unavailable';
        document.getElementById('productTitle').textContent = 'Product not found';
        document.getElementById('productDesc').textContent = 'Please go back to products and choose another item.';
        return;
      }

      renderProduct(result.product);
      loadRelatedProducts(result.product.category || result.product.type || 'products');
    } catch (error) {
      console.error('Error loading product data:', error);
      document.getElementById('imageStatus').textContent = 'Image unavailable';
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
          const key = product.urlKey || product.url_key || product.sku || product.id || product.name || '';
          const priceValue = product.priceNumber || priceNumber(product.price);

          return `
            <div class="col-6 col-md-4 col-lg-3 mb-4">
              <div class="product-card product-detail-related__card"
                data-product-id="${escapeHtml(key)}"
                data-product-name="${escapeHtml(product.name || 'Product')}"
                data-product-price="${escapeHtml(priceValue)}"
                data-product-image="${escapeHtml(image)}"
                data-price="${escapeHtml(priceValue)}">
                <a href="product-detail.php?product=${encodeURIComponent(key)}" class="product-detail-related__link">
                  <img class="product-detail-related__image" src="${escapeHtml(image)}" alt="${escapeHtml(product.name || 'Product')}">
                  <div class="product-name product-detail-related__name">${escapeHtml(product.name || 'Product')}</div>
                  <div class="product-detail-related__meta">${escapeHtml(product.type || product.category || 'Product')}</div>
                  <div class="product-price product-detail-related__price">${window.NivisPricing.html(priceValue)}</div>
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
    document.getElementById('arrowLeft')?.addEventListener('click', () => goToGalleryImage(currentImageIndex - 1));
    document.getElementById('arrowRight')?.addEventListener('click', () => goToGalleryImage(currentImageIndex + 1));
    document.getElementById('imgSection')?.addEventListener('pointerenter', event => {
      if (event.pointerType !== 'mouse') return;
      galleryHovered = true;
      stopGalleryAutoSlide();
    });
    document.getElementById('imgSection')?.addEventListener('pointerleave', event => {
      if (event.pointerType !== 'mouse') return;
      galleryHovered = false;
      startGalleryAutoSlide();
    });
    document.getElementById('imgSection')?.addEventListener('focusin', pauseGalleryOnFocus);
    document.getElementById('imgSection')?.addEventListener('focusout', event => {
      if (!event.currentTarget.contains(event.relatedTarget)) {
        startGalleryAutoSlide();
      }
    });
    document.getElementById('addToCartBtn')?.addEventListener('click', addCurrentProductToCart);
    initRatingPopover();

    document.querySelectorAll('.info-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.info-tab').forEach(item => item.classList.remove('active'));
        tab.classList.add('active');

        ['desc', 'how', 'details'].forEach(name => {
          document.getElementById(`tab-${name}`).style.display = tab.dataset.tab === name ? '' : 'none';
        });

        const activePanel = document.getElementById(`tab-${tab.dataset.tab}`);
        const productPanel = document.querySelector('.product-detail-page .right_side_product');
        if (activePanel && productPanel) {
          const overflow = activePanel.getBoundingClientRect().bottom - productPanel.getBoundingClientRect().bottom;
          if (overflow > 0) productPanel.scrollTop += overflow;
        }
      });
    });

    loadProductData();
  });
</script>

<?php include 'footer.php'; ?>
