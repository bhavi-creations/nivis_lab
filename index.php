<?php include 'navbar.php'; ?>

<!-- ╕
     3. HERO IMAGE SECTION
╕ -->
<section class="index_img_section" id="indexHero" aria-label="Nivis Labs featured products">
    <div class="index_img_section__bg"></div>
    <div class="index_img_section__stripes"></div>
    <div class="index_img_section__glow"></div>

    <svg class="index_img_section__rays" viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <line class="ray ray-1" x1="820" y1="380" x2="1440" y2="60" />
        <line class="ray ray-2" x1="820" y1="380" x2="1440" y2="200" />
        <line class="ray ray-3" x1="820" y1="380" x2="1440" y2="720" />
        <line class="ray ray-4" x1="820" y1="380" x2="200" y2="800" />
        <line class="ray ray-1" x1="820" y1="380" x2="100" y2="50" />
        <line class="ray ray-3" x1="820" y1="380" x2="600" y2="800" />
    </svg>

    <div class="index_img_section__slides" id="indexHeroSlides">
        <a class="index_img_section__slide active" href="products.php" style="--hero-img: url('./assets/img/2.png');">
            <img class="index_img_section__product" src="./assets/img/2.png" alt="Nivis Labs slide 1" onerror="this.src='./assets/img/2.png';" />
            <!-- <div class="index_img_section__content">
                <span class="index_img_section__new-tag">New Launch</span>
                <h1 class="index_img_section__title">Panthenol<br />Hydrating Gel<br />Sunscreen</h1>
                <div class="index_img_section__badges">
                    <span class="index_img_section__badge">SPF 60 PA++++</span>
                    <span class="index_img_section__badge">No Whitecast</span>
                    <span class="index_img_section__badge">Shop Now</span>
                </div>
            </div> -->
            <div class="index_img_section__cta"><span>Read More</span><i class="bi bi-arrow-right-circle"></i></div>
        </a>

        <a class="index_img_section__slide" href="products.php" style="--hero-img: url('./assets/img/3.png');">
            <img class="index_img_section__product" src="./assets/img/3.png" alt="Nivis Labs slide 2" onerror="this.src='./assets/img/3.png';" />
            <!-- <div class="index_img_section__content">
                <span class="index_img_section__new-tag">Daily Protection</span>
                <h1 class="index_img_section__title">Sun Care<br />That Feels<br />Light</h1>
                <div class="index_img_section__badges">
                    <span class="index_img_section__badge">Broad Spectrum</span>
                    <span class="index_img_section__badge">Everyday Routine</span>
                    <span class="index_img_section__badge">View Sunscreens</span>
                </div>
            </div> -->
            <div class="index_img_section__cta"><span>Read More</span><i class="bi bi-arrow-right-circle"></i></div>
        </a>

        <a class="index_img_section__slide" href="products.php" style="--hero-img: url('./assets/img/4.png');">
            <img class="index_img_section__product" src="./assets/img/4.png" alt="Nivis Labs slide 3" onerror="this.src='./assets/img/4.png';" />
            <!-- <div class="index_img_section__content">
                <span class="index_img_section__new-tag">Barrier Care</span>
                <h1 class="index_img_section__title">Comforting<br />Moisture<br />Support</h1>
                <div class="index_img_section__badges">
                    <span class="index_img_section__badge">Hydration</span>
                    <span class="index_img_section__badge">Skin Barrier</span>
                    <span class="index_img_section__badge">View Moisturizers</span>
                </div>
            </div> -->
            <div class="index_img_section__cta"><span>Read More</span><i class="bi bi-arrow-right-circle"></i></div>
        </a>

        <a class="index_img_section__slide" href="products.php" style="--hero-img: url('./assets/img/5.png');">
            <img class="index_img_section__product" src="./assets/img/5.png" alt="Nivis Labs slide 4" onerror="this.src='./assets/img/5.png';" />
            <div class="index_img_section__cta"><span>Read More</span><i class="bi bi-arrow-right-circle"></i></div>
        </a>

    </div>

</section>

<script>
    (function() {
        const hero = document.getElementById('indexHero');
        const slidesWrap = document.getElementById('indexHeroSlides');
        const fallbackImage = './assets/img/product.webp';
        let slides = [];
        let current = 0;
        let timer = null;

        if (!hero || !slidesWrap) return;

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, match => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [match]));
        }

        function heroTitle(title) {
            const words = String(title || 'Nivis Labs Product').trim().split(/\s+/);
            const rows = [
                words.slice(0, 2).join(' '),
                words.slice(2, 4).join(' '),
                words.slice(4).join(' ')
            ].filter(Boolean);
            return rows.map(escapeHtml).join('<br />');
        }

        function productImage(product) {
            if (Array.isArray(product.images) && product.images.length > 0) return product.images[0];
            return product.imageUrl || product.thumbnail || '';
        }

        function normalizeHeroImage(image) {
            const imagePath = String(image || '').trim();
            if (!imagePath) return fallbackImage;
            if (/^(https?:)?\/\//i.test(imagePath) || imagePath.startsWith('/') || imagePath.startsWith('./')) return imagePath;

            return imagePath;
        }

        function hasUploadedImage(image) {
            const imagePath = normalizeHeroImage(image);
            return imagePath && !imagePath.includes('/assets/img/product.webp');
        }

        function isHeroBannerImage(image) {
            return new Promise(resolve => {
                if (!hasUploadedImage(image)) {
                    resolve(false);
                    return;
                }

                const probe = new Image();
                probe.onload = () => {
                    const ratio = probe.naturalWidth / Math.max(1, probe.naturalHeight);
                    resolve(ratio >= 2.4);
                };
                probe.onerror = () => resolve(false);
                probe.src = image;
            });
        }

        function productHref(product) {
            const key = product.urlKey || product.url_key || product.sku || product.id || product.name || '';
            return key ? `product-detail.php?product=${encodeURIComponent(key)}` : 'products.php';
        }

        function slideTemplate(slide) {
            const badges = (slide.badges || []).filter(Boolean).slice(0, 3).map(badge => (
                `<span class="index_img_section__badge">${escapeHtml(badge)}</span>`
            )).join('');

            return `
                <a class="index_img_section__slide" href="${escapeHtml(slide.href)}" style="--hero-img: url('${escapeHtml(slide.image)}');">
                    <img class="index_img_section__product" src="${escapeHtml(slide.image)}" alt="${escapeHtml(slide.title)}" onerror="this.src='${fallbackImage}';" />
                    <div class="index_img_section__content">
                        <span class="index_img_section__new-tag">${escapeHtml(slide.tag || 'Nivis Labs')}</span>
                        <h1 class="index_img_section__title">${heroTitle(slide.title)}</h1>
                        <div class="index_img_section__badges">${badges}</div>
                    </div>
                    <div class="index_img_section__cta"><span>Read More</span><i class="bi bi-arrow-right-circle"></i></div>
                </a>
            `;
        }

        function setSlide(index) {
            if (!slides.length) return;
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => slide.classList.toggle('active', slideIndex === current));
        }

        function startSlider() {
            clearInterval(timer);
            if (slides.length > 1) {
                timer = setInterval(() => setSlide(current + 1), 3500);
            }
        }

        function initSlides() {
            slides = Array.from(slidesWrap.querySelectorAll('.index_img_section__slide'));
            setSlide(0);
            startSlider();
        }

        async function loadBackendSlides() {
            try {
                const response = await fetch('fetch_category_products.php?category=all');
                const result = await response.json();
                const products = result.data?.products || [];
                const candidateSlides = products
                    .map(product => ({
                        title: product.name || 'Nivis Labs Product',
                        image: normalizeHeroImage(productImage(product)),
                        href: productHref(product),
                        tag: product.type || product.category || 'Featured Product',
                        badges: [product.size, product.subtitle || product.concern || product.category, product.price || 'Shop Now']
                    }))
                    .filter(slide => hasUploadedImage(slide.image))
                    .slice(0, 4);

                const bannerChecks = await Promise.all(candidateSlides.map(slide => isHeroBannerImage(slide.image)));
                const backendSlides = candidateSlides.filter((_, index) => bannerChecks[index]);

                if (backendSlides.length < 4) return;
                slidesWrap.innerHTML = backendSlides.map(slideTemplate).join('');
                initSlides();
            } catch (error) {
                console.error('Unable to load hero products:', error);
            }
        }

        initSlides();
        loadBackendSlides();
    })();
