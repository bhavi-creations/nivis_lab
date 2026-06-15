<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="title" content="Nivis Labs | Dermatologist-Tested Skincare Products for Healthy Skin" />
    <meta name="descriptions" content="Discover Nivis Labs' dermatologist-tested skincare range including serums, moisturizers, sunscreens, and cleansers. Target acne, pigmentation, dark spots, dehydration, and aging with science-backed formulations." />
    <title>Nivis Labs</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="./assets/css/style.css?v=14">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />



</head>

<body>


    <!-- ════════════════════════════════════════════
     1. MARQUEE SECTION
════════════════════════════════════════════ -->
    <div class="index_marquee_section">
        <div class="index_marquee_section__track" id="marqueeTrack">

            <!-- Repeated twice for seamless loop -->
            <!-- Set A -->
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">10%</span> Off on 1 item
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">20%</span> Off on 2 items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">32%</span> Off on 3+ items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                Free Shipping on orders above ₹499
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">10%</span> Off on 1 item
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">20%</span> Off on 2 items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">32%</span> Off on 3+ items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                Dermatologist Tested &amp; Approved
            </span>
            <span class="index_marquee_section__sep"></span>

            <!-- Set B (duplicate for infinite scroll) -->
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">10%</span> Off on 1 item
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">20%</span> Off on 2 items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">32%</span> Off on 3+ items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                Free Shipping on orders above ₹499
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">10%</span> Off on 1 item
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">20%</span> Off on 2 items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                <span class="index_marquee_section__num">32%</span> Off on 3+ items
            </span>
            <span class="index_marquee_section__sep"></span>
            <span class="index_marquee_section__item">
                Dermatologist Tested &amp; Approved
            </span>
            <span class="index_marquee_section__sep"></span>

        </div>
    </div>


    <!-- ════════════════════════════════════════════
     2. NAVBAR SECTION
