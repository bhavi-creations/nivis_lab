<?php include 'navbar.php'; ?>


<!-- <div class="container pt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">How To Treat Acne?</a></li>
          
        </ol>
    </nav>
</div> -->



<section class="How_Tos_section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="How_Tos_header">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb justify-content-start small">
                    <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">How-Tos</li>
                </ol>
            </nav>
            <h1>How-Tos</h1>
        </div>

        <!-- Filter Nav -->
        <div class="How_Tos_nav" id="filter-nav">
            <a class="active" data-filter="all">View</a>
            <a data-filter="all">All posts</a>
            <a data-filter="acne">Acne</a>
            <a data-filter="brightening">Brightening</a>
        </div>

        <!-- Cards container - Limited to 3 cards per row -->
        <div class="row g-4" id="how-tos-container">
            <!-- Top 3 Cards will be injected by JS -->
        </div>
    </div>
</section>

<script>
    // Data setup (Links correctly added here)
    const posts = [{
            title: "How To Make Skin Glow?",
            cat: "brightening",
            img: "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800",
            desc: "Healthy, glowing skin isn't about chasing trends or changing your natural color. For us Indians, true radiance comes from consistent, science-backed skincare...",
            url: "how_to_make_skn_glow.php"
        },
        {
            title: "How To Treat Acne?",
            cat: "acne",
            img: "https://images.unsplash.com/photo-1612817288484-6f916006741a?w=800",
            desc: "Acne isn't limited to teenagers—adults experience it too. Managing breakouts requires a consistent, evidence-based routine with ingredients like Salicylic Acid...",
            url: "how_to_treat_acne.php"
        },
        {
            title: "How To Get Rid of Acne Sequelae (or Scars)?",
            cat: "acne",
            img: "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800",
            desc: "Acne scars—whether pits, raised bumps, or stubborn dark spots—can linger long after breakouts fade, especially in busy Indian cities where pollution is high...",
            url: "how_to_get_rid_of_acne_scars.php"
        }
    ];

    let currentCategory = "all";

    function renderUI() {
        const container = document.getElementById('how-tos-container');
        container.innerHTML = "";

        // Filter logic
        const filtered = currentCategory === "all" ? posts : posts.filter(p => p.cat === currentCategory);

        // Display Cards
        filtered.forEach(item => {
            container.innerHTML += `
            <div class="col-md-4">
                <div class="How_Tos_card">
                    <div class="How_Tos_img_wrapper">
                        <!-- Image click chesthe vellela link add cheshanu -->
                        <a href="${item.url}">
                            <img src="${item.img}" alt="${item.title}">
                        </a>
                    </div>
                    <div class="How_Tos_card_body">
                        <h3 class="How_Tos_card_title">${item.title}</h3>
                        <p class="How_Tos_card_text">${item.desc}</p>
                        <!-- Ikada '#' badulu '${item.url}' use cheshanu -->
                        <a href="${item.url}" class="How_Tos_read_more">READ MORE &rarr;</a>
                    </div>
                </div>
            </div>`;
        });

        // Handle empty results
        if (filtered.length === 0) {
            container.innerHTML = `<div class="col-12 text-center py-5"><p class="text-muted">No posts found in this category.</p></div>`;
        }
    }

    // Filter Logic
    document.getElementById('filter-nav').addEventListener('click', (e) => {
        if (e.target.tagName === 'A') {
            document.querySelectorAll('#filter-nav a').forEach(a => a.classList.remove('active'));
            e.target.classList.add('active');

            currentCategory = e.target.getAttribute('data-filter');
            renderUI();
        }
    });

    // Initial Load
    renderUI();
</script>


<?php include 'footer.php'; ?>