</script>

<!-- our spot light  -->



<!-- <div class="container">
    <div class="col-3">
        <div class="card">

        </div>
    </div>
    <div class="col-3"></div>
    <div class="col-3"></div>
    <div class="col-3"></div>
</div> -->


<!-- <div class="container my-5 mb-5">

    <div class="skin-wrapper_index">

      
        <div class="skin-label_index">SKIN ASSESSMENT</div>

       
        <div class="skin-section_index">

            <div class="row align-items-center">

                
                <div class="col-md-7">
                    <p class="text-danger fw-semibold mb-1">
                        AI-POWERED SKIN ANALYSIS
                    </p>

                    <h2 class="skin-title_index">
                        GET CURATED HELP FOR YOUR SKIN
                    </h2>

                    <p class="text-muted">
                        Answer 3 quick questions — our AI dermat advisor will build a personalised routine just for
                        you.
                    </p>

                    <button class="skin-btn_index">
                        START MY SKIN ASSESSMENT →
                    </button>
                </div>

               
                <div class="col-md-5">

                    <div class="feature-box_index">
                        <div class="feature-icon_index">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <strong>Know your skin</strong><br>
                            <small>Identify your skin type in seconds</small>
                        </div>
                    </div>

                    <div class="feature-box_index">
                        <div class="feature-icon_index">
                            <i class="fa fa-heartbeat"></i>
                        </div>
                        <div>
                            <strong>Target concerns</strong><br>
                            <small>Acne, pigmentation, aging & more</small>
                        </div>
                    </div>

                    <div class="feature-box_index">
                        <div class="feature-icon_index">
                            <i class="fa fa-image"></i>
                        </div>
                        <div>
                            <strong>Photo analysis</strong><br>
                            <small>AI analyses your skin for better results</small>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>



</div> -->

<!-- <div class="container my-5 ai_powered_skin_analysis">
    <div class="skin-wrapper_index border rounded-3 position-relative ">

       
        <div class="skin-label_index bg-danger text-white px-3 py-1 position-absolute top-0 start-0 translate-middle-y ms-4 fw-bold small">
            SKIN ASSESSMENT
        </div>

      
        <div id="step-landing" class="skin-section_index py-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p class="text-danger fw-semibold mb-1">NIVIS LABS SKIN CHECK</p>
                    <h2 class="skin-title_index fw-bold mb-3">BUILD A ROUTINE AROUND YOUR SKIN NEEDS</h2>
                    <p class="text-muted">Answer a few quick questions and discover Nivis Labs products aligned with your skin type and concerns.</p>
                    <button class="skin-btn_index btn btn-dark px-4 py-2" onclick="showStep(1)">
                        START MY ROUTINE CHECK →
                    </button>
                </div>
                <div class="col-md-5 mt-4 mt-md-0">
                    <div class="feature-box_index d-flex align-items-start mb-3">
                        <div class="feature-icon_index me-3"><i class="fa fa-user text-danger"></i></div>
                        <div><strong>Know your skin</strong><br><small class="text-muted">Understand your skin type and routine needs</small></div>
                    </div>
                    <div class="feature-box_index d-flex align-items-start mb-3">
                        <div class="feature-icon_index me-3"><i class="fa fa-heartbeat text-danger"></i></div>
                        <div><strong>Target concerns</strong><br><small class="text-muted">Acne, pigmentation, dryness, sun care and more</small></div>
                    </div>
                    <div class="feature-box_index d-flex align-items-start">
                        <div class="feature-icon_index me-3"><i class="fa fa-image text-danger"></i></div>
                        <div><strong>Product guidance</strong><br><small class="text-muted">Find Nivis Labs formulas that fit your routine</small></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div id="step-1" class="step-container d-none text-center py-4">
            <div class="progress mb-3 mx-auto" style="height: 4px; width: 200px;">
                <div class="progress-bar bg-danger" style="width: 33%"></div>
            </div>
            <p class="text-danger small fw-bold mb-1">STEP 1 OF 3</p>
            <h3 class="fw-bold">What's your skin type?</h3>
            <p class="text-muted small">Select all that apply</p>
            <div class="row g-2 justify-content-center my-4 px-lg-5">
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Oily</button></div>
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Dry</button></div>
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Combination</button></div>
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Sensitive</button></div>
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Normal</button></div>
                <div class="col-6 col-md-4"><button class="btn btn-outline-secondary w-100 py-3 option-btn" onclick="toggleSelection(this, 1)">Not sure</button></div>
            </div>
            <button id="next-1" class="btn btn-secondary w-100 py-3 fw-bold disabled" onclick="showStep(2)">NEXT →</button>
        </div>

        
        <div id="step-2" class="step-container d-none text-center py-4">
            <div class="progress mb-3 mx-auto" style="height: 4px; width: 200px;">
                <div class="progress-bar bg-danger" style="width: 66%"></div>
            </div>
            <p class="text-danger small fw-bold mb-1">STEP 2 OF 3</p>
            <h3 class="fw-bold">What are your skin concerns?</h3>
            <p class="text-muted small">Select up to 3</p>
            <div class="row g-2 justify-content-center my-4 px-lg-5">
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Acne & breakouts</button></div>
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Pigmentation</button></div>
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Fine lines & aging</button></div>
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Dark circles</button></div>
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Open pores</button></div>
                <div class="col-6 col-md-6"><button class="btn btn-outline-secondary w-100 py-2 option-btn" onclick="toggleSelection(this, 2)">Sun damage</button></div>
            </div>
            <button id="next-2" class="btn btn-secondary w-100 py-3 fw-bold disabled" onclick="showStep(3)">NEXT →</button>
        </div>

        
        <div id="step-3" class="step-container d-none text-center py-4">
            <div class="progress mb-3 mx-auto" style="height: 4px; width: 200px;">
                <div class="progress-bar bg-danger" style="width: 100%"></div>
            </div>
            <p class="text-danger small fw-bold mb-1">STEP 3 OF 3</p>
            <h3 class="fw-bold">Share your preference</h3>
            <p class="text-muted small">Add a photo if you want more context for your routine selection.</p>

            <div class="upload-box border border-danger border-dashed rounded-3 p-5 my-4 mx-auto" style="max-width: 400px; border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                <i class="fa fa-image fs-1 text-danger mb-2"></i>
                <p class="mb-0 fw-bold">Tap to upload a skin photo</p>
                <small class="text-muted">JPG, PNG under 4MB</small>
                <input type="file" id="fileInput" class="d-none" accept="image/*">
            </div>

            <button class="btn btn-danger w-100 py-3 fw-bold mb-2">GET MY NIVIS ROUTINE →</button>
            <a href="#" class="text-muted small text-decoration-underline">Skip photo & continue</a>
        </div>

    </div>
</div> -->


