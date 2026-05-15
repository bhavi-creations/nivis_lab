<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 text-center">
                <h6>Information</h6>
                <ul>
                    <li><a href="tracking.php">Track Your Order</a></li>
                    <li><a href="our-story.php">The /PHD/ Story</a></li>
                    <li><a href="our_team.php">The /PHD/ Council</a></li>
                    -->
                    <li><a href="skinthesis.php">Skinthesis</a></li>
                    <li><a href="reward.php">Rewards</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4 text-center">
                <h6>Important Links</h6>
                <ul>
                    <li><a href="shipping_returns.php">Shipping & Returns</a></li>
                    <li><a href="terms_condition.php">Terms & Conditions</a></li>
                    <li><a href="privacy_policy.php">Privacy Policy</a></li>
                    <li><a href="refund.php">Refund Policy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="build_phd.php">Let's Build /PHD/</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4 text-center">
                <h6>You will love us here</h6>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-12">
                <div class="footer-logo">/NIVIS LAB/</div>
                <!-- <p class="text-uppercase small tracking-widest">Proven Honest Derma</p>
                    <div class="copyright">Copyright © 2026 /PHD/. All rights reserved.</div> -->
                <!-- <img src="./assets/img/logo_1 (1).png" alt="" style="width: 200px;"> -->
            </div>
        </div>
    </div>
</footer>



<div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer">
    <div class="offcanvas-header border-bottom">
        <h5>Mee Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="cartContent">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelector('.video_section_add_btn').addEventListener('click', function() {
        const cartBody = document.getElementById('cartContent');
        cartBody.innerHTML = `
                <div class="d-flex align-items-center mb-3">
                    <p>Product Added!</p>
                </div>
            `;
        // Migatha logic ikkada add cheyandi
    });
</script>
</body>