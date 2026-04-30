<?php include 'navbar.php'; ?>

<!-- ════════════════════════════════════════════
     3. HERO IMAGE SECTION
════════════════════════════════════════════ -->
<section class="index_img_section" id="indexHero" onclick="window.location.href='panthenol-hydrating-gel-sunscreen.php'" role="link" tabindex="0" aria-label="View Panthenol Hydrating Gel Sunscreen">

    <!-- Background -->
    <div class="index_img_section__bg"></div>
    <div class="index_img_section__stripes"></div>

    <!-- Spotlight glow -->
    <div class="index_img_section__glow"></div>

    <!-- Light rays SVG -->
    <svg class="index_img_section__rays" viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <line class="ray ray-1" x1="820" y1="380" x2="1440" y2="60" />
        <line class="ray ray-2" x1="820" y1="380" x2="1440" y2="200" />
        <line class="ray ray-3" x1="820" y1="380" x2="1440" y2="720" />
        <line class="ray ray-4" x1="820" y1="380" x2="200" y2="800" />
        <line class="ray ray-1" x1="820" y1="380" x2="100" y2="50" />
        <line class="ray ray-3" x1="820" y1="380" x2="600" y2="800" />
    </svg>

    <!-- Product image — using a placeholder sunscreen image -->
    <img
        class="index_img_section__product"
        src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=500&q=90&fit=crop&crop=center"
        alt="PHD Panthenol Hydrating Gel Sunscreen SPF 60 PA++++"
        onerror="this.src='https://placehold.co/360x600/8b0000/ffffff?text=/PHD/+SPF60'" />

    <!-- Content overlay -->
    <div class="index_img_section__content">
        <span class="index_img_section__new-tag">New Launch</span>
        <h1 class="index_img_section__title">
            /Panthenol<br />
            Hydrating Gel<br />
            Sunscreen/
        </h1>
        <div class="index_img_section__badges">
            <span class="index_img_section__badge">SPF 60 PA++++</span>
            <span class="index_img_section__badge">European Standard New-Generation Filters</span>
            <span class="index_img_section__badge">No Whitecast</span>
        </div>
    </div>

    <!-- CTA hint -->
    <div class="index_img_section__cta">
        <span>Shop Now</span>
        <i class="bi bi-arrow-right-circle"></i>
    </div>
</section>

<!-- our spot light  -->



<div class="container">
    <div class="col-3">
        <div class="card">

        </div>
    </div>
    <div class="col-3"></div>
    <div class="col-3"></div>
    <div class="col-3"></div>
</div>


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