<!-- <div class="container my-5">
    <div class="count_section text-center">
        <div class="sale-badge mb-3">SALE ENDS IN</div>
        <div class="d-flex justify-content-center align-items-center gap-2">
            <div class="timer-box">
                <div class="time-num" id="hrs">09</div>
                <div class="time-label">HRS</div>
            </div>
            <span class="fs-3 fw-bold">:</span>
            <div class="timer-box">
                <div class="time-num" id="min">19</div>
                <div class="time-label">MIN</div>
            </div>
            <span class="fs-3 fw-bold">:</span>
            <div class="timer-box border-danger">
                <div class="time-num red-text" id="sec">53</div>
                <div class="time-label">SEC</div>
            </div>
        </div>
        <div class="mt-3 small"><span class="dot"></span> <strong>2743</strong> people viewing right now</div>
        <div class="progress-container mx-auto">
            <div class="progress-bar-custom"></div>
        </div>
    </div>
</div> -->











<!-- <div class="container mb-5">
    <div class="img_section text-center">
        <h2 class="fw-bold mb-4">SHOP BY CONCERN</h2>
        <div class="row g-2 justify-content-center">

            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="2">
                    <div class="concern-overlay">Acne </div>
                </div>
            </div>

            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="1">
                    <div class="concern-overlay">Pigmentation </div>
                </div>
            </div>
            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="3">
                    <div class="concern-overlay">Acne Marks </div>
                </div>
            </div>

            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="1">
                    <div class="concern-overlay">Dark Spot </div>
                </div>
            </div>
            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="1">
                    <div class="concern-overlay">Anti-Ageing </div>
                </div>
            </div>






            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="4">
                    <div class="concern-overlay">Dehydration</div>
                </div>
            </div>
            <div class="col-lg-2-custom">
                <div class="concern-card"><img src="./assets/img/logo.jpeg" alt="5">
                    <div class="concern-overlay">Eczema </div>
                </div>
            </div>

        </div>
    </div>
</div> -->


<div class="container py-5 img_section_container dermat-routine-section">
    <div class="img_section text-center">
        <h2 class="fw-bold mb-4" style="letter-spacing: 1px; color:black">Build Your Perfect Skin Routine</h2>


        <div class="d-flex flex-nowrap justify-content-start overflow-auto pb-3 no-scrollbar dermat-concern-row" id="dermatConcernRow">

            <div class="concern-item" onclick="showDermatRoutine('acne', this, 1)">
                <div class="concern-card">
                    <img src="./assets/img/acne.png" alt="Nivis Labs Acne">
                    <div class="concern-overlay">Acne</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('pigmentation', this, 2)">
                <div class="concern-card">
                    <img src="./assets/img/Pigmentation.png" alt="Nivis Labs Pigmentation">
                    <div class="concern-overlay">Pigmentation</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('acne-marks', this, 3)">
                <div class="concern-card">
                    <img src="./assets/img/Acne Marks.png" alt="Nivis Labs Acne Marks">
                    <div class="concern-overlay">Acne Marks</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('dark-spots', this, 4)">
                <div class="concern-card">
                    <img src="./assets/img/Dark Spots.png" alt="Nivis Labs Dark Spots">
                    <div class="concern-overlay">Dark Spots</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('anti-ageing', this, 5)">
                <div class="concern-card">
                    <img src="./assets/img/Anti-Aging.png" alt="Nivis Labs Anti-Aging">
                    <div class="concern-overlay">Anti-Aging</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('dehydration', this, 6)">
                <div class="concern-card">
                    <img src="./assets/img/Dehydration.png" alt="Nivis Labs Dehydration">
                    <div class="concern-overlay">Dehydration</div>
                </div>
            </div>

        </div>


        <div id="dermat-results" class="mt-4" style="display:none;">
            <!-- <p class="text-muted small mb-3" id="dermatResultHint">Choose a product to build your routine:</p> -->


            <div id="routine-content" class="mx-auto" style="max-width: 1100px;"></div>
        </div>
    </div>
</div>








