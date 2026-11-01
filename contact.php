<?php include 'navbar.php'; ?>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
.new-contact-us-page{
    --primary:#006b7d;
    --primary-dark:#00556a;
    --text:#555d63;
    --heading:#075b6d;
    --border:#e8ecee;
    --light:#f8fafb;

    background:#fff;
    color:var(--text);
    font-family:Arial, Helvetica, sans-serif;
    padding:22px 0 0;
}

.new-contact-us-page *{
    box-sizing:border-box;
}

/* breadcrumb */
.new-contact-us-page .contact-breadcrumb{
    font-size:13px;
    color:#858d91;
    margin-bottom:34px;
}

.new-contact-us-page .contact-breadcrumb a{
    color:#7f898d;
    text-decoration:none;
}

.new-contact-us-page .contact-breadcrumb span{
    margin:0 7px;
}

/* Headings */
.new-contact-us-page .section-title,
.new-contact-us-page .form-title,
.new-contact-us-page .faq-title{
    color:var(--heading);
    font-family:Georgia, "Times New Roman", serif;
    font-weight:600;
}

.new-contact-us-page .section-title{
    font-size:31px;
    line-height:1.15;
    margin-bottom:10px;
}

.new-contact-us-page .section-description{
    font-size:15px;
    line-height:1.5;
    margin-bottom:22px;
    max-width:350px;
}

/* left information cards */
.new-contact-us-page .contact-info-card{
    border:1px solid var(--border);
    min-height:80px;
    padding:12px 15px;
    display:flex;
    align-items:center;
    gap:16px;
    background:#fff;
    margin-bottom:10px;
}

.new-contact-us-page .contact-icon{
    width:43px;
    height:43px;
    flex:0 0 43px;
    border-radius:50%;
    background:var(--heading);
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:17px;
}

.new-contact-us-page .contact-info-card h5{
    color:#28525d;
    font-size:15px;
    font-weight:700;
    margin:0 0 3px;
}

.new-contact-us-page .contact-info-card p{
    margin:0;
    font-size:13px;
    line-height:1.4;
    color:#777f83;
}

/* form */
.new-contact-us-page .contact-form-card{
    border:1px solid #e3e7e9;
    padding:28px 31px 21px;
    background:#fff;
    min-height:423px;
}

.new-contact-us-page .form-title{
    font-size:26px;
    margin-bottom:24px;
}

.new-contact-us-page .contact-label{
    color:#686e72;
    font-size:13px;
    margin-bottom:5px;
}

.new-contact-us-page .contact-label .required{
    color:#c70000;
}

.new-contact-us-page .form-control{
    height:37px;
    border:1px solid #d9dddf;
    border-radius:4px;
    box-shadow:none;
    font-size:14px;
}

.new-contact-us-page textarea.form-control{
    height:58px;
    resize:none;
}

.new-contact-us-page .form-control:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 2px rgba(0,107,125,.08);
}

