(function () {
    'use strict';
    const assessment = document.getElementById('skin-assessment');
    const matching = window.NivisSkinMatching;
    if (!assessment || !matching) return;

    const state = { skinType: '', concerns: [], photoUrl: '', photoVersion: 0, resultsVersion: 0 };
    const nextType = assessment.querySelector('#next-1');
    const nextConcerns = assessment.querySelector('#next-2');
    const fileInput = assessment.querySelector('#fileInput');
    const photoStatus = assessment.querySelector('#skin-photo-status');
    const resultStatus = assessment.querySelector('#skin-result-status');
    const grid = assessment.querySelector('#skin-result-products');
    let catalogPromise;

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[character]));
    }

    function showStep(step) {
        const target = String(step);
        if ((target === '2' || target === '3' || target === 'results') && !state.skinType) return;
        if ((target === '3' || target === 'results') && !state.concerns.length) return;
        state.resultsVersion += 1;
        assessment.querySelectorAll('[data-assessment-step]').forEach(panel => {
            panel.hidden = panel.dataset.assessmentStep !== target;
        });
        const heading = assessment.querySelector('[data-assessment-step="' + target + '"] h3');
        if (heading) heading.focus({ preventScroll: true });
        if (assessment.getBoundingClientRect().top < 0) assessment.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function loadCatalog() {
        if (!catalogPromise) {
            catalogPromise = (async function () {
                const controller = new AbortController();
                const timeout = setTimeout(() => controller.abort(), 30000);
                try {
                    const response = await fetch('fetch_category_products.php?category=all', {
                        headers: { Accept: 'application/json' }, signal: controller.signal
                    });
                    if (!response.ok) throw new Error('Product request failed');
                    const result = await response.json();
                    if (result.error || !Array.isArray(result.products)) throw new Error('Invalid product response');
                    return result.products;
                } finally {
                    clearTimeout(timeout);
                }
            }()).catch(error => {
                catalogPromise = null;
                throw error;
            });
        }
        return catalogPromise;
    }

    function productCard(match) {
        const product = match.product;
        const key = product.urlKey || product.url_key || product.sku || product.id || product.name;
        const name = product.name || 'Nivis product';
        let image = product.imageUrl || product.primaryImage || './assets/img/product.webp';
        try {
            const url = new URL(image, window.location.href);
            if (!['http:', 'https:'].includes(url.protocol)) image = './assets/img/product.webp';
        } catch (_) {
            image = './assets/img/product.webp';
        }
        const price = Number(product.priceNumber) || Number(String(product.price || '').replace(/,/g, '').replace(/[^0-9.]/g, ''));
        const tags = match.matchedConcerns.map(concern => '<span>' + escapeHtml(matching.concernRules[concern].label) + '</span>').join('');
        const skinNote = match.explicitSkinMatch ? '<p class="skin-product-type-note">Product information lists ' + escapeHtml(matching.skinLabels[state.skinType].toLowerCase()) + ' skin.</p>' : '';
        return '<article class="skin-product-card">' +
            '<a class="skin-product-image" href="product-detail.php?product=' + encodeURIComponent(key) + '"><img src="' + escapeHtml(image) + '" alt="' + escapeHtml(name) + '" loading="lazy"></a>' +
            '<div class="skin-product-content"><p class="skin-product-category">' + escapeHtml(product.type || 'Skin care') + '</p>' +
            '<h4>' + escapeHtml(name) + '</h4><div class="skin-product-tags">' + tags + '</div>' + skinNote +
            (price > 0 ? '<p class="skin-product-price">&#8377;' + price.toLocaleString('en-IN') + '</p>' : '') +
            '<a class="skin-assessment-primary" href="product-detail.php?product=' + encodeURIComponent(key) + '">VIEW PRODUCT <span aria-hidden="true">&rarr;</span></a></div></article>';
    }

    function renderPhoto() {
        const preview = assessment.querySelector('#skin-photo-preview');
        const image = assessment.querySelector('#skin-photo-image');
        preview.hidden = !state.photoUrl;
        if (state.photoUrl) image.src = state.photoUrl;
        else image.removeAttribute('src');
    }

    async function showResults() {
        if (!state.skinType || !state.concerns.length) return;
        showStep('results');
        const version = state.resultsVersion;
        const selected = state.concerns.slice();
        const type = state.skinType;
        const typeText = type === 'unsure' ? 'Skin type: Not sure' : matching.skinLabels[type] + ' skin';
        assessment.querySelector('#skin-result-summary').textContent = typeText + ' · ' + selected.map(key => matching.concernRules[key].label).join(' · ') + '. Matches use product information, with your skin type used to prioritise formulas.';
        renderPhoto();
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'true');
        resultStatus.textContent = 'Finding products for your selections…';
        const unmatched = assessment.querySelector('#skin-result-unmatched');
        unmatched.hidden = true;
        try {
            const products = await loadCatalog();
            if (version !== state.resultsVersion) return;
            const result = matching.recommend(products, type, selected);
            grid.innerHTML = result.matches.map(productCard).join('');
            grid.querySelectorAll('img').forEach(image => image.addEventListener('error', function () {
                if (!this.dataset.fallback) {
                    this.dataset.fallback = 'true';
                    this.src = './assets/img/product.webp';
                }
            }));
            resultStatus.textContent = result.matches.length ? result.matches.length + ' product' + (result.matches.length === 1 ? '' : 's') + ' matched your selections.' : 'No matching products are available for these selections right now. Edit your answers or explore our collection.';
            if (result.unmatched.length) {
                unmatched.textContent = 'No product match currently listed for: ' + result.unmatched.map(key => matching.concernRules[key].label).join(', ') + '. For product help, call 9666690910 or use Skin Care Help.';
                unmatched.hidden = false;
            }
        } catch (_) {
            if (version !== state.resultsVersion) return;
            resultStatus.innerHTML = '<p>We could not load your products. Please try again.</p><button type="button" class="skin-assessment-secondary" data-assessment-results>TRY AGAIN</button>';
        } finally {
            if (version === state.resultsVersion) grid.setAttribute('aria-busy', 'false');
        }
    }

    assessment.addEventListener('click', event => {
        const typeButton = event.target.closest('[data-skin-type]');
        if (typeButton) {
            state.skinType = typeButton.dataset.skinType;
            assessment.querySelectorAll('[data-skin-type]').forEach(button => {
                button.setAttribute('aria-pressed', String(button === typeButton));
            });
            nextType.disabled = false;
            return;
        }
        const concernButton = event.target.closest('[data-skin-concern]');
        if (concernButton) {
            const concern = concernButton.dataset.skinConcern;
            const index = state.concerns.indexOf(concern);
            const status = assessment.querySelector('#skin-concern-status');
            if (index >= 0) state.concerns.splice(index, 1);
            else if (state.concerns.length < 3) state.concerns.push(concern);
            else {
                status.textContent = 'You can select up to 3 concerns. Deselect one to choose another.';
                return;
            }
            concernButton.setAttribute('aria-pressed', String(state.concerns.includes(concern)));
            status.textContent = state.concerns.length + ' of 3 concerns selected.';
            nextConcerns.disabled = !state.concerns.length;
            return;
        }
        const navigation = event.target.closest('[data-assessment-next], [data-assessment-back]');
        if (navigation) {
            showStep(navigation.dataset.assessmentNext || navigation.dataset.assessmentBack);
            if (navigation.dataset.assessmentNext === '1') loadCatalog().catch(() => {});
            return;
        }
        if (event.target.closest('[data-assessment-results]')) showResults();
    });

    fileInput.addEventListener('change', () => {
        const version = ++state.photoVersion;
        const file = fileInput.files[0];
        if (!file) return;
        photoStatus.textContent = '';
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 4 * 1024 * 1024) {
            photoStatus.textContent = 'Choose a JPG, PNG or WebP image up to 4 MB.';
            fileInput.value = '';
            return;
        }
        photoStatus.textContent = 'Preparing your photo…';
        const photoUrl = URL.createObjectURL(file);
        const image = new Image();
        image.onload = () => {
            if (version !== state.photoVersion) {
                URL.revokeObjectURL(photoUrl);
                return;
            }
            if (state.photoUrl) URL.revokeObjectURL(state.photoUrl);
            state.photoUrl = photoUrl;
            photoStatus.textContent = 'Photo added. Your product matches are ready below.';
            showResults();
        };
        image.onerror = () => {
            URL.revokeObjectURL(photoUrl);
            if (version !== state.photoVersion) return;
            photoStatus.textContent = 'This image could not be opened. Please choose another photo.';
            fileInput.value = '';
        };
        image.src = photoUrl;
    });

    assessment.querySelector('#skin-photo-remove').addEventListener('click', () => {
        state.photoVersion += 1;
        if (state.photoUrl) URL.revokeObjectURL(state.photoUrl);
        state.photoUrl = '';
        fileInput.value = '';
        photoStatus.textContent = '';
        renderPhoto();
    });

    document.addEventListener('nivis:start-assessment', () => {
        showStep('1');
        assessment.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
}());
