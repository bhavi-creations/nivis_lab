<?php include 'navbar.php'; ?>

<p class="contact_sub_heading">Home/Contact</p>

<!-- <div class="contact_section">
    <div class="container">
        <span class="badge bg-primary">mani</span>
        <div class="div">
            <h5>Call Service Temporarily Down</h5>
            <p>Our phone lines are currently unavailable. Please reach us via WhatsApp or email — we'll get back to you within a few hours.</p>
            <p>Last updated at <span>10:00 PM, 23 April 2026</span> </p>
        </div>
    </div>
</div> -->


<div class="alert-banner">
    <div class="alert-content container">
        <span class="icon">⚠️</span>
        <div>
            <h3>Call Service Temporarily Down</h3>
            <p>
                Our phone lines are currently unavailable. Please reach us via WhatsApp or email — we'll get back to you within a few hours.
            </p>
            <!-- <small>Last updated at 10:10 PM, 23 April 2026</small> -->
        </div>
    </div>
</div>

<div class="contact-section">
    <h2>Need to contact us?</h2>
    <p class="timing">Support timings: 10:00 AM TO 7:00 PM (Monday to Sunday)</p>

    <div class="contact-cards">

        <!-- Phone -->
        <div class="card">
            <div class="icon">📞</div>
            <h3>Phone</h3>
            <p>+91 99876543210</p>
            <button>CALL US</button>
        </div>

        <!-- WhatsApp -->
        <div class="card">
            <div class="icon">💬</div>
            <h3>Whatsapp</h3>
            <p>+91 99876543210</p>
            <button>WHATSAPP US</button>
        </div>

        <!-- Email -->
        <div class="card">
            <div class="icon">✉️</div>
            <h3>Email</h3>
            <p>nivis@e-commerce.com</p>
            <button>EMAIL US</button>
        </div>

    </div>

    <div class="gst">
        <h3>GST Address</h3>
        <p>
            Plot no 28, RTO Office Rd, behind lazza icecream shop, Ranga Rao Nagar, Kakinada, Vakalapudi, Andhra Pradesh 533003
        </p>
    </div>
</div>


<!-- CONTACT FORM START -->
<div class="contact-form-section">
    <div class="container">
        <div class="contact-form-box">

            <div class="form-heading">
                <h2>Send Us a Message</h2>
                <p>Have a question or need assistance? Fill out the form below and we'll get back to you shortly.</p>
            </div>

            <form action="contact-process.php" method="POST">

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <input type="text" name="name" class="form-control custom-input" placeholder="Your Name" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <input type="email" name="email" class="form-control custom-input" placeholder="Your Email" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <input type="text" name="phone" class="form-control custom-input" placeholder="Phone Number" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <input type="text" name="subject" class="form-control custom-input" placeholder="Subject" required>
                    </div>

                    <div class="col-12 mb-4">
                        <textarea name="message" rows="6" class="form-control custom-input" placeholder="Write Your Message Here..." required></textarea>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="contact-btn">
                            Submit Message
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- CONTACT FORM END -->



<?php include 'footer.php'; ?>