<script>
    const dermatCategoryMap = {
        acne: 'acne',
        pigmentation: 'pigmentation',
        'acne-marks': 'acne-marks',
        'dark-spots': 'pigmentation',
        'anti-ageing': 'lines-and-wrinkles',
        dehydration: 'dehydration',
        'sun-protection': 'sunscreen'
    };

    let dermatSelectedIndex = 1;
    let dermatCurrentType = '';
    let dermatVisibleProducts = [];
    let dermatAllProducts = [];
    let dermatCurrentRoutine = [];

    function escapeDermatHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function(char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [char];
        });
    }

    function dermatProductKey(product) {
        return String(product.sku || product.id || product.urlKey || product.url_key || product.name || 'product')
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    }

    function dermatProductImage(product) {
        return (product.images && product.images.length > 0) ?
            product.images[0] :
            (product.imageUrl || product.primaryImage || './assets/img/product.webp');
    }

    function dermatFallbackProducts(type) {
        const samples = {
            acne: [{
                    name: 'Acne Control Treatment',
                    urlKey: 'acne',
                    category: 'Acne',
                    concern: 'Acne',
                    type: 'Treatment',
                    priceNumber: 500,
                    price: 'Rs. 500',
                    imageUrl: './assets/img/acne.png'
                },
                {
                    name: 'Acne Marks Care',
                    urlKey: 'acnemarks',
                    category: 'Acne',
                    concern: 'Acne Marks',
                    type: 'Treatment',
                    priceNumber: 520,
                    price: 'Rs. 520',
                    imageUrl: './assets/img/Acne Marks.png'
                }
            ],
            pigmentation: [{
                    name: 'Pigmentation Brightening Care',
                    urlKey: 'pigmentation',
                    category: 'Pigmentation',
                    concern: 'Pigmentation',
                    type: 'Treatment',
                    priceNumber: 520,
                    price: 'Rs. 520',
                    imageUrl: './assets/img/Pigmentation.png'
                },
                {
                    name: 'Dark Spots Correcting Care',
                    urlKey: 'dark-spots',
                    category: 'Pigmentation',
                    concern: 'Dark Spots',
                    type: 'Treatment',
                    priceNumber: 500,
                    price: 'Rs. 500',
                    imageUrl: './assets/img/Dark Spots.png'
                }
            ],
            'acne-marks': [{
                    name: 'Acne Marks Care',
                    urlKey: 'acnemarks',
                    category: 'Acne',
                    concern: 'Acne Marks',
                    type: 'Treatment',
                    priceNumber: 520,
                    price: 'Rs. 520',
                    imageUrl: './assets/img/Acne Marks.png'
                },
                {
                    name: 'Post Acne Spot Care',
                    urlKey: 'acne-marks',
                    category: 'Acne',
                    concern: 'Acne Marks',
                    type: 'Treatment',
                    priceNumber: 500,
                    price: 'Rs. 500',
                    imageUrl: './assets/img/acne.png'
                }
            ],
            'dark-spots': [{
                    name: 'Dark Spots Correcting Care',
                    urlKey: 'dark-spots',
                    category: 'Pigmentation',
                    concern: 'Dark Spots',
                    type: 'Treatment',
                    priceNumber: 500,
                    price: 'Rs. 500',
                    imageUrl: './assets/img/Dark Spots.png'
                },
                {
                    name: 'Brightening Support Care',
                    urlKey: 'pigmentation',
                    category: 'Pigmentation',
                    concern: 'Pigmentation',
                    type: 'Treatment',
                    priceNumber: 520,
                    price: 'Rs. 520',
                    imageUrl: './assets/img/Pigmentation.png'
                }
            ],
            'anti-ageing': [{
                    name: 'Anti-Aging Routine Care',
                    urlKey: 'anti-aging',
                    category: 'Anti-Aging',
                    concern: 'Anti-Aging',
                    type: 'Treatment',
                    priceNumber: 540,
                    price: 'Rs. 540',
                    imageUrl: './assets/img/Anti-Aging.png'
                },
                {
                    name: 'Night Repair Cream',
                    urlKey: 'korean-moon-light-night-cream',
                    category: 'Night Cream',
                    concern: 'Anti-Aging',
                    type: 'Night Cream',
                    priceNumber: 360,
                    price: 'Rs. 360',
                    imageUrl: './assets/img/night cream.jpeg'
                }
            ],
            dehydration: [{
                    name: 'Hydration Support Care',
                    urlKey: 'dehydration',
                    category: 'Dehydration',
                    concern: 'Dehydration',
                    type: 'Moisturizer',
                    priceNumber: 500,
                    price: 'Rs. 500',
                    imageUrl: './assets/img/Dehydration.png'
                },
                {
                    name: 'Hydromist Moisturizing Spray',
                    urlKey: 'hydromist-moisturizing-spray',
                    category: 'Face Mist',
                    concern: 'Dehydration',
                    type: 'Spray',
                    priceNumber: 260,
                    price: 'Rs. 260',
                    imageUrl: './assets/img/face spray.jpeg'
                }
            ]
        };

        return (samples[type] || samples.acne).map(product => ({
            ...product,
            id: product.urlKey,
            sku: product.urlKey,
            images: [product.imageUrl],
            subtitle: product.concern,
            displayConcern: product.concern,
            reviewsCount: '120',
            boughtTag: 'Sample routine pick'
        }));
    }

    function dermatHasNivisImage(product) {
        const image = dermatProductImage(product);
        if (!image) return false;

        const normalized = String(image).toLowerCase();
        const isUsableImage = !normalized.includes('/assets/img/product.webp') &&
            !normalized.includes('/assets/img/logo.jpeg');

        return isUsableImage &&
            (normalized.includes('localhost:3000') ||
                normalized.includes('/assets/img/') ||
                /^https?:\/\//.test(normalized) ||
                normalized.startsWith('./assets/img/'));
    }

    function dermatPriceNumber(product) {
        const displayPrice = String(product.price || '').replace(/,/g, '');
        const displayAmount = Number(displayPrice.replace(/[^0-9.]/g, ''));
        if (displayAmount) return displayAmount;

        const rawAmount = Number(product.priceNumber || 0);
        if (!rawAmount) return 0;

        return rawAmount;
    }

    function dermatPriceLabel(product) {
        const amount = dermatPriceNumber(product);
        return amount ? `Rs. ${amount.toLocaleString('en-IN')}` : 'Rs. 0';
    }

    function dermatCartAttrs(product) {
        const imageUrl = dermatProductImage(product);
        const priceNumber = dermatPriceNumber(product);

        return `
            data-product-id="${escapeDermatHtml(dermatProductKey(product))}"
            data-sku="${escapeDermatHtml(product.sku || '')}"
            data-product-name="${escapeDermatHtml(product.name || 'Product')}"
            data-product-price="${escapeDermatHtml(priceNumber)}"
            data-product-image="${escapeDermatHtml(imageUrl)}"
            data-price="${escapeDermatHtml(priceNumber)}"
        `;
    }

    function dermatProductCard(product, index = 0, selectable = false, active = false) {
        const fallbackImage = './assets/img/product.webp';
        const imageUrl = dermatProductImage(product);
        const selectableAttrs = selectable ? `role="button" tabindex="0" data-dermat-select="${index}"` : '';
        const price = dermatPriceLabel(product);

        return `
            <div class="product-card dermat-choice-card${active ? ' active' : ''}" ${dermatCartAttrs(product)} ${selectableAttrs}>
                <div class="dermat-choice-inner">
                    <div class="product-img-wrap">
                        <img class="img-primary" src="${escapeDermatHtml(imageUrl)}" alt="${escapeDermatHtml(product.name)}" loading="lazy" onerror="this.onerror=null;this.src='${fallbackImage}';">
                    </div>
                    <div class="product-info">
                        <div class="product-name">${escapeDermatHtml(product.name || 'Product')}</div>
                        <div class="dermat-choice-footer">
                            <span class="product-price">${escapeDermatHtml(price)}</span>
                            <span class="dermat-select-pill">Select</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function dermatRoutineItem(step, item, label) {
        const product = item.product || item;
        const imageUrl = dermatProductImage(product);
        const price = dermatPriceLabel(product);

        return `
            <div class="dermat-routine-step${item.active === false ? ' inactive' : ''}" ${dermatCartAttrs(product)}>
                <button class="dermat-routine-check" type="button" data-routine-toggle="${step - 1}" aria-label="Toggle ${escapeDermatHtml(product.name || 'Product')}"><i class="fa fa-check"></i></button>
                <img src="${escapeDermatHtml(imageUrl)}" alt="${escapeDermatHtml(product.name || 'Product')}" loading="lazy" onerror="this.onerror=null;this.src='./assets/img/product.webp';">
                <div class="dermat-routine-step-copy">
                    <div class="dermat-routine-step-label">STEP ${step}  ${escapeDermatHtml(label)}</div>
                    <div class="dermat-routine-step-name">${escapeDermatHtml(product.name || 'Product')}</div>
                    <div class="dermat-routine-step-price">${escapeDermatHtml(price)}</div>
                </div>
                <button class="dermat-direct-cart" type="button">ADD</button>
            </div>
        `;
    }

    function dermatProductSearchText(product) {
        return [
            product.name,
            product.subtitle,
            product.description,
            product.type,
            product.category,
            product.concern,
            product.displayConcern,
            product.ingredient,
            product.urlKey,
            product.sku
        ].filter(Boolean).join(' ').toLowerCase();
    }

    function dermatFindProduct(products, matchers, excludedKeys = new Set()) {
        return (products || []).find(product => {
            const key = dermatProductKey(product);
            const text = dermatProductSearchText(product);
            return key && !excludedKeys.has(key) && matchers.some(matcher => matcher(text, product));
        });
    }

    function dermatBuildRoutine(type, selectedProduct, products = dermatAllProducts) {
        const availableProducts = mergeDermatProducts(type, products);
        const selectedKey = dermatProductKey(selectedProduct);
        const usedKeys = new Set(selectedKey ? [selectedKey] : []);
        const routine = [];

        const cleanser = dermatFindProduct(availableProducts, [
            text => text.includes('cleanser') || text.includes('face wash') || text.includes('face-wash')
        ], usedKeys);
        if (cleanser) {
            routine.push({
                label: 'CLEANSER',
                product: cleanser
            });
            usedKeys.add(dermatProductKey(cleanser));
        }

        if (selectedProduct) {
            routine.push({
                label: 'TREATMENT',
                product: selectedProduct
            });
        }

        const moisturizer = dermatFindProduct(availableProducts, [
            text => text.includes('moisturizer') || text.includes('moisturiser') || text.includes('cream')
        ], usedKeys);
        if (moisturizer) {
            routine.push({
                label: 'MOISTURIZER',
                product: moisturizer
            });
            usedKeys.add(dermatProductKey(moisturizer));
        }

        const sunscreen = dermatFindProduct(availableProducts, [
            text => text.includes('sunscreen') || text.includes('spf') || text.includes('sun protection')
        ], usedKeys);
        if (sunscreen) {
            routine.push({
                label: 'SUNSCREEN',
                product: sunscreen
            });
        }

        return routine;
    }

    function dermatRoutineDoctor(type) {
        return 'Nivis Labs Recommends';
    }

    function dermatAddCardToCart(card) {
        if (!card || !window.NivisCart) return;

        const product = window.NivisCart.fromCard(card);
        if (!product) return;

        window.NivisCart.add(product, 1);

        const drawer = document.getElementById('cartDrawer');
        if (drawer && window.bootstrap) {
            bootstrap.Offcanvas.getOrCreateInstance(drawer).show();
        }
    }

    function dermatUpdateRoutineSummary() {
        const activeItems = dermatCurrentRoutine.filter(item => item.active !== false);
        const total = activeItems.reduce((sum, item) => sum + dermatPriceNumber(item.product), 0);
        const oldTotal = total ? Math.round(total / 0.72) : 0;
        const save = oldTotal - total;

        const countEl = document.getElementById('dermatRoutineCount');
        const oldEl = document.getElementById('dermatRoutineOld');
        const totalEl = document.getElementById('dermatRoutineTotal');
        const saveEl = document.getElementById('dermatRoutineSave');
        const offerEl = document.getElementById('dermatRoutineOffer');

        if (countEl) countEl.textContent = `${activeItems.length} items selected`;
        if (oldEl) oldEl.textContent = oldTotal ? `Rs. ${oldTotal.toLocaleString('en-IN')}` : '';
        if (totalEl) totalEl.textContent = `Rs. ${total.toLocaleString('en-IN')}`;
        if (saveEl) saveEl.textContent = `You save Rs. ${save.toLocaleString('en-IN')}`;
        if (offerEl) offerEl.textContent = `Buy ${activeItems.length} @ Rs. ${total.toLocaleString('en-IN')}`;
    }

    function dermatSetRoutineFromProduct(type, selectedProduct) {
        dermatCurrentRoutine = dermatBuildRoutine(type, selectedProduct).map(item => ({
            product: item.product,
            label: item.label,
            active: true
        }));
    }

    function renderDermatRoutineMode(type, visibleProducts, selectedProductIndex = 0, includeChoices = false) {
        const contentArea = document.getElementById('routine-content');
        const hint = document.getElementById('dermatResultHint');
        const selectedProduct = visibleProducts[selectedProductIndex] || visibleProducts[0];
        if (!contentArea || !selectedProduct) return;

        dermatSetRoutineFromProduct(type, selectedProduct);
        const choicesHtml = includeChoices ?
            `<div class="dermat-products-grid dermat-choice-grid text-start">${visibleProducts.map((product, index) => dermatProductCard(product, index, true, index === selectedProductIndex)).join('')}</div>` :
            '';
        const selectedImage = dermatProductImage(selectedProduct);
        const selectedSubtitle = selectedProduct.subtitle || selectedProduct.displayConcern || selectedProduct.concern || selectedProduct.category || 'Selected skincare product';
        const selectedCardHtml = `
            <div class="dermat-selected-product-card" ${dermatCartAttrs(selectedProduct)}>
                <div class="dermat-selected-product-media">
                    <img src="${escapeDermatHtml(selectedImage)}" alt="${escapeDermatHtml(selectedProduct.name || 'Product')}" loading="lazy" onerror="this.onerror=null;this.src='./assets/img/product.webp';">
                </div>
                <div class="dermat-selected-product-copy">
                    <div class="dermat-selected-kicker">Selected product</div>
                    <h3>${escapeDermatHtml(selectedProduct.name || 'Product')}</h3>
                    <p>${escapeDermatHtml(selectedSubtitle)}</p>
                    <div class="dermat-selected-actions">
                        <strong>${escapeDermatHtml(dermatPriceLabel(selectedProduct))}</strong>
                        <button class="dermat-direct-cart" type="button">ADD</button>
                    </div>
                </div>
            </div>
        `;

        if (hint) {
            hint.textContent = 'Selected product and related routine:';
        }

        contentArea.innerHTML = `
            ${choicesHtml}
            ${selectedCardHtml}

            <div class="dermat-routine-card">
                <div class="dermat-routine-head">
                    <div class="dermat-brand-mark">NIVIS<br>LABS</div>
                    <div>
                        <div class="dermat-routine-doctor">${escapeDermatHtml(dermatRoutineDoctor(type))}</div>
                        <div class="dermat-routine-tag">SOLUTION FOR ${escapeDermatHtml((selectedProduct.concern || selectedProduct.subtitle || selectedProduct.category || 'SKINCARE').toUpperCase())}</div>
                    </div>
                </div>
                <div class="dermat-routine-body">
                    ${dermatCurrentRoutine.map((item, index) => dermatRoutineItem(index + 1, item, item.label || 'PRODUCT')).join('')}
                </div>
                <div class="dermat-routine-summary">
                    <span id="dermatRoutineCount">4 items selected</span>
                    <span class="dermat-routine-old" id="dermatRoutineOld"></span>
                    <strong id="dermatRoutineTotal">Rs. 0</strong>
                </div>
                <div class="dermat-routine-saving"><span id="dermatRoutineOffer"></span> <em id="dermatRoutineSave"></em></div>
                <button class="dermat-complete-cart" type="button">ADD COMPLETE ROUTINE</button>
                <div class="dermat-routine-individual">or add products individually</div>
            </div>
        `;

        dermatUpdateRoutineSummary();
    }

    function renderDermatProductChoices(type, visibleProducts) {
        const contentArea = document.getElementById('routine-content');
        const hint = document.getElementById('dermatResultHint');
        if (!contentArea) return;

        dermatCurrentType = type;
        dermatVisibleProducts = visibleProducts;
        dermatCurrentRoutine = [];

        if (hint) {
            hint.textContent = 'Choose a product to build your routine:';
        }

        contentArea.innerHTML = `
            <div class="dermat-products-grid dermat-choice-grid text-start">
                ${visibleProducts.map((product, index) => dermatProductCard(product, index, true, false)).join('')}
            </div>
        `;
    }

    function mergeDermatProducts(type, products) {
        const mergedProducts = (products || []).filter(dermatHasNivisImage);
        const seenProducts = new Set();

        return mergedProducts.filter(product => {
            const key = String(product.id || product.sku || product.urlKey || product.url_key || product.name || '').toLowerCase();
            if (!key || seenProducts.has(key)) return false;
            seenProducts.add(key);
            return true;
        });
    }

    function dermatConcernProductsFromAll(type, products) {
        const concernAliases = {
            acne: ['acne', 'pimple', 'breakout', 'salicylic'],
            pigmentation: ['pigmentation', 'brightening', 'vitamin c', 'alpha arbutin', 'depigmentation'],
            'acne-marks': ['acne mark', 'acne marks', 'blemish', 'spot correcting'],
            'dark-spots': ['dark spot', 'dark spots', 'pigmentation', 'alpha arbutin'],
            'anti-ageing': ['anti ageing', 'anti aging', 'wrinkle', 'retinol', 'bakuchiol', 'peptide'],
            dehydration: ['dehydration', 'hydrating', 'hyaluronic', 'ceramide', 'moisture'],
            'sun-protection': ['sunscreen', 'spf', 'sun protection']
        };
        const aliases = concernAliases[type] || [type.replace(/-/g, ' ')];

        return mergeDermatProducts(type, products).filter(product => {
            const text = dermatProductSearchText(product);
            return aliases.some(alias => text.includes(alias));
        });
    }

    function renderDermatProducts(type, products) {
        const contentArea = document.getElementById('routine-content');
        if (!contentArea) return;

        let visibleProducts = dermatConcernProductsFromAll(type, products);
        if (!visibleProducts.length) {
            visibleProducts = mergeDermatProducts(type, products);
        }

        if (!visibleProducts.length) {
            visibleProducts = dermatFallbackProducts(type);
        }

        dermatCurrentType = type;
        dermatVisibleProducts = visibleProducts;
        renderDermatProductChoices(type, visibleProducts);
    }

    async function loadDermatProducts(type) {
        const contentArea = document.getElementById('routine-content');
        if (!contentArea) return;

        contentArea.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-dark" role="status"></div>
                <p class="mt-2 mb-0">Loading products...</p>
            </div>
        `;

        try {
            const category = dermatCategoryMap[type] || type;
            const [categoryResult, allResult] = await Promise.all([
                fetch(`fetch_category_products.php?category=${encodeURIComponent(category)}`).then(response => response.json()),
                fetch('fetch_category_products.php?category=all').then(response => response.json())
            ]);
            const categoryProducts = categoryResult.data?.products || categoryResult.products || [];
            const allProducts = allResult.data?.products || allResult.products || [];
            dermatAllProducts = mergeDermatProducts('all', allProducts.length ? allProducts : categoryProducts);
            const matchedProducts = dermatConcernProductsFromAll(type, dermatAllProducts);
            renderDermatProducts(type, categoryProducts.length ? categoryProducts.concat(matchedProducts) : matchedProducts);
        } catch (error) {
            renderDermatProducts(type, []);
        }
    }

    function showDermatRoutine(type, element, index = 1) {
        dermatSelectedIndex = index;
        const results = document.getElementById('dermat-results');
        if (results) results.style.display = '';
        document.querySelectorAll('.dermat-routine-section .concern-card').forEach(card => {
            card.classList.remove('active-dermat');
        });

        if (element) {
            element.querySelector('.concern-card')?.classList.add('active-dermat');
        }

        loadDermatProducts(type);
        document.getElementById('routine-content')?.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dermatSection = document.querySelector('.dermat-routine-section');
        dermatSection?.addEventListener('click', function(event) {
            const selectionCard = event.target.closest('[data-dermat-select]');
            if (selectionCard) {
                const selectedIndex = Number(selectionCard.dataset.dermatSelect || 0);
                renderDermatRoutineMode(dermatCurrentType, dermatVisibleProducts, selectedIndex, true);
                document.querySelector('.dermat-selected-product-card')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
                event.preventDefault();
                return;
            }

            const toggleButton = event.target.closest('[data-routine-toggle]');
            if (toggleButton) {
                const index = Number(toggleButton.dataset.routineToggle || 0);
                if (dermatCurrentRoutine[index]) {
                    dermatCurrentRoutine[index].active = dermatCurrentRoutine[index].active === false;
                    toggleButton.closest('.dermat-routine-step')?.classList.toggle('inactive', dermatCurrentRoutine[index].active === false);
                    dermatUpdateRoutineSummary();
                }
                event.preventDefault();
                return;
            }

            const directButton = event.target.closest('.dermat-direct-cart');
            if (directButton) {
                const card = directButton.closest('[data-product-id]');
                dermatAddCardToCart(card);
                event.preventDefault();
                return;
            }

            const completeButton = event.target.closest('.dermat-complete-cart');
            if (completeButton && window.NivisCart) {
                dermatCurrentRoutine.filter(item => item.active !== false).forEach(item => {
                    const product = item.product;
                    window.NivisCart.add({
                        id: dermatProductKey(product),
                        sku: product.sku || product.productCode || product.id || '',
                        name: product.name || 'Product',
                        price: dermatPriceNumber(product),
                        image: dermatProductImage(product),
                        quantity: 1
                    }, 1);
                });
                const drawer = document.getElementById('cartDrawer');
                if (drawer && window.bootstrap) bootstrap.Offcanvas.getOrCreateInstance(drawer).show();
                event.preventDefault();
            }
        });
        initDermatMobileSlider();
    });

    function initDermatMobileSlider() {
        const row = document.getElementById('dermatConcernRow');
        if (!row) return;

        let timer = null;

        function start() {
            if (timer || !window.matchMedia('(max-width: 576px)').matches) return;
            timer = setInterval(function() {
                const item = row.querySelector('.concern-item');
                if (!item) return;

                const nextLeft = row.scrollLeft + item.offsetWidth + 12;
                const maxLeft = row.scrollWidth - row.clientWidth - 4;
                row.scrollTo({
                    left: nextLeft >= maxLeft ? 0 : nextLeft,
                    behavior: 'smooth'
                });
            }, 2600);
        }

        function stop() {
            clearInterval(timer);
            timer = null;
        }

        row.addEventListener('mouseenter', stop);
        row.addEventListener('mouseleave', start);
        row.addEventListener('touchstart', stop, {
            passive: true
        });
        row.addEventListener('touchend', start, {
            passive: true
        });
        window.addEventListener('resize', function() {
            stop();
            start();
        });

        start();
    }
