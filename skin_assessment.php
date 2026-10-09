<section class="container my-5 ai_powered_skin_analysis" id="skin-assessment" aria-label="Skin assessment">
    <div class="skin-wrapper_index skin-assessment-panel">
        <span class="skin-label_index">SKIN ASSESSMENT</span>

        <div id="step-landing" class="skin-assessment-step skin-section_index" data-assessment-step="landing">
            <div class="row align-items-center g-4">
                <div class="col-md-7">
                    <p class="skin-assessment-kicker">NIVIS LABS SKIN CHECK</p>
                    <h2 class="skin-title_index">BUILD A ROUTINE AROUND YOUR SKIN NEEDS</h2>
                    <p class="skin-assessment-muted">Discover products based on your skin type and the concerns you want to focus on.</p>
                    <button type="button" class="skin-assessment-primary" data-assessment-next="1">START MY SKIN CARE <span aria-hidden="true">&rarr;</span></button>
                </div>
                <div class="col-md-5">
                    <div class="feature-box_index"><span class="feature-icon_index"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3"/></svg></span><div><strong>Know your skin</strong><br><small>Choose your skin type</small></div></div>
                    <div class="feature-box_index"><span class="feature-icon_index"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M12 21 3 12A5.5 5.5 0 0 1 12 5a5.5 5.5 0 0 1 9 7Z"/></svg></span><div><strong>Focus on your concerns</strong><br><small>Select up to three priorities</small></div></div>
                    <div class="feature-box_index"><span class="feature-icon_index"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16l1 14H3Z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/></svg></span><div><strong>Explore your matches</strong><br><small>Find products from our collection</small></div></div>
                </div>
            </div>
        </div>

        <div id="step-1" class="skin-assessment-step" data-assessment-step="1" hidden>
            <div class="skin-assessment-progress" aria-hidden="true"><span style="width: 33%"></span></div>
            <p class="skin-assessment-kicker">STEP 1 OF 3</p>
            <h3 tabindex="-1">What's your skin type?</h3>
            <p class="skin-assessment-muted" id="skin-type-help">Choose one that best describes your skin.</p>
            <div class="skin-assessment-options" role="group" aria-label="Skin type" aria-describedby="skin-type-help">
                <?php foreach (['oily' => 'Oily', 'dry' => 'Dry', 'combination' => 'Combination', 'sensitive' => 'Sensitive', 'normal' => 'Normal', 'unsure' => 'Not sure'] as $value => $label): ?>
                    <button type="button" class="skin-assessment-option" data-skin-type="<?php echo $value; ?>" aria-pressed="false"><?php echo $label; ?></button>
                <?php endforeach; ?>
            </div>
            <div class="skin-assessment-actions">
                <button type="button" class="skin-assessment-secondary" data-assessment-back="landing">Back</button>
                <button type="button" id="next-1" class="skin-assessment-primary" data-assessment-next="2" disabled>NEXT <span aria-hidden="true">&rarr;</span></button>
            </div>
        </div>

        <div id="step-2" class="skin-assessment-step" data-assessment-step="2" hidden>
            <div class="skin-assessment-progress" aria-hidden="true"><span style="width: 66%"></span></div>
            <p class="skin-assessment-kicker">STEP 2 OF 3</p>
            <h3 tabindex="-1">What are your skin concerns?</h3>
            <p class="skin-assessment-muted" id="skin-concern-help">Select up to 3 concerns.</p>
            <div class="skin-assessment-options skin-assessment-options--concerns" role="group" aria-label="Skin concerns" aria-describedby="skin-concern-help">
                <?php foreach (['acne' => 'Acne & breakouts', 'pigmentation' => 'Pigmentation', 'aging' => 'Fine lines & aging', 'dark-circles' => 'Dark circles', 'pores' => 'Open pores', 'sun' => 'Sun damage', 'dryness' => 'Dryness & dehydration'] as $value => $label): ?>
                    <button type="button" class="skin-assessment-option" data-skin-concern="<?php echo $value; ?>" aria-pressed="false"><?php echo htmlspecialchars($label); ?></button>
                <?php endforeach; ?>
            </div>
            <p class="skin-assessment-feedback" id="skin-concern-status" role="status" aria-live="polite"></p>
            <div class="skin-assessment-actions">
                <button type="button" class="skin-assessment-secondary" data-assessment-back="1">Back</button>
                <button type="button" id="next-2" class="skin-assessment-primary" data-assessment-next="3" disabled>NEXT <span aria-hidden="true">&rarr;</span></button>
            </div>
        </div>

        <div id="step-3" class="skin-assessment-step" data-assessment-step="3" hidden>
            <div class="skin-assessment-progress" aria-hidden="true"><span style="width: 100%"></span></div>
            <p class="skin-assessment-kicker">STEP 3 OF 3</p>
            <h3 tabindex="-1">Share your preference</h3>
            <p class="skin-assessment-muted">Add a skin photo to preview it alongside your product matches.</p>
            <label class="skin-assessment-upload" for="fileInput">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="18" rx="3"/><circle cx="8" cy="8" r="2"/><path d="m3 17 6-6 4 4 3-3 6 6"/></svg>
                <strong>Tap to add a skin photo</strong>
                <span>JPG, PNG or WebP &middot; Up to 4 MB</span>
                <input type="file" id="fileInput" class="skin-assessment-file" accept="image/jpeg,image/png,image/webp">
            </label>
            <p class="skin-assessment-muted small">Your photo stays in this browser. Product matches use your selected skin type and concerns.</p>
            <p class="skin-assessment-feedback" id="skin-photo-status" role="status" aria-live="polite"></p>
            <div class="skin-assessment-actions">
                <button type="button" class="skin-assessment-secondary" data-assessment-back="2">Back</button>
                <button type="button" class="skin-assessment-primary" data-assessment-results>GET MY NIVIS ROUTINE <span aria-hidden="true">&rarr;</span></button>
            </div>
            <button type="button" class="skin-assessment-skip" data-assessment-results>Skip photo &amp; see my products</button>
        </div>

        <div id="step-results" class="skin-assessment-step" data-assessment-step="results" hidden>
            <p class="skin-assessment-kicker">YOUR PERSONAL PRODUCT EDIT</p>
            <h3 tabindex="-1">Your Nivis skin care matches</h3>
            <p class="skin-assessment-muted" id="skin-result-summary"></p>
            <figure class="skin-assessment-preview" id="skin-photo-preview" hidden>
                <img id="skin-photo-image" alt="Your selected skin photo">
                <figcaption>Your photo preview <button type="button" class="skin-assessment-skip" id="skin-photo-remove">Remove photo</button></figcaption>
            </figure>
            <div id="skin-result-status" class="skin-assessment-feedback" role="status" aria-live="polite"></div>
            <div id="skin-result-products" class="skin-assessment-products" aria-busy="false"></div>
            <p id="skin-result-unmatched" class="skin-assessment-muted" hidden></p>
            <div class="skin-assessment-actions">
                <button type="button" class="skin-assessment-secondary" data-assessment-back="1">Edit my answers</button>
                <a class="skin-assessment-primary" href="products.php">EXPLORE ALL PRODUCTS</a>
            </div>
        </div>
    </div>
</section>
<script src="assets/js/skin-product-matching.js?v=1" defer></script>
<script src="assets/js/skin-assessment.js?v=2" defer></script>
