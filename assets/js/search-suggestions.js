(function() {
    const endpoint = 'search_suggestions.php';
    const minChars = 1;
    const debounceMs = 180;
    let activeController = null;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }

    function resultTemplate(item) {
        const icon = item.type === 'category' ? '<i class="bi bi-grid"></i>' : '';
        const image = item.image
            ? `<img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.title)}" onerror="this.style.display='none';">`
            : `<span class="nivis-search-result__icon">${icon}</span>`;

        return `
            <a class="nivis-search-result" href="${escapeHtml(item.url)}">
                <span class="nivis-search-result__media">${image}</span>
                <span class="nivis-search-result__body">
                    <span class="nivis-search-result__type">${escapeHtml(item.type)}</span>
                    <span class="nivis-search-result__title">${escapeHtml(item.title)}</span>
                    <span class="nivis-search-result__subtitle">${escapeHtml(item.subtitle || '')}</span>
                </span>
            </a>
        `;
    }

    function setStatus(resultsEl, message) {
        resultsEl.innerHTML = `<div class="nivis-search-empty">${escapeHtml(message)}</div>`;
        resultsEl.classList.add('is-open');
    }

    function hideResults(resultsEl) {
        resultsEl.classList.remove('is-open');
        resultsEl.innerHTML = '';
    }

    function initSearch(input, resultsEl) {
        if (!input || !resultsEl || input.dataset.searchReady === 'true') return;

        input.dataset.searchReady = 'true';
        let timer = null;

        async function runSearch() {
            const q = input.value.trim();

            if (q.length < minChars) {
                hideResults(resultsEl);
                return;
            }

            setStatus(resultsEl, 'Searching...');

            if (activeController) {
                activeController.abort();
            }

            activeController = new AbortController();

            try {
                const response = await fetch(`${endpoint}?q=${encodeURIComponent(q)}&limit=10`, {
                    signal: activeController.signal
                });
                const data = await response.json();
                const items = Array.isArray(data.items) ? data.items : [];

                if (!items.length) {
                    setStatus(resultsEl, 'No products or categories found.');
                    return;
                }

                resultsEl.innerHTML = items.map(resultTemplate).join('');
                resultsEl.classList.add('is-open');
            } catch (error) {
                if (error.name === 'AbortError') return;
                setStatus(resultsEl, 'Unable to load search results.');
            }
        }

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(runSearch, debounceMs);
        });

        input.addEventListener('focus', () => {
            if (input.value.trim().length >= minChars && resultsEl.innerHTML.trim()) {
                resultsEl.classList.add('is-open');
            }
        });

        input.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                const firstResult = resultsEl.querySelector('a');
                if (firstResult) {
                    event.preventDefault();
                    window.location.href = firstResult.href;
                }
            }

            if (event.key === 'Escape') {
                hideResults(resultsEl);
                input.blur();
            }
        });
    }

    function initNavbarSearch() {
        const searchButton = document.getElementById('navbarSearchButton');
        const panel = document.getElementById('navbarSearchPanel');
        const input = document.getElementById('navbarSearchInput');
        const results = document.getElementById('navbarSearchResults');
        const closeButton = document.getElementById('navbarSearchClose');

        initSearch(input, results);

        if (!searchButton || !panel || !input) return;

        function openPanel() {
            panel.classList.add('is-open');
            panel.setAttribute('aria-hidden', 'false');
            setTimeout(() => input.focus(), 30);
        }

        function closePanel() {
            panel.classList.remove('is-open');
            panel.setAttribute('aria-hidden', 'true');
            input.value = '';
            if (results) hideResults(results);
        }

        searchButton.addEventListener('click', openPanel);
        closeButton?.addEventListener('click', closePanel);

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && panel.classList.contains('is-open')) {
                closePanel();
            }
        });

        panel.addEventListener('click', event => {
            if (event.target === panel) {
                closePanel();
            }
        });
    }

    function initIndexSearch() {
        initSearch(
            document.getElementById('indexGuideSearchInput'),
            document.getElementById('indexGuideSearchResults')
        );
    }

    function initSkinthesisSearch() {
        const input = document.querySelector('.skinthesis-page .skinthesis-hero .search-box input');

        if (!input || input.dataset.searchReady === 'true') return;

        const sections = Array.from(document.querySelectorAll('.skinthesis-article-section'));
        const articles = Array.from(document.querySelectorAll('.skinthesis-page .skinthesis-card')).map(card => {
            const link = card.querySelector('.read-more-link');
            const title = card.querySelector('.card-title')?.textContent.trim() || 'Skincare article';
            const subtitle = card.querySelector('.card-text')?.textContent.replace(/\s+/g, ' ').trim() || '';

            return {
                column: card.closest('[class*="col-"]'),
                url: link?.getAttribute('href') || '#',
                searchText: `${title} ${subtitle}`.toLowerCase()
            };
        });

        if (!articles.length) return;
        input.dataset.searchReady = 'true';

        function filterArticles() {
            const query = input.value.trim().toLowerCase();

            articles.forEach(article => {
                const matches = !query || article.searchText.includes(query);
                if (article.column) article.column.hidden = !matches;
            });

            sections.forEach(section => {
                const hasVisibleArticle = Array.from(section.querySelectorAll('.skinthesis-card'))
                    .some(card => !card.closest('[class*="col-"]')?.hidden);
                section.hidden = Boolean(query) && !hasVisibleArticle;
            });

            return articles.filter(article => !article.column?.hidden);
        }

        input.addEventListener('input', filterArticles);
        input.addEventListener('keydown', event => {
            if (event.key === 'Enter' && input.value.trim()) {
                const firstMatch = filterArticles()[0];
                if (firstMatch?.url && firstMatch.url !== '#') {
                    event.preventDefault();
                    window.location.href = firstMatch.url;
                }
            }

            if (event.key === 'Escape') {
                input.value = '';
                filterArticles();
                input.blur();
            }
        });
    }

    function initContactEmailLink() {
        const emailCard = Array.from(document.querySelectorAll('.contact-cards .card')).find(card =>
            card.querySelector('h3')?.textContent.trim().toLowerCase() === 'email'
        );

        if (!emailCard) return;

        const emailText = emailCard.querySelector('p');
        const emailButton = emailCard.querySelector('button');
        const openEmail = () => {
            window.location.href = 'mailto:nivislabs@gmail.com';
        };

        emailButton?.addEventListener('click', openEmail);

        if (emailText) {
            emailText.tabIndex = 0;
            emailText.setAttribute('role', 'link');
            emailText.setAttribute('aria-label', 'Email Nivis Labs');
            emailText.style.cursor = 'pointer';
            emailText.addEventListener('click', openEmail);
            emailText.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openEmail();
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initNavbarSearch();
        initIndexSearch();
        initSkinthesisSearch();
        initContactEmailLink();
    });
})();