</script>















<section class="py-5 px-3 " style="background-color:#0a2b4a;">
    <div class="container px-lg-5">
        <h2 class="fw-bold mb-4 px-3 text-center text-white">SHOP OUR SPOTLIGHTS</h2>
        <div class="product-carousel" id="spotlightProductCarousel">
            <div class="spotlight-loading text-center py-4 w-100">
                <div class="spinner-border text-dark" role="status"></div>
                <p class="mt-2 mb-0">Loading products...</p>
            </div>
        </div>
    </div>
</section>




<!-- formulated sesction   -->
<!-- explore  section stylings  -->
<!-- <section class="video_section_wrapper">
    <div class="container">
        <h2 class="video_section_title">NIVIS LABS FORMULAS IN FOCUS</h2>
        <div class="video_section_carousel">

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay controls muted loop playsinline poster="./assets/img/reel_1.mp4">
                            <source src="./assets/img/reel_1.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                   
                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls poster="./assets/img/reel_2.mp4">
                            <source src="./assets/img/reel_2.mp4" type="video/mp4">
                        </video>
                    </div>
                   
                </div>
            </div>

           <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/reel_3.mp4" type="video/mp4">
                        </video>
                    </div>
                   
                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/reel_4.mp4" type="video/mp4">
                        </video>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</section> -->


