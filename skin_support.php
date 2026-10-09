<div class="nivis-support-dock" aria-label="Connect with Nivis Labs">
    <nav class="nivis-support-social" aria-label="Social media">
        <a class="nivis-support-icon nivis-support-instagram" href="https://www.instagram.com/nivislabs/" target="_blank" rel="noopener noreferrer" aria-label="Nivis Labs on Instagram" title="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
        <a class="nivis-support-icon nivis-support-facebook" href="https://www.facebook.com/nivislabs.co/" target="_blank" rel="noopener noreferrer" aria-label="Nivis Labs on Facebook" title="Facebook"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14.5 22v-9h3l.5-4h-3.5V6.5c0-1.2.4-2 2.1-2H18V1.2C17.3 1.1 16.4 1 15.2 1 12 1 10 3 10 6.3V9H7v4h3v9z"/></svg></a>
        <a class="nivis-support-icon nivis-support-whatsapp" href="https://wa.me/919666690910" target="_blank" rel="noopener noreferrer" aria-label="Chat with Nivis Labs on WhatsApp" title="WhatsApp"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.7a9 9 0 0 1-13.2 8L3 21l1.3-4.6A9 9 0 1 1 21 11.7Z"/><path d="m8 7 2 3-1.3 1.3a9 9 0 0 0 4 4L14 14l3 2c-1 2-2.5 2.4-5 1.1a12 12 0 0 1-5.1-5.2C5.6 9.5 6.2 8 8 7Z"/></svg></a>
    </nav>
    <button type="button" class="nivis-support-launcher" id="nivis-support-toggle" aria-expanded="false" aria-controls="nivis-support-panel">
        <span class="nivis-support-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M20 11a8 8 0 0 1-8 8H8l-5 3 1.5-6A8 8 0 1 1 20 11Z"/><path d="M7.5 10h9M7.5 14h6"/></svg></span>
        <span class="nivis-support-caption">Skin Care Help</span>
    </button>
</div>

<section class="nivis-support-panel" id="nivis-support-panel" role="dialog" aria-modal="false" aria-labelledby="nivis-support-title" hidden>
    <header class="nivis-support-header">
        <div><p>NIVIS LABS</p><h2 id="nivis-support-title" tabindex="-1">Your Skin Care Guide</h2></div>
        <button type="button" id="nivis-support-close" aria-label="Close skin care help"><span aria-hidden="true">&times;</span></button>
    </header>
    <div class="nivis-support-body">
        <p class="nivis-support-welcome">Welcome to Nivis Labs. Let us help you explore products and build your routine.</p>
        <button type="button" class="nivis-support-primary" id="nivis-support-start">Explore product help <span aria-hidden="true">&rarr;</span></button>
        <div id="nivis-support-questions" hidden>
            <p class="nivis-support-prompt">What would you like to know?</p>
            <div class="nivis-support-question-list" role="group" aria-label="Product questions">
                <button type="button" data-support-question="match" aria-pressed="false">How do I find products for my skin?</button>
                <button type="button" data-support-question="details" aria-pressed="false">Where can I see ingredients and product details?</button>
                <button type="button" data-support-question="order" aria-pressed="false">How can I order a product?</button>
            </div>
            <div class="nivis-support-answer" id="nivis-support-answer" role="status" aria-live="polite" hidden></div>
        </div>
    </div>
    <div class="nivis-support-contact" id="nivis-support-contact" hidden>
        <p>Need a little more help? Connect with our team.</p>
        <div><a href="tel:+919666690910">Contact</a><a href="mailto:nivislabs@gmail.com">Email</a></div>
    </div>
</section>
<script src="assets/js/skin-support.js?v=1" defer></script>