.new-contact-us-page .submit-btn{
    width:100%;
    height:40px;
    border:0;
    border-radius:22px;
    background:linear-gradient(90deg,#08667a,#007d8a);
    color:#fff;
    font-size:13px;
    font-weight:700;
    letter-spacing:2px;
    transition:.25s ease;
}

.new-contact-us-page .submit-btn:hover{
    background:linear-gradient(90deg,#00576c,#006d78);
    transform:translateY(-1px);
}

/* right image */
.new-contact-us-page .office-image-wrap{
    height:377px;
    overflow:hidden;
    background:#f2f2f2;
}

.new-contact-us-page .office-image-wrap img{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    display:block;
}

/* faq */
.new-contact-us-page .faq-block{
    text-align:center;
    padding:65px 20px 29px;
    border-bottom:1px solid #f1f1f1;
}

.new-contact-us-page .faq-title{
    font-size:24px;
    margin-bottom:3px;
}

.new-contact-us-page .faq-text{
    font-size:12px;
    color:#92999c;
    margin-bottom:16px;
}

.new-contact-us-page .faq-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:18px;
    min-width:175px;
    height:38px;
    padding:0 20px;
    border-radius:22px;
    background:linear-gradient(90deg,#08667a,#007d8a);
    color:#fff;
    text-decoration:none;
    font-size:12px;
    font-weight:700;
    letter-spacing:1px;
}

.new-contact-us-page .faq-arrow{
    width:20px;
    height:20px;
    border:1px solid rgba(255,255,255,.55);
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:9px;
}

/* responsive */
@media (min-width:1200px){
    .new-contact-us-page .contact-main-row{
        align-items:flex-start;
    }

    .new-contact-us-page .contact-left-col{
        padding-right:25px;
    }

    .new-contact-us-page .contact-form-col{
        padding-left:9px;
        padding-right:18px;
    }

    .new-contact-us-page .contact-image-col{
        padding-left:0;
    }
}

@media (max-width:991.98px){
    .new-contact-us-page{
        padding-top:15px;
    }

    .new-contact-us-page .office-image-wrap{
        height:360px;
        margin-top:20px;
    }

    .new-contact-us-page .faq-block{
        padding-top:35px;
    }
}

@media (max-width:767.98px){
    .new-contact-us-page .contact-breadcrumb{
        margin-bottom:20px;
    }

    .new-contact-us-page .section-title{
        font-size:28px;
    }

    .new-contact-us-page .contact-form-card{
        margin-top:22px;
        padding:24px 20px;
        min-height:auto;
    }

    .new-contact-us-page .form-title{
        font-size:24px;
    }

    .new-contact-us-page .office-image-wrap{
        height:300px;
    }
}

@media (max-width:575.98px){
    .new-contact-us-page .section-title{
        font-size:25px;
    }

    .new-contact-us-page .contact-info-card{
        min-height:74px;
    }

    .new-contact-us-page .office-image-wrap{
        height:245px;
    }

    .new-contact-us-page .faq-title{
        font-size:21px;
    }
}
</style>


<section class="new-contact-us-page py-5" >

    <div class="container-fluid px-3 px-md-4">

        <!-- Breadcrumb -->
        <div class="contact-breadcrumb">
            <a href="#">Home</a>
            <span>›</span>
            <span>Contact Us</span>
        </div>

        <div class="row g-0 contact-main-row">

            <!-- LEFT CONTACT INFO -->
            <div class="col-lg-4 contact-left-col">

                <h1 class="section-title">Get in Touch</h1>

                <p class="section-description">
                    We're here to help.<br>
                    For product enquiries, feedback or support,<br>
                    feel free to reach out to us.
                </p>


                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div>
                        <h5>Call Us</h5>
                        <p>
                            <strong>+91 9666690910</strong><br>
                            Mon - Sat, 10:00 AM - 6:00 PM
                        </p>
                    </div>
                </div>


                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div>
                        <h5>Email Us</h5>
                        <p>
                            <strong>contact@nivislabs</strong><br>
                            We respond within 24 hours.
                        </p>
                    </div>
                </div>


                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>
                        <h5>Our Address</h5>
                        <p>
                            2-21/632/SF-08-08
Rushikonda,
Vishakahapatnam -530045.
                        </p>
                    </div>
                </div>


                <!-- <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="fa-solid fa-at"></i>
                    </div>

                    <div>
                        <h5>General Enquiries</h5>
                        <p>
                            For product, collaboration or<br>
                            distributor enquiries.
                        </p>
                    </div>
                </div> -->

            </div>


            <!-- CENTER FORM -->
            <div class="col-lg-4 contact-form-col">

                <div class="contact-form-card">

                    <h2 class="form-title">Send Us a Message</h2>

                    <form action="#" method="post">

                        <div class="mb-3">
                            <label class="contact-label">
                                Your Name <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="contact-label">
                                Email Address <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="contact-label">
                                Phone Number
                            </label>
                            <input
                                type="tel"
                                name="phone"
                                class="form-control"
                            >
                        </div>


                        <div class="mb-3">
                            <label class="contact-label">
                                Message <span class="required">*</span>
                            </label>
                            <textarea
                                name="message"
                                class="form-control"
                                required
                            ></textarea>
                        </div>


                        <button type="submit" class="submit-btn">
                            SUBMIT
                        </button>

                    </form>

                </div>

            </div>


            <!-- RIGHT IMAGE -->
            <div class="col-lg-4 contact-image-col">

                <div class="office-image-wrap">
                    <!-- Replace with your exact Nivis Labs office image -->
                    <img
                        src="./assets/img/FACE SERUM.png"
                        alt="Nivis Labs Office"
                    >
                </div>

            </div>

        </div>


        <!-- FAQ -->
        <!-- <div class="row">
            <div class="col-lg-5"></div>

            <div class="col-lg-4">
                <div class="faq-block">

                    <h2 class="faq-title">
                        Frequently Asked Questions
                    </h2>

                    <p class="faq-text">
                        Find quick answers to common queries.
                    </p>

                    <a href="faq.php" class="faq-btn">
                        VIEW FAQS

                        <span class="faq-arrow">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    </a>

                </div>
            </div>

            <div class="col-lg-3"></div>
        </div> -->

    </div>

</section>



<?php include 'footer.php'; ?>