<section class="video_section_wrapper d-none d-lg-block">
    <div class="container">
        <h2 class="video_section_title text-center fw-bold">NIVIS LABS FORMULAS IN FOCUS</h2>
        <div class="video_section_carousel ">

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay controls muted loop playsinline poster="./assets/img/reel_1.mp4">
                            <source src="./assets/img/reel_1.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls poster="./assets/img/reel_2.mp4">
                            <source src="./assets/img/new_video_1.mp4" type="video/mp4">
                        </video>
                    </div>

                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/UV Aqua.mp4" type="video/mp4">
                        </video>
                    </div>

                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/nivis glow.mp4" type="video/mp4">
                        </video>
                    </div>

                </div>
            </div>

        </div>






    </div>
</section>


<section class="video_section_wrapper d-block d-lg-none">
    <div class="container">
        <h2 class="video_section_title">NIVIS LABS FORMULAS IN FOCUS</h2>
        <div class="video_section_carousel ">

            <div class="swiper-container team-slider">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/reel_1.mp4">
                                        <source src="./assets/img/reel_1.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide ">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/new_video_1.mp4">
                                        <source src="./assets/img/new_video_1.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/UV Aqua.mp4">
                                        <source src="./assets/img/UV Aqua.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>




                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/nivis glow.mp4">
                                        <source src="./assets/img/nivis glow.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>






    </div>
</section>








<script>
    var swiper = new Swiper(".team-slider", {
        slidesPerView: 3, // Show 3 slides at a time
        spaceBetween: 20, // Adjust spacing between slides
        loop: true, // Enables infinite scrolling
        autoplay: {
            delay: 3000, // Auto-slide every 3 seconds
            disableOnInteraction: false,
        },

        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            1024: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 2
            },
            0: {
                slidesPerView: 1
            }
        }
    });