════════════════════════════════════════════ -->
    <header class="  index_navbar_section" id="indexNavbar">
        <div class="  container  index_navbar_section__inner">


            <a href="/" class="index_navbar_section__logo">

                <img src="./assets/img/logo_1 (1).png" alt="" style="width: 100px;">
            </a>


            <ul class="index_navbar_section__links">


                <li class="index_navbar_section__item">
                    <a class="index_navbar_section__link" href="index.php">Home</a>
                </li>


                <li class="index_navbar_section__item">
                    <a class="index_navbar_section__link" href="our-story.php">About Us</a>
                </li>


                <li class="index_navbar_section__item">
                    <a class="index_navbar_section__link" href="products.php">Product</a>
                    <ul class="index_navbar_section__dropdown" id="navbarCategoryDesktop">
                        <li>Loading categories...</li>
                    </ul>
                </li>


                <li class="index_navbar_section__item">
                    <a class="index_navbar_section__link" href="#">Ingredients</a>
                    <ul class="index_navbar_section__dropdown" id="navbarIngredientDesktop">
                        <li><a href="salicylic_acid.php">Salicylic Acid</a></li>
                        <li><a href="niacinamide.php">Niacinamide</a></li>
                        <li><a href="alpha_arbutin.php">Alpha Arbutin</a></li>
                        <li><a href="vitamin_c.php">Vitamin C</a></li>
                        <li><a href="retinol.php">Retinol</a></li>
                        <li><a href="hyaluronic_acid.php">Hyaluronic Acid</a></li>
                        <li><a href="ceramides.php">Ceramides</a></li>
                        <li><a href="products.php">Explore all products</a></li>
                    </ul>
                </li>


                <li class="index_navbar_section__item">
                    <a class="index_navbar_section__link" href="contact.php">Contact Us</a>
                </li>

            </ul>


            <div class="index_navbar_section__icons">
                <button class="index_navbar_section__icon-btn" title="Search">
                    <i class="bi bi-search"></i>
                </button>
                <button class="index_navbar_section__icon-btn" title="Account">
                    <i class="bi bi-person"></i>
                </button>
                <button class="index_navbar_section__icon-btn" title="Cart" style="position:relative" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-controls="cartDrawer">
                    <i class="bi bi-bag"></i>
                    <span class="index_navbar_section__cart-badge" id="cartBadge">0</span>
                </button>
            </div>


            <button class="index_navbar_section__burger" id="navBurger" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>

        </div>
    </header>



    <!-- Fixed Rewards pill -->
    <!-- <a href="#" class="our_section_5__rewards">
    <span class="our_section_5__rewards-icon">🎁</span>
    Rewards
  </a> -->
    <?php include 'reward.php'; ?>




    <!-- ── BACKDROP ── -->
    <div class="index_navbar_section__backdrop" id="navBackdrop"></div>

    <!-- ── MOBILE OFFCANVAS ── -->
    <div class="index_navbar_section__offcanvas" id="navOffcanvas">

        <div class="index_navbar_section__offcanvas-head">
            <span class="index_navbar_section__offcanvas-logo">Nivis Labs</span>
            <button class="index_navbar_section__offcanvas-close" id="navClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <ul class="index_navbar_section__mob-list">

            <!-- HOME -->
            <li class="index_navbar_section__mob-item">
                <a href="index.php" class="index_navbar_section__mob-plain">Home</a>
            </li>

            <!-- ABOUT US -->
            <li class="index_navbar_section__mob-item">
                <a href="our-story.php" class="index_navbar_section__mob-plain">About Us</a>
            </li>

            <!-- PRODUCT -->
            <li class="index_navbar_section__mob-item" data-has-sub="true">
                <div class="index_navbar_section__mob-row">
                    <span class="index_navbar_section__mob-label">Product</span>
                    <span class="index_navbar_section__mob-plus"><i class="bi bi-plus"></i></span>
                </div>
                <ul class="index_navbar_section__mob-sub" id="navbarCategoryMobile">
                    <li>Loading categories...</li>
                </ul>
            </li>

            <!-- INGREDIENTS -->
            <li class="index_navbar_section__mob-item" data-has-sub="true">
                <div class="index_navbar_section__mob-row">
                    <span class="index_navbar_section__mob-label">Ingredients</span>
                    <span class="index_navbar_section__mob-plus"><i class="bi bi-plus"></i></span>
                </div>
                <ul class="index_navbar_section__mob-sub" id="navbarIngredientMobile">
                    <li><a href="salicylic_acid.php">Salicylic Acid</a></li>
                    <li><a href="niacinamide.php">Niacinamide</a></li>
                    <li><a href="alpha_arbutin.php">Alpha Arbutin</a></li>
                    <li><a href="vitamin_c.php">Vitamin C</a></li>
                    <li><a href="retinol.php">Retinol</a></li>
                    <li><a href="hyaluronic_acid.php">Hyaluronic Acid</a></li>
                    <li><a href="ceramides.php">Ceramides</a></li>
                    <!-- <li><a href="#">Explore all products</a></li> -->
                </ul>
            </li>

            <!-- CONTACT US -->
            <li class="index_navbar_section__mob-item">
                <a href="contact.php" class="index_navbar_section__mob-plain">Contact Us</a>
            </li>

        </ul>
    </div>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/graphql-client.js?v=5"></script>

    <script>
        /* ─── Navbar scroll behaviour ─── */
        const navbar = document.getElementById('indexNavbar');
        window.addEventListener('scroll', () => {
            if (!navbar) return;
            if (window.scrollY > 10) {
                navbar.classList.add('index_navbar_section--scrolled');
            } else {
                navbar.classList.remove('index_navbar_section--scrolled');
            }
        }, {
            passive: true
        });

        /* ─── Mobile menu open/close ─── */
        const burger = document.getElementById('navBurger');
        const offcanvas = document.getElementById('navOffcanvas');
        const backdrop = document.getElementById('navBackdrop');
        const closeBtn = document.getElementById('navClose');

        function openMenu() {
            offcanvas.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            offcanvas.classList.remove('open');
            backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (burger && offcanvas && backdrop && closeBtn) {
            burger.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            backdrop.addEventListener('click', closeMenu);
        }

        /* ─── Mobile accordion (+ toggle for sub-services) ─── */
        document.querySelectorAll('.index_navbar_section__mob-item[data-has-sub]').forEach(item => {
            const row = item.querySelector('.index_navbar_section__mob-row');
            if (!row) return;
            row.addEventListener('click', () => {
                const isOpen = item.classList.contains('open');
                // Close all others
                document.querySelectorAll('.index_navbar_section__mob-item.open').forEach(el => el.classList.remove('open'));
                if (!isOpen) item.classList.add('open');
            });
        });

        async function loadNavbarCategories() {
            const desktopMenu = document.getElementById('navbarCategoryDesktop');
            const mobileMenu = document.getElementById('navbarCategoryMobile');
            const placeholder = '<li>No categories found</li>';

            if (desktopMenu) desktopMenu.innerHTML = '<li>Loading categories...</li>';
            if (mobileMenu) mobileMenu.innerHTML = '<li>Loading categories...</li>';

            try {
                const response = await fetch('fetch_categories.php');
                const result = await response.json();
                const categories = result.data?.categories?.items || [];

                if (!Array.isArray(categories) || categories.length === 0) {
                    if (desktopMenu) desktopMenu.innerHTML = placeholder;
                    if (mobileMenu) mobileMenu.innerHTML = placeholder;
                    return;
                }

                const itemsHtml = categories.map(category => {
                    const slug = encodeURIComponent(category.url_key || category.urlKey || category.name || '');
                    const label = category.name || 'Category';
                    const url = slug ? `category.php?category=${slug}` : '#';
                    return `<li><a href="${url}">${label}</a></li>`;
                }).join('');

                if (desktopMenu) desktopMenu.innerHTML = itemsHtml;
                if (mobileMenu) mobileMenu.innerHTML = itemsHtml;
            } catch (error) {
                console.error('Unable to load navbar categories:', error);
                if (desktopMenu) desktopMenu.innerHTML = placeholder;
                if (mobileMenu) mobileMenu.innerHTML = placeholder;
            }
        }

        loadNavbarCategories();
        function loadNavbarIngredients() {
            const desktopMenu = document.getElementById('navbarIngredientDesktop');
            const mobileMenu = document.getElementById('navbarIngredientMobile');
            const ingredients = [
                ['Salicylic Acid', 'salicylic_acid.php'],
                ['Niacinamide', 'niacinamide.php'],
                ['Alpha Arbutin', 'alpha_arbutin.php'],
                ['Vitamin C', 'vitamin_c.php'],
                ['Retinol', 'retinol.php'],
                ['Hyaluronic Acid', 'hyaluronic_acid.php'],
                ['Ceramides', 'ceramides.php'],
                ['Explore all products', 'products.php']
            ];
            const itemsHtml = ingredients.map(([label, url]) => `<li><a href="${url}">${label}</a></li>`).join('');

            if (desktopMenu) desktopMenu.innerHTML = itemsHtml;
            if (mobileMenu) mobileMenu.innerHTML = itemsHtml;
        }

        loadNavbarIngredients();

        /* ─── Hero keyboard accessibility ─── */
        const indexHero = document.getElementById('indexHero');
        if (indexHero) {
            indexHero.addEventListener('keydown', e => {
                if (e.key === 'Enter') window.location.href = 'product-detail.html';
            });
        }

        /* ─── Cart functionality ─── */
        function updateCartBadge() {
            const badge = document.getElementById('cartBadge');
            if (!badge || !window.NivisCart) return;
            badge.textContent = window.NivisCart.count();
        }

        function displayCart(cart) {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');

            if (!cartItems || !cartTotal) return; // Modal might not be loaded on all pages

            if (window.NivisCart) {
                const localCart = cart || window.NivisCart.toCart();

                if (localCart.items.length === 0) {
                    cartItems.innerHTML = '<p>Your cart is empty.</p>';
                    cartTotal.innerHTML = '';
                    return;
                }

                cartItems.innerHTML = localCart.items.map(item => `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div><strong>${item.name}</strong> (x${item.quantity})</div>
                        <div>Rs. ${Number(item.price || 0) * Number(item.quantity || 0)}</div>
                    </div>
                `).join('');

                cartTotal.innerHTML = `<strong>Total: Rs. ${localCart.total}</strong>`;
                return;
            }

            if (cart.items.length === 0) {
                cartItems.innerHTML = '<p>Your cart is empty.</p>';
                cartTotal.innerHTML = '';
                return;
            }

            cartItems.innerHTML = cart.items.map(item => `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${item.product.name}</strong> (x${item.quantity})
                    </div>
                    <div>₹${item.product.price * item.quantity}</div>
                </div>
            `).join('');

            cartTotal.innerHTML = `<strong>Total: ₹${cart.total}</strong>`;
        }

        // Update cart badge on page load
        updateCartBadge();
        window.addEventListener('nivis-cart:updated', updateCartBadge);
    </script>
