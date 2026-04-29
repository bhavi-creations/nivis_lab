<?php include 'navbar.php';?>
    <style>
        /* Skinthesis Section Custom Styling */
        .skinthesis_section {
            background-color: #fff;
        }

        .skinthesis_section .section-title {
            font-size: 1.8rem;
            color: #333;
            font-weight: 500;
        }

        .skinthesis-card {
            border: none;
            background: #fff;
            border-radius: 8px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            /* స్మూత్ యానిమేషన్ */
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            /* లైట్ షాడో */
        }

        .card-img-wrapper {
            overflow: hidden;
        }

        .skinthesis-card .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .skinthesis-card .card-title {
            font-size: 1.3rem;
            /* కార్డ్స్ టైటిల్స్ కోసం */
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .read-more-link {
            color: #d12e3f;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.85rem;
            display: inline-block;
            margin-top: 10px;
        }

        /* HOVER ANIMATION: Pop-up Type Effect */
        .skinthesis-card:hover {
            transform: translateY(-10px) scale(1.02);
            /* పైకి లేచి కొంచెం పెద్దది అవుతుంది */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            /* షాడో పెరుగుతుంది */
        }

        .skinthesis-card:hover .card-img-top {
            transform: scale(1.1);
            /* ఇమేజ్ కొంచెం జూమ్ అవుతుంది */
        }

        .read-more-link:hover {
            text-decoration: underline;
            color: #9c1c1c;
        }
    </style>
</head>

<body>

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
                <span href="#" class="index_img_section__badge mx-1">Advice</span>
                <span href="#" class="index_img_section__badge  mx-1">Conditions</span>
                <span href="#" class="index_img_section__badge mx-1">How-To</span>
                <span href="#" class="index_img_section__badge mx-1">Ingredients</span>
                <span href="#" class="index_img_section__badge mx-1">Index</span>
                <!-- <span class="index_img_section__badge">No Whitecast</span> -->
            </div>
        </div>
    </section>








    <section class="skinthesis_section py-5">
        <div class="container">

            <h2 class="text-center section-title mb-5">Dermatologist-Approved Answers to Your Everyday Questions</h2>
            <div class="row g-4 justify-content-center mb-5">

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Vitamin C">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">How Does Vitamin C Really Help Your Skin?</h4>
                            <p class="card-text text-muted small">
                                Vitamin C is one of the most researched and trusted ingredients in skincare — but
                                confusion around when to use it, how it works, and what to expect is common. In this
                                dermatologist-approved guide, we...
                            </p>
                            <a href="how_does_vitamin_c_really_help_your_skin.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1556228720-195a672e8a13?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Layer Skincare">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">How To Layer Skincare?</h4>
                            <p class="card-text text-muted small">
                                Layering skincare correctly helps your products work better together and protects your
                                skin's natural barrier. This dermatologist-approved guide explains the right order to
                                apply cleansers, serums,...
                            </p>
                            <a href="how_to_layer_skincare.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1596755389378-7d0afac6b856?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Skin Type">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">What Is Your Skin Type?</h4>
                            <p class="card-text text-muted small">
                                Understanding your skin type is the first step toward building a skincare routine that
                                actually works. Whether you have oily, dry, combination, sensitive, or normal skin,
                                knowing how your skin behaves helps...
                            </p>
                            <a href="what_is_your_skin_type.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

            </div>

            <h2 class="text-center section-title mb-5 mt-5">Practical Guides for Different Skin Concerns</h2>
            <div class="row g-4 justify-content-center mb-5">

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc2069?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Skin Glow">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">How To Make Skin Glow?</h4>
                            <p class="card-text text-muted small">
                                Healthy, glowing skin isn't about chasing trends or changing your natural color. For us
                                Indians, true radiance comes from consistent, science-backed skincare — focusing on
                                hydration, barrier strength,...
                            </p>
                            <a href="how_to_make_skin_glow.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1581057390911-3fb39eb2b896?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Treat Acne">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">How To Treat Acne?</h4>
                            <p class="card-text text-muted small">
                                Acne isn't limited to teenagers — adults experience it too. Managing breakouts requires
                                a consistent, evidence-based routine with ingredients like Salicylic Acid and
                                Niacinamide. This guide offers...
                            </p>
                            <a href="how_to_treat_acne.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Acne Scars">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">How To Get Rid of Acne Sequelae (or Scars)?</h4>
                            <p class="card-text text-muted small">
                                Acne scars — whether pits, raised bumps, or stubborn dark spots — can linger long after
                                breakouts fade, especially in busy Indian cities where pollution and stress slow
                                healing. But with the right skincare...
                            </p>
                            <a href="how_to_get_rid_of_acne_scars.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

            </div>

            <h2 class="text-center section-title mb-5 mt-5">Deep Dive into the Key Skincare Ingredients</h2>
            <div class="row g-4 justify-content-center">

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Squalane">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">Squalane</h4>
                            <p class="card-text text-muted small">
                                Squalane is a lightweight, non-greasy oil that works wonders for your skin by locking in
                                moisture, protecting the skin barrier, and keeping your complexion hydrated and
                                balanced. Derived from...
                            </p>
                            <a href="squalane.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Shea Butter">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">Shea Butter</h4>
                            <p class="card-text text-muted small">
                                Shea Butter is a natural skincare powerhouse known for its ability to deeply moisturize,
                                soothe dryness, and protect the skin barrier. Derived from the nuts of the African Shea
                                tree, this rich butter...
                            </p>
                            <a href="shea_butter.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skinthesis-card h-100">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&q=80&w=600"
                                class="card-img-top" alt="Peptazin">
                        </div>
                        <div class="card-body p-4">
                            <h4 class="card-title">Peptazin™</h4>
                            <p class="card-text text-muted small">
                                Peptazin™ brings together three distinct peptides in one easy-to-use formula. Enjoy
                                calmer, clearer skin as it helps fight blemishes, reduce irritation, and repair the look
                                of past spots—without harsh side...
                            </p>
                            <a href="Peptazin.php" class="read-more-link">READ MORE →</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>


<?php include 'footer.php'; ?>
</body>

</html>