</script>
<hr>

<!-- <section class="explore_section_wrapper">
    <div class="container ">

        <h2 class="explore_section_title">EXPLORE OUR CATEGORIES</h2>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="explore_section_carousel">
                    <div>
                        <div class="explore_section_card">
                            <div class="explore_section_content">
                                <h4>SERUMS</h4>
                                <p>Derm-backed actives for pigmentation, aging & acne.</p>
                            </div>
                            <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Serums">
                            <a href="#" class="explore_section_shop_now">SHOP NOW</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-lg-3">
                <div class="explore_section_carousel">
                    <div>
                        <div class="explore_section_card">
                            <div class="explore_section_content">
                                <h4>SERUMS</h4>
                                <p>Derm-backed actives for pigmentation, aging & acne.</p>
                            </div>
                            <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Serums">
                            <a href="#" class="explore_section_shop_now">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="explore_section_carousel">
                    <div>
                        <div class="explore_section_card">
                            <div class="explore_section_content">
                                <h4>SERUMS</h4>
                                <p>Derm-backed actives for pigmentation, aging & acne.</p>
                            </div>
                            <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Serums">
                            <a href="#" class="explore_section_shop_now">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="explore_section_carousel">
                    <div>
                        <div class="explore_section_card">
                            <div class="explore_section_content">
                                <h4>SERUMS</h4>
                                <p>Derm-backed actives for pigmentation, aging & acne.</p>
                            </div>
                            <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Serums">
                            <a href="#" class="explore_section_shop_now">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>








        </div>

    </div>
</section> -->



<!-- <section class="video_section_wrapper d-block d-lg-none">
    <div class="container">
        <h2 class="video_section_title">EXPLORE OUR CATEGORIES</h2>
        <div class="video_section_carousel ">

            <div class="swiper-container team-slider">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/reel_1.mp4">
                                        <source src="./assets/img/reel_1.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide " >
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/new_video_1.mp4">
                                        <source src="./assets/img/new_video_1.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/UV Aqua.mp4">
                                        <source src="./assets/img/UV Aqua.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>




                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class="video_section_card">
                                <div class="video_section_container">
                                    <video autoplay controls muted loop playsinline poster="./assets/img/nivis glow.mp4">
                                        <source src="./assets/img/nivis glow.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>






    </div>
</section> -->







<!-- skinthesis section  -->
<!-- SECTION 1: STRAIGHT UP (FORMER SKINTHESIS) -->
<section class="index_straight-up_section">
    <div class="container">
        <h5 class="text-uppercase ls-2 text-white">/NIVIS SKIN GUIDE/</h5>
        <h2 class="fw-bold mb-4 text-white">Clear answers for everyday skincare decisions</h2>

        <div class="search-box mx-auto nivis-inline-search">
            <div class="input-group">
                <span class="input-group-text bg-white border-0">
                    <i class="fa fa-search text-muted"></i>
                </span>
                <input type="search" class="form-control border-0" id="indexGuideSearchInput" autocomplete="off" placeholder="Search products and categories">
            </div>
            <div class="nivis-search-results nivis-search-results--inline" id="indexGuideSearchResults"></div>
        </div>

        <div class="d-flex flex-wrap justify-content-center mt-4">
            <!-- <a href="#" class="btn btn-outline-white text-white">Advice</a>
            <a href="#" class="btn btn-outline-white text-white">Conditions</a>
            <a href="#" class="btn btn-outline-white text-white">How-To</a>
            <a href="#" class="btn btn-outline-white text-white">Ingredients</a>
            <a href="#" class="btn btn-outline-white text-white">Index</a> -->
            <!-- <span href="general_advice.php" class="index_img_section__badge mx-1">Advice</span>
            <span href="skin_condition.php" class="index_img_section__badge  mx-1">Conditions</span>
            <span href="how-tos.php" class="index_img_section__badge mx-1">How-To</span>
            <span href="integrety.php" class="index_img_section__badge mx-1">Ingredients</span>
            <span href="index.php" class="index_img_section__badge mx-1">Index</span> -->
            <a href="general_advice.php" class="index_img_section__badge mx-1">Advice</a>
            <a href="skin_condition.php" class="index_img_section__badge mx-1">Conditions</a>
            <a href="how-tos.php" class="index_img_section__badge mx-1">How-To</a>
            <a href="integrety.php" class="index_img_section__badge mx-1">Ingredients</a>
            <a href="index.php" class="index_img_section__badge mx-1">Index</a>

        </div>
    </div>
</section>


<!-- SECTION 2: ICONS -->
<!-- <section class="index_last_second">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2833/2833315.png" style="width: 100px;">
                <p>Quality-Focused Formulas</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/3063/3063822.png" style="width: 100px;">
                <p>Skin-Friendly Approach</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2913/2913564.png" style="width: 100px;">
                <p>Thoughtful Ingredient Selection</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/802/802826.png" style="width: 100px;">
                <p>Built for Daily Routines</p>
            </div>
        </div>
    </div>
</section> -->


<section class="explore_section_wrapper">
    <div class="container">

        <h2 class="explore_section_title">EXPLORE OUR CATEGORIES</h2>

        <div class="row g-3">
            <div class="col-md-6 col-xl-3">

                <a href="category.php?category=face-serum" class="explore_section_card">

                    <div class="explore_section_content">
                        <h4>Face Serum</h4>
                        <p>Targeted actives for glow, acne marks, and uneven tone.</p>
                    </div>
                    <img src="./assets/img/FACE SERUM.jpeg" class="explore_section_img" alt="Face Serum">
                    <span class="explore_section_shop_now">SHOP NOW</span>

                </a>

            </div>

            <div class="col-md-6 col-xl-3">

                <a href="category.php?category=moisturizers" class="explore_section_card">
                    <div class="explore_section_content">
                        <h4>Moisturizers</h4>
                        <p>Hydration and barrier repair for daily skin comfort.</p>
                    </div>
                    <img src="./assets/img/foot cream.jpeg" class="explore_section_img" alt="Moisturizers">
                    <span class="explore_section_shop_now">SHOP NOW</span>
                </a>

            </div>

            <div class="col-md-6 col-xl-3">

                <a href="category.php?category=sunscreen" class="explore_section_card">
                    <div class="explore_section_content">
                        <h4>Sunscreen</h4>
                        <p>Advanced UV protection for everyday outdoor care.</p>
                    </div>
                    <img src="./assets/img/face spray.jpeg" class="explore_section_img" alt="Sunscreen">
                    <span class="explore_section_shop_now">SHOP NOW</span>
                </a>

            </div>

            <div class="col-md-6 col-xl-3">
                <a href="category.php?category=face-cleanser" class="explore_section_card">
                    <div class="explore_section_content">
                        <h4>Face Cleanser</h4>
                        <p>Gentle daily cleansing for fresh, balanced skin.</p>
                    </div>
                    <img src="./assets/img/SUNSCFREEN.jpeg" class="explore_section_img" alt="Face Cleanser">
                    <span class="explore_section_shop_now">SHOP NOW</span>
                </a>
            </div>
        </div>


    </div>




</section>

