<div class="container my-5 ai_powered_skin_analysis">
    <div class="skin-wrapper_index border rounded-3 position-relative ">

        <!-- TOP LABEL -->
        <div class="skin-label_index bg-danger text-white px-3 py-1 position-absolute top-0 start-0 translate-middle-y ms-4 fw-bold small">
            SKIN ASSESSMENT
        </div>

        <!-- SECTION 0: LANDING (Your Original Code) -->
        <div id="step-landing" class="skin-section_index py-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p class="text-danger fw-semibold mb-1">AI-POWERED SKIN ANALYSIS</p>
                    <h2 class="skin-title_index fw-bold mb-3">GET CURATED HELP FOR YOUR SKIN</h2>
                    <p class="text-muted">Answer 3 quick questions — our AI dermat advisor will build a personalised routine just for you.</p>
                    <button class="skin-btn_index btn btn-dark px-4 py-2" onclick="showStep(1)">
                        START MY SKIN ASSESSMENT →
                    </button>
                </div>
                <div class="col-md-5 mt-4 mt-md-0">
                    <div class="feature-box_index d-flex align-items-start mb-3">
                        <div class="feature-icon_index me-3"><i class="fa fa-user text-danger"></i></div>
                        <div><strong>Know your skin</strong><br><small class="text-muted">Identify your skin type in seconds</small></div>
                    </div>
                    <div class="feature-box_index d-flex align-items-start mb-3">
                        <div class="feature-icon_index me-3"><i class="fa fa-heartbeat text-danger"></i></div>
                        <div><strong>Target concerns</strong><br><small class="text-muted">Acne, pigmentation, aging & more</small></div>
                    </div>
                    <div class="feature-box_index d-flex align-items-start">
                        <div class="feature-icon_index me-3"><i class="fa fa-image text-danger"></i></div>
                        <div><strong>Photo analysis</strong><br><small class="text-muted">AI analyses your skin for better results</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 1: SKIN TYPE -->
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

        <!-- STEP 2: CONCERNS -->
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

        <!-- STEP 3: PHOTO UPLOAD -->
        <div id="step-3" class="step-container d-none text-center py-4">
            <div class="progress mb-3 mx-auto" style="height: 4px; width: 200px;">
                <div class="progress-bar bg-danger" style="width: 100%"></div>
            </div>
            <p class="text-danger small fw-bold mb-1">STEP 3 OF 3</p>
            <h3 class="fw-bold">Share a photo for analysis</h3>
            <p class="text-muted small">A clear, well-lit selfie helps us recommend better.</p>

            <div class="upload-box border border-danger border-dashed rounded-3 p-5 my-4 mx-auto" style="max-width: 400px; border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                <i class="fa fa-image fs-1 text-danger mb-2"></i>
                <p class="mb-0 fw-bold">Tap to upload a selfie</p>
                <small class="text-muted">JPG, PNG under 4MB</small>
                <input type="file" id="fileInput" class="d-none" accept="image/*">
            </div>

            <button class="btn btn-danger w-100 py-3 fw-bold mb-2">GET MY ROUTINE →</button>
            <a href="#" class="text-muted small text-decoration-underline">Skip photo & continue</a>
        </div>

    </div>
</div>


<div class="container my-5">
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
</div>











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


