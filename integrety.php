<?php include 'navbar.php' ; ?>                                           

<body class="">

  <h1 class="page-title">Ingredients</h1>

  <!-- Filter tabs -->
  <div class="filter-bar" id="filterBar">
    <button class="filter-btn active" data-cat="all">View All</button>
    <button class="filter-btn" data-cat="Acne">Acne</button>
    <button class="filter-btn" data-cat="Brightening">Brightening</button>
    <button class="filter-btn" data-cat="Eczema">Eczema</button>
    <button class="filter-btn" data-cat="Hydration">Hydration</button>
    <button class="filter-btn" data-cat="Lines and Wrinkles">Lines and Wrinkles</button>
    <button class="filter-btn" data-cat="Pigmentation">Pigmentation</button>
  </div>
  <div class="container my-5">
    <!-- Grid -->
    <div class="ingredients-grid" id="grid"></div>

    <!-- Pagination -->
    <div class="pagination-wrap" id="paginationWrap"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // ── Data ──────────────────────────────────────────────────────────────
      const ITEMS_PER_PAGE = 9;

      const ingredients = [
        // Hydration
        {
          name: "Squalane",
          cat: "Hydration",
          link: "squalane.php",
          emoji: "🟡",
          bg: "#f5e6b0",
          desc: "A lightweight, non-greasy oil that works wonders for your skin by locking in moisture, protecting the skin barrier, and keeping your complexion hydrated and balanced. Derived from..."
        },
        {
          name: "Shea Butter",
          cat: "Hydration",
          link: "shea_butter.php",
          emoji: "🤍",
          bg: "#e8d5b0",
          desc: "Shea butter is a natural skincare powerhouse known for its ability to deeply moisturise, soothe dryness, and protect the skin barrier. Derived from the nuts of the African Shea tree, this rich butter is packed with..."
        },
        {
          name: "Peptazin™",
          cat: "Hydration",
          link: "Peptazin.php",
          emoji: "💧",
          bg: "#d0e8f5",
          desc: "Peptazin™ brings together three distinct peptides in one easy-to-use formula. Enjoy calmer, clearer skin as it helps fight blemishes, reduce irritation, and repair the look of past spots"
        },
        {
          name: "Ceramides",
          cat: "Hydration",
          link: "ceramides.php",
          emoji: "🫧",
          bg: "#c8e8f0",
          desc: "Ceramides are the building blocks of your skin barrier, working like glue to hold skin cells together and lock in moisture. These natural fats help keep your skin hydrated, smooth, and"
        },
        {
          name: "Bakuchiol",
          cat: "Hydration",
          link: "bakuchiol.php",
          emoji: "🔵",
          bg: "#b8d8f0",
          desc: "Bakuchiol is a plant-based alternative to retinol, offering similar anti-aging benefits without irritation. Derived from the Babchi plant, this gentle yet powerful ingredient helps boost"
        },

        // Acne
        {
          name: "Retinol",
          cat: "Acne",
          link: "retinol.php",
          emoji: "🔶",
          bg: "#f5c880",
          desc: "Retinol is a powerful Vitamin A derivative known for its ability to boost collagen, reduce wrinkles, clear acne, and improve skin texture. It works by speeding up skin renewal, unclogging pores, and evening out skin tone, making it a must-have"
        },
        {
          name: "Pentavitin®",
          cat: "Acne",
          link: "Pentavitin.php",
          emoji: "🌸",
          bg: "#e0b0e8",
          desc: "Pentavitin® is a powerful moisture magnet that keeps your skin hydrated for up to 72 hours, making it an essential ingredient for dry, sensitive, and dehydrated skin. Unlike regular moisturizers, Pentavitin® binds to the skin’s natural proteins,"
        },
        {
          name: "Tyrobright™",
          cat: "Acne",
          link: "tyrobrigh.php",
          emoji: "🟢",
          bg: "#b8e8c0",
          desc: "Discover Tyrobright™, a proprietary creation from the /PHD/ council. It acts like a gentle ‘melanin vacuum,’ targeting stubborn dark spots to deliver a brighter, more even complexion—"
        },
        {
          name: "Tasmanian Pepper",
          cat: "Acne",
          link: "tasmanian_pepper.php",
          emoji: "⚗️",
          bg: "#d0f0d0",
          desc: "Tasmanian Pepper is a powerful natural ingredient known for its ability to soothe irritation, reduce redness, and protect sensitive skin. Packed with antioxidants and anti-inflammatory"
        },

        // Brightening
        {
          name: "N-Acetyl Glucosamine (NAG)",
          cat: "Brightening",
          link: "n_acetyl_glucosamine.php",
          emoji: "💜",
          bg: "#d8c8f0",
          desc: "N-Acetyl Glucosamine (NAG) is a powerful yet gentle skincare ingredient that helps boost hydration, fade dark spots, smooth fine lines, and strengthen the skin barrier. Naturally found in the"
        },
        {
          name: "Peptide",
          cat: "Brightening",
          link: "peptide.php",
          emoji: "🟠",
          bg: "#f5d8a0",
          desc: "Peptides are tiny but powerful building blocks that play a key role in keeping your skin firm, smooth, and youthful. Acting as messengers, peptides signal your skin to produce more"
        },
        {
          name: "Salicylic Acid",
          cat: "Brightening",
          link: "salicylic_acid.php",
          emoji: "🌿",
          bg: "#c8f0d8",
          desc: "Salicylic Acid is a powerful skincare ingredient known for its ability to clear clogged pores, reduce acne, and smooth rough skin. Derived from willow bark, this Beta Hydroxy Acid (BHA)"
        },

        // Lines and Wrinkles
        {
          name: "Centella Asiatica (Cica)",
          cat: "Lines and Wrinkles",
          link: "centella_asiatica.php ",
          emoji: "🟡",
          bg: "#f0e8b0",
          desc: "Centella Asiatica, also known as Cica or Gotu Kola, is a powerful skin-healing ingredient that has been used for centuries to soothe irritation, reduce redness, and repair the skin barrier."
        },
        {
          name: "Panthenol",
          cat: "Lines and Wrinkles",
          link: "Panthenol.php",
          emoji: "🔗",
          bg: "#e0d8f0",
          desc: "Panthenol, a form of Vitamin B5, is a powerhouse ingredient that hydrates, soothes, and strengthens both skin and hair. Acting as a moisture magnet, it locks in hydration, softens"
        },
        {
          name: "Polyglutamic Acid (PGA)",
          cat: "Lines and Wrinkles",
          link: "polyglutamic_acid.php",
          emoji: "⚡",
          bg: "#f0f0b8",
          desc: "Polyglutamic Acid (PGA) is a powerful hydration booster that helps your skin retain moisture, stay plump, and feel ultra-soft. Known for its ability to hold up to 5,000 times its weight in water, PGA"
        },

        // Pigmentation
        {
          name: "Hyaluronic Acid",
          cat: "Pigmentation",
          link: "hyaluronic_acid.php",
          emoji: "❄️",
          bg: "#d0f0f8",
          desc: "Hyaluronic Acid is a powerful moisture-binding ingredient that keeps your skin hydrated, plump, and youthful. Naturally found in the body, it acts like a sponge, holding up to 1,000 times its"
        },
        {
          name: "Coenzyme Q10",
          cat: "Pigmentation",
          link: "coenzyme_q10.php",
          emoji: "✨",
          bg: "#f0f0d0",
          desc: "Coenzyme Q10 (CoQ10) is a powerful antioxidant that helps energize your skin, reduce fine lines and wrinkles, fight free radicals, and improve elasticity. Naturally found in the body, CoQ10"
        },
        {
          name: "Ferulic Acid",
          cat: "Pigmentation",
          link: "ferulic_acid.php",
          emoji: "🌶️",
          bg: "#d8f0d8",
          desc: "Ferulic Acid is a powerful antioxidant that helps protect your skin from sun damage, pollution, and premature aging. Found naturally in plants like apples, oranges, and rice, this skincare hero"
        },

        // Eczema
        {
          name: "Niacinamide",
          cat: "Eczema",
          link: "niacinamide.php",
          emoji: "🌾",
          bg: "#f0e8c8",
          desc: "Niacinamide is a skincare superhero that does it all—strengthening the skin barrier, reducing redness, minimizing pores, and keeping skin hydrated. Whether you're dealing with acne,"
        },
        {
          name: "Vitamin C (Ascorbic Acid)",
          cat: "Eczema",
          link: "vitamin_c.php",
          emoji: "🍃",
          bg: "#c8f0c8",
          desc: "Vitamin C, also known as Ascorbic Acid, is a powerhouse ingredient in skincare, loved for its ability to brighten skin, fade dark spots, and protect against environmental damage. But how does it work, and what’s the best way to use it"
        },
        {
          name: "Alpha Arbutin",
          cat: "Eczema",
          link: "alpha_arbutin.php",
          emoji: "🌼",
          bg: "#f8f0c0",
          desc: "Struggling with dark spots, uneven skin tone, or hyperpigmentation? Alpha Arbutin might be the solution you need! This powerful yet gentle skincare ingredient works to brighten your complexion by reducing melanin product"
        },
      ];

      let activeCategory = "all";
      let currentPage = 1;

      function filtered() {
        return activeCategory === "all" ?
          ingredients :
          ingredients.filter(i => i.cat === activeCategory);
      }

      function totalPages() {
        return Math.max(1, Math.ceil(filtered().length / ITEMS_PER_PAGE));
      }

      function renderGrid() {
        const grid = document.getElementById("grid");
        const data = filtered();
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const slice = data.slice(start, start + ITEMS_PER_PAGE);

        grid.innerHTML = "";

        if (slice.length === 0) {
          grid.innerHTML = `<div class="empty-state">No ingredients found for this category.</div>`;
          return;
        }

        slice.forEach((item, idx) => {
          const card = document.createElement("div");
          card.className = "ing-card";
          card.style.animationDelay = (idx * 40) + "ms";

          card.innerHTML = `
        <a href="${item.link}" style="text-decoration:none; color:inherit; display:block; height:100%;">
          
          <div class="ing-card__img-placeholder" style="background:${item.bg}">
            ${item.emoji}
          </div>

          <div class="ing-card__body">
            <div class="ing-card__name">${item.name}</div>
            <div class="ing-card__desc">${item.desc}</div>
            <div class="ing-card__link">Read More</div>
          </div>

        </a>
      `;

          grid.appendChild(card);
        });
      }

      function renderPagination() {
        const wrap = document.getElementById("paginationWrap");
        const total = totalPages();
        wrap.innerHTML = "";

        if (total <= 1) return;

        const prev = document.createElement("button");
        prev.className = "pg-btn pg-arrow";
        prev.textContent = "‹";
        prev.disabled = currentPage === 1;
        prev.onclick = () => goTo(currentPage - 1);
        wrap.appendChild(prev);

        const pages = getPageRange(currentPage, total);
        pages.forEach(p => {
          if (p === "…") {
            const el = document.createElement("span");
            el.style.cssText = "padding:0 4px;color:var(--muted);font-size:13px;line-height:34px";
            el.textContent = "…";
            wrap.appendChild(el);
          } else {
            const btn = document.createElement("button");
            btn.className = "pg-btn" + (p === currentPage ? " active" : "");
            btn.textContent = p;
            btn.onclick = () => goTo(p);
            wrap.appendChild(btn);
          }
        });

        const next = document.createElement("button");
        next.className = "pg-btn pg-arrow";
        next.textContent = "›";
        next.disabled = currentPage === total;
        next.onclick = () => goTo(currentPage + 1);
        wrap.appendChild(next);
      }

      function getPageRange(cur, total) {
        if (total <= 5) return Array.from({
          length: total
        }, (_, i) => i + 1);
        if (cur <= 3) return [1, 2, 3, 4, "…", total];
        if (cur >= total - 2) return [1, "…", total - 3, total - 2, total - 1, total];
        return [1, "…", cur - 1, cur, cur + 1, "…", total];
      }

      function goTo(page) {
        currentPage = page;
        renderGrid();
        renderPagination();
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      }

      document.getElementById("filterBar").addEventListener("click", e => {
        const btn = e.target.closest(".filter-btn");
        if (!btn) return;
        document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        activeCategory = btn.dataset.cat;
        currentPage = 1;
        renderGrid();
        renderPagination();
      });

      renderGrid();
      renderPagination();
    </script>
  </div>
  <?php include 'footer.php' ; ?>
</body>

</html>