<section>
    <div class="faq-section">

        <h2 class="faq-title">FREQUENTLY ASKED</h2>
        <p class="faq-subtitle">
            Quick answers about Nivis Labs, our product approach, and how to choose the right formula for your routine.
        </p>

        <div class="accordion" id="faqAccordion">

            <!-- Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        What is Nivis Labs?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Nivis Labs is a skincare brand focused on practical, science-aware formulas for everyday skin
                        needs like hydration, cleansing, sun protection, brightening, and barrier support.
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        How does Nivis Labs choose products?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        We build around real use cases first, then choose ingredients, textures, and product formats
                        that make the formula easy to understand and easy to use consistently.
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        Why choose Nivis Labs?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Nivis Labs keeps skincare simple: clear categories, focused formulas, transparent product
                        information, and routines that support healthy-looking skin without unnecessary confusion.
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- <section class="featured-section">
    <div class="container">

        <h2 class="featured-title">FEATURED IN</h2>
        <div class="featured-line"></div>

        <p class="featured-subtitle">
            Trusted by customers looking for clear, practical skincare from Nivis Labs.
        </p>

         <div class="row g-4 justify-content-center">

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/FACE SERUM.jpeg" alt="products" class="img-fluid">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/face spray.jpeg" alt="product" class="img-fluid">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/foot cream.jpeg" alt="product" class="img-fluid">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/SUNSCFREEN.jpeg" alt="product" class="img-fluid">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/INSTA GLOW.jpeg" alt="product" class="img-fluid">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/face spray.jpeg" alt="product" class="img-fluid">
                </div>
            </div>

        </div>

    </div>
</section> -->








<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    $(document).ready(function() {
        const spotlightCarousel = $('#spotlightProductCarousel');

        function escapeSpotlightHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function(char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                } [char];
            });
        }

        function spotlightProductCard(product) {
            const fallbackImage = './assets/img/logo.jpeg';
            const imageUrl = product.imageUrl || product.primaryImage || fallbackImage;
            const productKey = product.urlKey || product.url_key || product.sku || product.id || product.name || '';
            const link = `product-detail.php?product=${encodeURIComponent(productKey)}`;
            const subtitle = product.subtitle || product.displayConcern || product.concern || product.category || 'Skincare';
            const size = product.size ? `<span class="spotlight-product-size">${escapeSpotlightHtml(product.size)}</span>` : '';
            const priceNumber = Number(String(product.priceNumber || product.price || '0').replace(/,/g, '').replace(/[^0-9.]/g, '')) || 0;
            const priceLabel = priceNumber ? `&#8377;${priceNumber.toLocaleString('en-IN')}` : '&#8377;0';
            const ratingHtml = '<span class="text-warning">★★★★☆</span>';

            return `
                <div class="px-2">
                    <div class="spotlight-card"
                        data-product-id="${escapeSpotlightHtml(productKey)}"
                        data-product-name="${escapeSpotlightHtml(product.name || 'Product')}"
                        data-product-price="${escapeSpotlightHtml(priceNumber)}"
                        data-product-image="${escapeSpotlightHtml(imageUrl)}"
                        data-price="${escapeSpotlightHtml(priceNumber)}">
                        <a href="${escapeSpotlightHtml(link)}">
                            <img src="${escapeSpotlightHtml(imageUrl)}" class="w-100 mb-3" alt="${escapeSpotlightHtml(product.name || 'Product')}" loading="lazy" onerror="this.onerror=null;this.src='${fallbackImage}';">
                            <h6 class="fw-bold">${escapeSpotlightHtml(product.name || 'Product')} ${size}</h6>
                            <p class="small text-muted mb-2">/ ${escapeSpotlightHtml(subtitle)} /</p>
                            <div class="spotlight-card__meta small mb-2">${ratingHtml} (${escapeSpotlightHtml(product.reviewsCount || 120)} reviews)</div>
                            <div class="spotlight-card__price mb-3"><span class="badge-b1g1">${escapeSpotlightHtml(product.boughtTag || 'B1G1')}</span> <span class="ms-1">${priceLabel}</span></div>
                        </a>
                        <div class="spotlight-card__popover">
                            <div class="spotlight-card__popover-title">${escapeSpotlightHtml(product.name || 'Product')}</div>
                            <p class="spotlight-card__popover-text">${escapeSpotlightHtml(subtitle)}</p>
                            <div class="spotlight-card__popover-meta">${escapeSpotlightHtml(product.type || 'Product')} ${product.size ? `• ${escapeSpotlightHtml(product.size)}` : ''} • ${priceLabel}</div>
                        </div>
                        <button type="button" class="btn btn-dark spotlight-card__btn w-100 rounded-0">ADD TO CART</button>
                    </div>
                </div>
            `;
        }

        function initProductCarousel() {
            if (!spotlightCarousel.length || spotlightCarousel.hasClass('slick-initialized')) return;

            spotlightCarousel.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2500,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }

        async function loadSpotlightProducts() {
            if (!spotlightCarousel.length) return;

            try {
                const response = await fetch('fetch_category_products.php?category=all');
                const result = await response.json();
                const products = result.products || [];

                if (products.length > 0) {
                    spotlightCarousel.html(products.map(spotlightProductCard).join(''));
                } else {
                    spotlightCarousel.html('<p class="text-muted mb-0 px-3">No products found.</p>');
                }
            } catch (error) {
                console.error('Unable to load spotlight products:', error);
                spotlightCarousel.html('<p class="text-danger mb-0 px-3">Unable to load products.</p>');
            }

            if (spotlightCarousel.children().length > 1) {
                initProductCarousel();
            }
        }

        loadSpotlightProducts();

        spotlightCarousel.on('click', '.spotlight-card__btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const card = this.closest('[data-product-id]');
            if (!card || !window.NivisCart) return;

            const product = window.NivisCart.fromCard(card);
            if (!product) return;

            window.NivisCart.add(product, 1);
        });

        spotlightCarousel.on('mouseenter focusin', '.spotlight-card', function() {
            this.classList.add('is-hovered');
        });

        spotlightCarousel.on('mouseleave focusout', '.spotlight-card', function() {
            this.classList.remove('is-hovered');
        });

        // 2. Countdown Timer Logic
        function startTimer(duration) {
            var timer = duration,
                hours, minutes, seconds;
            setInterval(function() {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                $('#hrs').text(hours < 10 ? "0" + hours : hours);
                $('#min').text(minutes < 10 ? "0" + minutes : minutes);
                $('#sec').text(seconds < 10 ? "0" + seconds : seconds);

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }
        startTimer(33593); // 09:19:53 in seconds
    });
</script>

<!-- explore stylings  -->
<script>
    $(document).ready(function() {
        $('.video_section_carousel').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            arrows: true,
            dots: false,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1.2,
                        arrows: false
                    }
                } // Mobile lo 1.2 isthe next card tease chestunnattu untundi
            ]
        });

        $('.explore_section_carousel').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: false,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>






<script>
    function showStep(step) {
        // Hide all steps
        document.getElementById('step-landing').classList.add('d-none');
        document.querySelectorAll('.step-container').forEach(el => el.classList.add('d-none'));

        // Show target step
        if (step === 1) document.getElementById('step-1').classList.remove('d-none');
        if (step === 2) document.getElementById('step-2').classList.remove('d-none');
        if (step === 3) document.getElementById('step-3').classList.remove('d-none');
    }

    function toggleSelection(btn, stepNum) {
        btn.classList.toggle('active');

        // Check if any button in this step is active
        const parent = btn.closest('.step-container');
        const anyActive = parent.querySelectorAll('.option-btn.active').length > 0;
        const nextBtn = document.getElementById('next-' + stepNum);

        if (anyActive) {
            nextBtn.classList.remove('disabled', 'btn-secondary');
            nextBtn.classList.add('enabled-next');
        } else {
            nextBtn.classList.add('disabled', 'btn-secondary');
            nextBtn.classList.remove('enabled-next');
        }
    }
</script>

<?php include 'footer.php'; ?>