<div class="container my-5 img_section_container">
    <div class="img_section text-center">
        <h2 class="fw-bold mb-4" style="letter-spacing: 1px;">FIND YOUR DERMAT ROUTINE</h2>

        <!-- TOP NAVIGATION: ALL 7 IMAGES IN ONE LINE -->
        <div class="d-flex flex-nowrap justify-content-start justify-content-md-center overflow-auto pb-3 gap-2 no-scrollbar">

            <div class="concern-item" onclick="showDermatRoutine('acne', this)">
                <div class="concern-card active-dermat">
                    <img src="./assets/img/logo.jpeg" alt="Acne">
                    <div class="concern-overlay">Acne</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('pigmentation', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Pigmentation">
                    <div class="concern-overlay">Pigmentation</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('acne-marks', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Acne Marks">
                    <div class="concern-overlay">Acne Marks</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('dark-spots', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Dark Spots">
                    <div class="concern-overlay">Dark Spots</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('anti-ageing', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Anti-Ageing">
                    <div class="concern-overlay">Anti-Ageing</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('dehydration', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Dehydration">
                    <div class="concern-overlay">Dehydration</div>
                </div>
            </div>

            <div class="concern-item" onclick="showDermatRoutine('eczema', this)">
                <div class="concern-card">
                    <img src="./assets/img/logo.jpeg" alt="Eczema">
                    <div class="concern-overlay">Eczema</div>
                </div>
            </div>

        </div>

        <!-- DYNAMIC PRODUCT AREA (Content injects here) -->
        <div id="dermat-results" class="mt-4">
            <p class="text-muted small mb-3">Choose your serum / treatment:</p>

            <!-- Result Container (Initial: Acne with 4 bottles matches first image) -->
            <!-- <div id="routine-content" class="mx-auto" style="max-width: 900px;">
                <img src="./assets/img/logo.jpeg " class="img-fluid rounded" alt="Acne Routine">
            </div> -->
        </div>
    </div>
</div>








<script>
    function showDermatRoutine(type, element) {
        // 1. Remove active border from all navigation cards
        document.querySelectorAll('.concern-card').forEach(card => {
            card.classList.remove('active-dermat');
        });

        // 2. Add active border to clicked navigation card
        element.querySelector('.concern-card').classList.add('active-dermat');

        // 3. Update the routine content image based on type
        const contentArea = document.getElementById('routine-content');
        let imageSrc = "";

        // EXACT MAPPING BASED ON PROVIDED IMAGES:
        switch (type) {
            case 'acne':
                imageSrc = "image_fe3475.jpg"; // Initial image (4 bottles)
                break;
            case 'pigmentation':
                imageSrc = "image_fe307f.jpg"; // Single Alpha Arbutin & Doctor recommends
                break;
            case 'acne-marks':
                imageSrc = "image_fe3059.jpg"; // Alpha Arbutin & Niacinamide
                break;
            case 'dark-spots':
                imageSrc = "image_fe3020.jpg"; // Single Vitamin C Brightening & Doctor recommends
                break;
            case 'anti-ageing':
                imageSrc = "image_fe3003.jpg"; // Single Retinol Face Serum & Doctor recommends
                break;
            case 'dehydration':
                imageSrc = "image_fe2ffc.jpg"; // Single Hyaluronic Acid Skin Serum & Doctor recommends
                break;
            case 'eczema':
                imageSrc = "image_fdde26.jpg"; // Single Ceramides Intensive Repair Cream & Doctor recommends
                break;
            default:
                imageSrc = "image_fe3475.jpg";
        }

        // Apply fadeIn animation on image update
        contentArea.innerHTML = `<img src="${imageSrc}" class="img-fluid rounded shadow" alt="${type} Routine" style="animation: fadeIn 0.5s ease-in-out;">`;

        // Optional: Smooth scroll to the result
        contentArea.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    }
</script>















<section class="py-5 px-3 bg-light">
    <div class="container px-lg-5">
        <h2 class="fw-bold mb-4 px-3">SHOP OUR SPOTLIGHTS</h2>
        <div class="product-carousel">
            <div class="px-2">
                <div class="product-card">
                    <a href="salicylic_acid_anti_acne_serum.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p1">
                        <h6 class="fw-bold">2% Salicylic Acid Anti-Acne Serum</h6>
                        <p class="small text-muted mb-2">/Solution for Pigmentation/</p>
                        <div class="small mb-2">★★★★★ (193 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹699</del> <span class="free-text ms-1">FREE</span></div>

                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>
            <div class="px-2">
                <div class="product-card">
                    <a href="alpha_arbutin_depigmentation_serum.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p2">
                        <h6 class="fw-bold">2% Alpha Arbutin Depigmentation Serum</h6>
                        <p class="small text-muted mb-2">/Brightening Solution/</p>
                        <div class="small mb-2">★★★★★ (152 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹699</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>
            <div class="px-2">
                <div class="product-card">
                    <a href="salicylic_acid_acne_spot_treatment_gel.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p3">
                        <h6 class="fw-bold">Salicylic Acid Acne Spot Treatment Gel</h6>
                        <p class="small text-muted mb-2">/Spot Correcting/</p>
                        <div class="small mb-2">★★★★★ (168 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹649</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>
            <div class="px-2">
                <div class="product-card">
                    <a href="niacinamide_spot_correcting_serum.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p4">
                        <h6 class="fw-bold">10% Niacinamide Spot Correcting Serum</h6>
                        <p class="small text-muted mb-2">/Hydrating Solution/</p>
                        <div class="small mb-2">★★★★★ (120 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹315</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>
            <div class="px-2">
                <div class="product-card">

                    <a href="pure_retinol_face_serum.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p5">
                        <h6 class="fw-bold">0.3% Pure Retinol Face Serum</h6>
                        <p class="small text-muted mb-2">/UV Protection/</p>
                        <div class="small mb-2">★★★★★ (200 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹499</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>

            <div class="px-2">
                <div class="product-card">

                    <a href="panthenol_hydrating_gel_sunscreen_spf_60.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p5">
                        <h6 class="fw-bold">Panthenol Hydrating Gel Sunscreen SPF 60 PA++++</h6>
                        <p class="small text-muted mb-2">/UV Protection/</p>
                        <div class="small mb-2">★★★★★ (200 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹499</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>

            <div class="px-2">
                <div class="product-card">

                    <a href="ceramides_intensive_repair_cream.php">
                        <img src="./assets/img/logo.jpeg" class="w-100 mb-3" alt="p5">
                        <h6 class="fw-bold">1% Ceramides Intensive Repair Cream</h6>
                        <p class="small text-muted mb-2">/UV Protection/</p>
                        <div class="small mb-2">★★★★★ (200 reviews)</div>
                        <div class="mb-3"><span class="badge-b1g1">B1G1</span> <del class="text-muted ms-1">₹499</del> <span class="free-text ms-1">FREE</span></div>
                    </a>
                    <button class="btn btn-dark w-100 rounded-0">ADD TO CART</button>
                </div>
            </div>






        </div>
    </div>
</section>




<!-- formulated sesction   -->
<!-- explore  section stylings  -->
<section class="video_section_wrapper">
    <div class="container">
        <h2 class="video_section_title">FORMULATED WITH GLOBAL DERMATOLOGISTS</h2>
        <div class="video_section_carousel">

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay controls muted loop playsinline poster="./assets/img/V & V WSaloon MVP.mp4">
                            <source src="./assets/img/V & V WSaloon MVP.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="video_section_product_info">
                        <a href="vitamin_c_brightening_serum.php">
                            <div class="video_section_product_name">10% Vitamin C Brightening Serum</div>
                            <div>
                                <span class="video_section_price_badge">B1G1</span>
                                <del class="small text-muted">₹699</del>
                                <span class="video_section_free">FREE</span>
                            </div>
                        </a>
                        <button class="video_section_add_btn">ADD TO CART</button>
                    </div>
                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline poster="./assets/img/V & V WSaloon MVP.mp4">
                            <source src="./assets/img/V & V WSaloon MVP.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="video_section_product_info">
                        <a href="vitamin_c_brightening_moisturizer.php">
                            <div class="video_section_product_name">Vitamin C Brightening Moisturizer</div>
                            <div><span class="video_section_price_badge">B1G1</span> <del
                                    class="small text-muted">₹549</del> <span class="video_section_free">FREE</span>
                            </div>
                        </a>
                        <button class="video_section_add_btn">ADD TO CART</button>
                    </div>
                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/V & V WSaloon MVP.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="video_section_product_info">
                        <a href="niacinamide_spot_correcting_serum.php">
                            <div class="video_section_product_name">10% Niacinamide Spot Correcting Serum</div>
                            <div><span class="video_section_price_badge">B1G1</span> <del
                                    class="small text-muted">₹649</del> <span class="video_section_free">FREE</span>
                            </div>
                        </a>
                        <button class="video_section_add_btn">ADD TO CART</button>
                    </div>
                </div>
            </div>

            <div class="px-2">
                <div class="video_section_card">
                    <div class="video_section_container">
                        <video autoplay muted loop playsinline controls>
                            <source src="./assets/img/V & V WSaloon MVP.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="video_section_product_info">
                        <a href="alpha_arbutin_depigmentation_serum.php">
                            <div class="video_section_product_name">2% Alpha Arbutin Depigmentation Serum</div>
                            <div><span class="video_section_price_badge">B1G1</span> <del
                                    class="small text-muted">₹699</del> <span class="video_section_free">FREE</span>
                            </div>
                        </a>
                        <button class="video_section_add_btn">ADD TO CART</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

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

<section class="explore_section_wrapper">
    <div class="container">

        <h2 class="explore_section_title">EXPLORE OUR CATEGORIES</h2>

        <div class="row g-3">
            <div class="col-md-6 col-lg-3">

                <div class="explore_section_card">

                    <div class="explore_section_content">
                        <a href="face_serum.php"></a>
                        <h4>SERUMS</h4>
                        <p>Derm-backed actives for pigmentation, aging & acne.</p>
                    </div>
                    <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Serums">
                    <a href="face_serum.php" class="explore_section_shop_now">SHOP NOW</a>
                    </a>

                </div>

            </div>

            <div class="col-md-6 col-lg-3">

                <div class="explore_section_card">
                    <div class="explore_section_content">
                        <a href="moisturizers.php">
                            <h4>CREAMS</h4>
                            <p>Hydration & barrier repair solutions.</p>
                    </div>
                    <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Creams">
                    <a href="moisturizers.php" class="explore_section_shop_now">SHOP NOW</a></a>
                </div>

            </div>

            <div class="col-md-6 col-lg-3">

                <div class="explore_section_card">
                    <div class="explore_section_content">
                        <a href="sunscreens.php">
                            <h4>SUNSCREEN</h4>
                            <p>Advanced UV protection for daily use.</p>
                    </div>
                    <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Sunscreen">
                    <a href="sunscreens.php" class="explore_section_shop_now">SHOP NOW</a>
                    </a>
                </div>

            </div>

            <div class="col-md-6 col-lg-3">
                <div class="explore_section_card">
                    <div class="explore_section_content">
                        <a href="face-wash.php">
                            <h4>Face wash</h4>
                            <p>Gentle yet effective daily cleansing.</p>
                    </div>
                    <img src="./assets/img/logo.jpeg" class="explore_section_img" alt="Cleanser">
                    <a href="face-wash.php" class="explore_section_shop_now">SHOP NOW</a>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- skinthesis section  -->
<!-- SECTION 1: STRAIGHT UP (FORMER SKINTHESIS) -->
<section class="index_straight-up_section">
    <div class="container">
        <h5 class="text-uppercase ls-2 text-white">/SKINTHESIS/</h5>
        <h2 class="fw-bold mb-4">Straight-up answers to your skincare questions</h2>

        <div class="search-box mx-auto">
            <div class="input-group">
                <span class="input-group-text bg-white border-0">
                    <i class="fa fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-0" placeholder="Search Skincare Tips">
            </div>
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
<section class="index_last_second">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2833/2833315.png" style="width: 100px;">
                <p>Indian FDA Approved</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/3063/3063822.png" style="width: 100px;">
                <p>Dermatologically Tested</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2913/2913564.png" style="width: 100px;">
                <p>No Toxic Chemicals</p>
            </div>
            <div class="col-6 col-md-3 mb-3 icon-box">
                <img src="https://cdn-icons-png.flaticon.com/512/802/802826.png" style="width: 100px;">
                <p>Plastic Positive</p>
            </div>
        </div>
    </div>
</section>


<section>
    <div class="faq-section">

        <h2 class="faq-title">FREQUENTLY ASKED</h2>
        <p class="faq-subtitle">
            Quick answers about /PHD/, our dermatologist council and what makes our formulas different.
        </p>

        <div class="accordion" id="faqAccordion">

            <!-- Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        What does /PHD/ stand for?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        /PHD/ stands for <b>Proven Honest Derma</b>. Every formula is co-developed with dermatologists
                        (Proven), uses INCI-disclosed active ingredients at clinically validated concentrations – no
                        fluff, no hidden claims (Honest), and is rooted in clinical dermatology rather than trend-led
                        marketing (Derma).
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        Who are the dermatologists behind /PHD/?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Our dermatologists are certified experts who co-create and validate each product using
                        scientific research and clinical testing.
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        Why trust /PHD/?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        We focus on transparency, clinically proven ingredients, and dermatologist-backed formulations
                        to ensure safe and effective skincare.
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<section class="featured-section">
    <div class="container">

        <h2 class="featured-title">FEATURED IN</h2>
        <div class="featured-line"></div>

        <p class="featured-subtitle">
            Dermatologist-approved formulations, covered by leading media.
        </p>

        <div class="row g-4 justify-content-center">

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="last_section">
                    <img src="./assets/img/logo_1 (1).png" alt="">
                </div>
            </div>

        </div>

    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    $(document).ready(function() {
        // 1. Slider Init
        $('.product-carousel').slick({
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