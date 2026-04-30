<?php include 'navbar.php'; ?>



    <section class="skin_condition_section">
        <div class="container">
            <!-- Header Section -->
            <div class="skin_condition_header">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb justify-content-start small">
                        <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Skin Conditions</li>
                    </ol>
                </nav>
                <h1>Skin Conditions</h1>
            </div>

            <!-- Filter Navigation -->
            <div class="skin_condition_nav">
                <a onclick="filterContent('all', this)" class="active">View All</a>
                <a onclick="filterContent('acne', this)">Acne</a>
                <a onclick="filterContent('brightening', this)">Brightening</a>
                <a onclick="filterContent('eczema', this)">Eczema</a>
                <a onclick="filterContent('hydration', this)">Hydration</a>
                <a onclick="filterContent('lines', this)">Lines and Wrinkles</a>
                <a onclick="filterContent('pigmentation', this)">Pigmentation</a>
            </div>

            <!-- Cards Grid -->
            <div class="row g-4" id="cards-container">
                <!-- Card 1 -->
                <div class="col-md-4 card-item" data-category="acne">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&q=80&w=500" alt="Squalane">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Squalane</h3>
                            <p class="skin_condition_card_text">Squalane is a lightweight, non-greasy oil that works wonders for your skin by locking in moisture, protecting the skin barrier, and keeping your skin hydrated.</p>
                            <a href="squalane.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-4 card-item" data-category="acne">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&q=80&w=500" alt="Peptazin">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Peptazin™</h3>
                            <p class="skin_condition_card_text">Peptazin™ brings together three distinct peptides in one easy-to-use formula. Enjoy calmer, clearer skin as it helps fight blemishes, reduce irritation, and improve texture.</p>
                            <a href="Peptazin.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-4 card-item" data-category="lines">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&q=80&w=500" alt="Bakuchiol">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Bakuchiol</h3>
                            <p class="skin_condition_card_text">Bakuchiol is a plant-based alternative to retinol, offering similar anti-aging benefits without irritation. Derived from the Babchi plant, this gentle yet effective ingredient helps reduce lines.</p>
                            <a href="bakuchiol.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-md-4 card-item" data-category="eczema">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?auto=format&fit=crop&q=80&w=500" alt="Tasmanian Pepper">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Retinol</h3>
                            <p class="skin_condition_card_text">Retinol is a powerful Vitamin A derivative known for its ability to boost collagen, reduce wrinkles, clear acne, and improve skin texture. It works by speeding up skin renewal, unclogging pores, and ...</p>
                            <a href="retinol.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col-md-4 card-item" data-category="brightening">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=500" alt="NAG">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">How To Treat Acne?</h3>
                            <p class="skin_condition_card_text">Acne isn’t limited to teenagers—adults experience it too. Managing breakouts requires a consistent, evidence-based routine with ingredients like Salicylic Acid and Niacinamide.</p>
                            <a href="how_to_treat_acne.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="col-md-4 card-item" data-category="hydration">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?auto=format&fit=crop&q=80&w=500" alt="Peptide">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">How to Get Rid of Acne Sequelae (or Scars)?</h3>
                            <p class="skin_condition_card_text">Acne scars—whether pits, raised bumps, or stubborn dark spots—can linger long after breakouts fade, especially in busy Indian cities where pollution and stress slow healing.</p>
                            <a href="how_to_get_rid_of_acne_scars.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 7 -->
                <div class="col-md-4 card-item" data-category="brightening">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=500" alt="Vitamin C">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Tasmanian Pepper</h3>
                            <p class="skin_condition_card_text">Tasmanian Pepper is a powerful natural ingredient known for its ability to soothe irritation, reduce redness, and protect sensitive skin. Packed with antioxidants and anti-inflammatory compounds,</p>
                            <a href="tasmanian_pepper.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 8 -->
                <div class="col-md-4 card-item" data-category="hydration">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1552046122-03184de85e08?auto=format&fit=crop&q=80&w=500" alt="Hyaluronic Acid">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">N-Acetyl Glucosamine (NAG)</h3>
                            <p class="skin_condition_card_text">N-Acetyl Glucosamine (NAG) is a powerful yet gentle skincare ingredient that helps boost hydration, fade dark spots, smooth fine lines, and strengthen the skin barrier.</p>
                            <a href="n_acetyl_glucosamine.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- Card 9 -->
                <div class="col-md-4 card-item" data-category="lines">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?auto=format&fit=crop&q=80&w=500" alt="Retinol">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Peptide</h3>
                            <p class="skin_condition_card_text">Peptides are tiny but powerful building blocks that play a key role in keeping your skin firm, smooth, and youthful. Acting as messengers, peptides signal your skin to produce more collagen and elastin</p>
                            <a href="peptide.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 10 -->
                <div class="col-md-4 card-item" data-category="pigmentation">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=500" alt="Vitamin C">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Salicylic Acid</h3>
                            <p class="skin_condition_card_text">Salicylic Acid is a powerful skincare ingredient known for its ability to clear clogged pores, reduce acne, and smooth rough skin. Derived from willow bark, this Beta Hydroxy Acid (BHA) goes deep</p>
                            <a href="salicylic_acid.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>

                 <!-- Card 11 -->
                <div class="col-md-4 card-item" data-category="pigmentation">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=500" alt="Vitamin C">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Centella Asiatica (Cica)</h3>
                            <p class="skin_condition_card_text">Centella Asiatica, also known as Cica or Gotu Kola, is a powerful skin-healing ingredient that has been used for centuries to soothe irritation, reduce redness, and repair the skin barrier. </p>
                            <a href="centella_asiatica.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>


                 <!-- Card 12 -->
                <div class="col-md-4 card-item" data-category="pigmentation">
                    <div class="skin_condition_card">
                        <div class="skin_condition_img_wrapper">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=500" alt="Vitamin C">
                        </div>
                        <div class="skin_condition_card_body">
                            <h3 class="skin_condition_card_title">Niacinamide</h3>
                            <p class="skin_condition_card_text">Niacinamide is a skincare superhero that does it all—strengthening the skin barrier, reducing redness, minimizing pores, and keeping skin hydrated. Whether you're dealing with acne, dryness</p>
                            <a href="niacinamide.php" class="skin_condition_read_more">READ MORE &rarr;</a>
                        </div>
                    </div>
                </div>


                <!-- Add more cards for Page 2 here if needed -->
            </div>

            <!-- Pagination -->
            <div class="skin_condition_pagination" id="pagination">
                <a onclick="changePage(1)" class="skin_condition_page_item active" id="page-1">1</a>
                <a onclick="changePage(2)" class="skin_condition_page_item" id="page-2">2</a>
                <a onclick="changePage(2)" class="skin_condition_page_item skin_condition_page_next">Next &rarr;</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentCategory = 'all';
        let currentPage = 1;
        const cardsPerPage = 9;

        function filterContent(category, element) {
            // Active tab styling
            const navLinks = document.querySelectorAll('.skin_condition_nav a');
            navLinks.forEach(link => link.classList.remove('active'));
            element.classList.add('active');

            currentCategory = category;
            currentPage = 1; // Filter change reset page
            updateUI();
        }

        function changePage(page) {
            currentPage = page;
            updateUI();
            window.scrollTo({
                top: 400,
                behavior: 'smooth'
            }); // Scroll to top of section
        }

        function updateUI() {
            const cards = document.querySelectorAll('.card-item');
            let visibleCards = [];

            // Step 1: Filter by category
            cards.forEach(card => {
                if (currentCategory === 'all' || card.getAttribute('data-category') === currentCategory) {
                    visibleCards.push(card);
                } else {
                    card.style.display = 'none';
                }
            });

            // Step 2: Paginate filtered results[cite: 1]
            const start = (currentPage - 1) * cardsPerPage;
            const end = start + cardsPerPage;

            visibleCards.forEach((card, index) => {
                if (index >= start && index < end) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update pagination active state[cite: 1]
            document.querySelectorAll('.skin_condition_page_item').forEach(item => item.classList.remove('active'));
            const activePageBtn = document.getElementById(`page-${currentPage}`);
            if (activePageBtn) activePageBtn.classList.add('active');
        }

        // Initial load
        updateUI();
    </script>

<?php include 'footer.php'; ?>