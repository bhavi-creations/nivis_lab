<?php include 'navbar.php'; ?>

<section class="climate-adaptive-skincaare">
    <div class="climate-adaptive-skincaare__inner">

        <div class="climate-adaptive-skincaare__eyebrow">
            <span></span>
            <p>SIGNATURE INNOVATION</p>
            <span></span>
        </div>

        <h2 class="climate-adaptive-skincaare__title">
            Climate-Adaptive Skincare
        </h2>

        <p class="climate-adaptive-skincaare__description">
            Science-led formulations for India’s diverse climate conditions.
        </p>

    </div>
</section>

<style>
.climate-adaptive-skincaare{
    width:100%;
    position:relative;
    overflow:hidden;

    padding:55px 20px;

    background:#ffffff;

    display:flex;
    align-items:center;
    justify-content:center;

    text-align:center;
}


/* INNER */
.climate-adaptive-skincaare__inner{
    width:100%;
    max-width:1100px;

    margin:0 auto;

    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
}


/* TOP SMALL HEADING */
.climate-adaptive-skincaare__eyebrow{
    display:flex;
    align-items:center;
    justify-content:center;

    width:100%;

    gap:24px;

    margin-bottom:20px;
}

.climate-adaptive-skincaare__eyebrow span{
    display:block;

    width:85px;
    height:1px;

    background:linear-gradient(
        90deg,
        transparent,
        #075563
    );
}

.climate-adaptive-skincaare__eyebrow span:last-child{
    background:linear-gradient(
        90deg,
        #075563,
        transparent
    );
}

.climate-adaptive-skincaare__eyebrow p{
    margin:0;

    color:#092f46;

    font-family:'Inter', sans-serif;

    font-size:clamp(9px, 1vw, 14px) !important;
    font-weight:500;

    line-height:1;

    letter-spacing:clamp(1.5px, .35vw, 5px);

    text-transform:uppercase;
}


/* MAIN TITLE */
.climate-adaptive-skincaare__title{
    margin:0;

    color:#075563;

    font-family:'Manrope', sans-serif;

    max-width:100%;
    font-size:clamp(30px, calc(5vw + 16px), 68px) !important;
    font-weight:600;

    line-height:1.08;

    letter-spacing:clamp(-1.8px, -.12vw, -.5px);
}


/* DESCRIPTION */
.climate-adaptive-skincaare__description{
    margin:16px 0 0;

    color:#5f6f78;

    font-family:'Manrope', sans-serif;

    font-size:clamp(14px, calc(1.5vw + 4px), 22px) !important;
    font-weight:500;

    line-height:1.55;
}


/* TABLET */
@media(max-width:991.98px){

    .climate-adaptive-skincaare{
        padding:45px 20px;
    }

    .climate-adaptive-skincaare__eyebrow{
        gap:18px;
        margin-bottom:17px;
    }

    .climate-adaptive-skincaare__eyebrow span{
        width:65px;
    }

}


/* MOBILE */
@media(max-width:767.98px){

    .climate-adaptive-skincaare{
        padding:38px 16px;
    }

    .climate-adaptive-skincaare__eyebrow{
        gap:11px;
        margin-bottom:15px;
    }

    .climate-adaptive-skincaare__eyebrow span{
        width:35px;
    }

    .climate-adaptive-skincaare__description{
        max-width:560px;

        margin-top:13px;

        line-height:1.55;
    }

}


/* SMALL MOBILE */
@media(max-width:480px){

    .climate-adaptive-skincaare{
        padding:32px 14px;
    }

    .climate-adaptive-skincaare__eyebrow{
        gap:8px;
    }

    .climate-adaptive-skincaare__eyebrow span{
        width:22px;
    }

}
</style>

<!-- CLIMATE ADAPTIVE SKINCARE SECOND SECTION START -->
<section class="climate-adaptive-skincaare-second-section">
    <div class="container">

        <!-- SECTION HEADING -->
        <div class="climate-second-heading">
            <span class="heading-line"></span>
            <h2>Explore by Climate</h2>
            <span class="heading-line"></span>
        </div>

        <!-- CLIMATE CARDS -->
        <div class="row g-4 climate-cards-row">

            <!-- TROPICAL & HUMID -->
            <div class="col-lg-4 col-md-6">
                <div class="climate-card climate-card-tropical">

                    <div class="climate-image-wrap">
                        <img
                            src="assets/img/tropical-climate.jpg"
                            alt="Tropical and Humid Climate"
                            class="img-fluid"
                        >
                    </div>

                    <div class="climate-card-content">
                        <div class="climate-copy">

                            <div class="climate-title-row">
                                <div class="climate-icon tropical-icon">
                                    <i class="bi bi-tree-fill"></i>
                                </div>

                                <h3>Tropical &amp; Humid</h3>
                            </div>

                            <h5>High heat and humidity.</h5>

                            <p>
                                Lightweight, sweat-friendly care
                                that keeps skin fresh and balanced.
                            </p>

                            <a href="products.php?category=climate-tropical-humid" class="climate-btn">
                                Explore Solutions
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>
                    </div>

                </div>
            </div>

            <!-- HOT & DRY -->
            <div class="col-lg-4 col-md-6">
                <div class="climate-card climate-card-hot">

                    <div class="climate-image-wrap">
                        <img
                            src="assets/img/hot-dry-climate.jpg"
                            alt="Hot and Dry Climate"
                            class="img-fluid"
                        >
                    </div>

                    <div class="climate-card-content">
                        <div class="climate-copy">

                            <div class="climate-title-row">
                                <div class="climate-icon hot-icon">
                                    <i class="bi bi-sun"></i>
                                </div>

                                <h3>Hot &amp; Dry</h3>
                            </div>

                            <h5>High heat with low humidity.</h5>

                            <p>
                                Deep hydration and barrier support
                                to prevent dryness and moisture loss.
                            </p>

                            <a href="products.php?category=climate-hot-dry" class="climate-btn">
                                Explore Solutions
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>
                    </div>

                </div>
            </div>

            <!-- COLD & DRY -->
            <div class="col-lg-4 col-md-6 mx-md-auto mx-lg-0">
                <div class="climate-card climate-card-cold">

                    <div class="climate-image-wrap">
                        <img
                            src="assets/img/cold-dry-climate.jpg"
                            alt="Cold and Dry Climate"
                            class="img-fluid"
                        >
                    </div>

                    <div class="climate-card-content">
                        <div class="climate-copy">

                            <div class="climate-title-row">
                                <div class="climate-icon cold-icon">
                                    <i class="bi bi-snow"></i>
                                </div>

                                <h3>Cold &amp; Dry</h3>
                            </div>

                            <h5>Low temperature and dry air.</h5>

                            <p>
                                Rich nourishment and barrier care
                                to keep skin soft, smooth and protected.
                            </p>

                            <a href="products.php?category=climate-cold-dry" class="climate-btn">
                                Explore Solutions
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- PROTECTION BAR -->
        <div class="climate-protection-bar">

            <div class="row align-items-center g-0">

                <div class="col-lg-3 col-md-12">
                    <div class="protection-main-title">
                        Built-In Protection
                        <br>
                        Across All Climates
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="protection-item">

                        <!-- <div class="protection-icon uv-icon">
                            <i class="bi bi-shield-check"></i>
                        </div> -->

                        <div>
                            <h4>UV Protection</h4>
                            <p>
                                Broad-spectrum defence
                                <br>
                                every day
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="protection-item">

                        <!-- <div class="protection-icon antioxidant-icon">
                            <i class="bi bi-leaf"></i>
                        </div> -->

                        <div>
                            <h4>Antioxidant Support</h4>
                            <p>
                                Helps combat
                                <br>
                                environmental stress
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="protection-item protection-item-last">

                        <!-- <div class="protection-icon pollution-icon">
                            <i class="bi bi-buildings"></i>
                        </div> -->

                        <div>
                            <h4>Pollution Defence</h4>
                            <p>
                                Protects skin from
                                <br>
                                daily urban exposure
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</section>


<style>
/* =========================================================
   CLIMATE ADAPTIVE SKINCARE SECOND SECTION
========================================================= */

.climate-adaptive-skincaare-second-section{
    width:100%;
    position:relative;
    overflow:hidden;
    padding:45px 0 50px;
    background:#ffffff;
    font-family:'Manrope',sans-serif;
}


/* SECTION HEADING */
.climate-adaptive-skincaare-second-section .climate-second-heading{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:26px;
    margin-bottom:28px;
}

.climate-adaptive-skincaare-second-section .climate-second-heading h2{
    margin:0;
    color:#075563;
    font-size:clamp(30px,3vw,42px);
    font-weight:700;
    line-height:1.1;
    text-align:center;
}

.climate-adaptive-skincaare-second-section .heading-line{
    width:110px;
    height:1px;
    background:linear-gradient(
        90deg,
        transparent,
        #075563
    );
}

.climate-adaptive-skincaare-second-section .heading-line:last-child{
    background:linear-gradient(
        90deg,
        #075563,
        transparent
    );
}


/* CARDS */
.climate-adaptive-skincaare-second-section .climate-card{
    width:100%;
    height:100%;
    position:relative;
    overflow:hidden;
    border-radius:22px;
    border:1px solid rgba(9,47,70,.10);
    background:#ffffff;
    box-shadow:0 10px 26px rgba(9,47,70,.08);
    transition:
        transform .3s ease,
        box-shadow .3s ease;
}

.climate-adaptive-skincaare-second-section .climate-card:hover{
    transform:translateY(-4px);
    box-shadow:0 16px 34px rgba(9,47,70,.12);
}


/* IMAGE */
.climate-adaptive-skincaare-second-section .climate-image-wrap{
    width:100%;
    height:165px;
    overflow:hidden;
}

.climate-adaptive-skincaare-second-section .climate-image-wrap img{
    display:block;
    width:100%;
    height:100%;
    object-fit:cover;
}


/* CARD CONTENT */
.climate-adaptive-skincaare-second-section .climate-card-content{
    position:relative;
    min-height:220px;
    padding:24px 24px 22px;
}


/* CARD BACKGROUNDS */
.climate-adaptive-skincaare-second-section .climate-card-tropical .climate-card-content{
    background:linear-gradient(
        180deg,
        #ffffff 0%,
        #f4fbf9 70%,
        #e5f7f2 100%
    );
}

.climate-adaptive-skincaare-second-section .climate-card-hot .climate-card-content{
    background:linear-gradient(
        180deg,
        #ffffff 0%,
        #fffaf1 70%,
        #fff1d8 100%
    );
}

.climate-adaptive-skincaare-second-section .climate-card-cold .climate-card-content{
    background:linear-gradient(
        180deg,
        #ffffff 0%,
        #f5faff 70%,
        #e6f2fc 100%
    );
}


/* COPY */
.climate-adaptive-skincaare-second-section .climate-copy{
    width:100%;
    height:100%;
    display:flex;
    flex-direction:column;
}


/* ICON + TITLE ROW */
.climate-adaptive-skincaare-second-section .climate-title-row{
    width:100%;
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:12px;
}


/* ICON */
.climate-adaptive-skincaare-second-section .climate-icon{
    position:static;
    width:58px;
    height:58px;
    flex:0 0 58px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;
    background:#ffffff;

    font-size:25px;

    border:2px solid rgba(7,85,99,.12);

    box-shadow:0 6px 18px rgba(9,47,70,.10);
}

.climate-adaptive-skincaare-second-section .tropical-icon{
    color:#16835d;
    border-color:rgba(22,131,93,.22);
}

.climate-adaptive-skincaare-second-section .hot-icon{
    color:#e3a52d;
    border-color:rgba(227,165,45,.28);
}

.climate-adaptive-skincaare-second-section .cold-icon{
    color:#1577b8;
    border-color:rgba(21,119,184,.24);
}


/* TITLE */
.climate-adaptive-skincaare-second-section .climate-title-row h3{
    margin:0;
    padding:0;

    color:#075563;

    font-size:clamp(21px,1.8vw,27px);
    font-weight:700;

    line-height:1.2;
}


/* SUBTITLE */
.climate-adaptive-skincaare-second-section .climate-copy h5{
    width:100%;
    margin:0 0 7px;

    color:#092f46;

    font-size:15px;
    font-weight:600;

    line-height:1.4;
}


/* DESCRIPTION */
.climate-adaptive-skincaare-second-section .climate-copy p{
    width:100%;
    margin:0 0 20px;

    color:#5f6f78;

    font-size:14px;
    font-weight:500;

    line-height:1.6;
}


/* BUTTON */
.climate-adaptive-skincaare-second-section .climate-btn{
    min-height:44px;
    margin-top:auto;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    align-self:center;

    gap:8px;

    padding:10px 28px;

    color:#ffffff!important;
    background:#075563;

    border:1px solid #075563;
    border-radius:999px;

    font-size:14px;
    font-weight:600;

    text-decoration:none;

    transition:
        background .25s ease,
        transform .25s ease;
}

.climate-adaptive-skincaare-second-section .climate-btn:hover{
    color:#ffffff!important;
    background:#092f46;
    transform:translateY(-2px);
}


/* =========================================================
   PROTECTION BAR
========================================================= */

.climate-adaptive-skincaare-second-section .climate-protection-bar{
    margin-top:28px;
    padding:22px 28px;

    border-radius:25px;

    background:linear-gradient(
        90deg,
        #f3f8f7,
        #edf7f5
    );

    border:1px solid rgba(7,85,99,.08);
}


/* MAIN PROTECTION TITLE */
.climate-adaptive-skincaare-second-section .protection-main-title{
    padding-right:25px;

    color:#075563;

    font-size:clamp(22px,2vw,28px);
    font-weight:700;

    line-height:1.25;

    border-right:1px solid rgba(7,85,99,.25);
}


/* PROTECTION ITEM */
.climate-adaptive-skincaare-second-section .protection-item{
    min-height:85px;

    padding:0 22px;

    display:flex;
    align-items:center;

    gap:14px;

    border-right:1px solid rgba(7,85,99,.18);
}

.climate-adaptive-skincaare-second-section .protection-item-last{
    border-right:0;
}


/* PROTECTION ICON */
.climate-adaptive-skincaare-second-section .protection-icon{
    width:52px;
    height:52px;

    flex:0 0 52px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;
    background:#ffffff;

    font-size:24px;

    border:2px solid;

    box-shadow:0 5px 15px rgba(9,47,70,.06);
}

.climate-adaptive-skincaare-second-section .uv-icon{
    color:#d89b24;
    border-color:#e8bd5a;
}

.climate-adaptive-skincaare-second-section .antioxidant-icon{
    color:#55a83b;
    border-color:#88c86c;
}

.climate-adaptive-skincaare-second-section .pollution-icon{
    color:#159e9b;
    border-color:#5bc1bd;
}


/* PROTECTION TEXT */
.climate-adaptive-skincaare-second-section .protection-item h4{
    margin:0 0 4px;

    color:#075563;

    font-size:16px;
    font-weight:700;
}

.climate-adaptive-skincaare-second-section .protection-item p{
    margin:0;

    color:#5f6f78;

    font-size:13px;
    font-weight:500;

    line-height:1.45;
}


/* =========================================================
   TABLET
========================================================= */

@media(max-width:991.98px){

    .climate-adaptive-skincaare-second-section{
        padding:40px 0;
    }

    .climate-adaptive-skincaare-second-section .heading-line{
        width:70px;
    }

    .climate-adaptive-skincaare-second-section .climate-image-wrap{
        height:155px;
    }

    .climate-adaptive-skincaare-second-section .climate-protection-bar{
        padding:25px 20px;
    }

    .climate-adaptive-skincaare-second-section .protection-main-title{
        padding:0 0 20px;
        margin-bottom:20px;

        text-align:center;

        border-right:0;
        border-bottom:1px solid rgba(7,85,99,.18);
    }

    .climate-adaptive-skincaare-second-section .protection-item{
        padding:0 15px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width:767.98px){

    .climate-adaptive-skincaare-second-section{
        padding:32px 0;
    }

    .climate-adaptive-skincaare-second-section .climate-second-heading{
        gap:12px;
        margin-bottom:22px;
    }

    .climate-adaptive-skincaare-second-section .heading-line{
        width:35px;
    }

    .climate-adaptive-skincaare-second-section .climate-second-heading h2{
        font-size:27px;
    }

    .climate-adaptive-skincaare-second-section .climate-image-wrap{
        height:145px;
    }

    .climate-adaptive-skincaare-second-section .climate-card-content{
        min-height:auto;
        padding:20px 18px;
    }

    .climate-adaptive-skincaare-second-section .climate-title-row{
        gap:12px;
        margin-bottom:10px;
    }

    .climate-adaptive-skincaare-second-section .climate-icon{
        width:52px;
        height:52px;

        flex-basis:52px;

        font-size:22px;
    }

    .climate-adaptive-skincaare-second-section .climate-title-row h3{
        font-size:20px;
    }

    .climate-adaptive-skincaare-second-section .climate-copy h5{
        margin-bottom:7px;

        font-size:13px;
    }

    .climate-adaptive-skincaare-second-section .climate-copy p{
        margin-bottom:18px;

        font-size:12px;
        line-height:1.6;
    }

    .climate-adaptive-skincaare-second-section .climate-btn{
        width:100%;
        margin-top:8px;

        font-size:13px;
    }

    .climate-adaptive-skincaare-second-section .climate-protection-bar{
        margin-top:24px;

        padding:20px 16px;
    }

    .climate-adaptive-skincaare-second-section .protection-item{
        padding:16px 5px;

        border-right:0;
        border-bottom:1px solid rgba(7,85,99,.15);
    }

    .climate-adaptive-skincaare-second-section .protection-item-last{
        border-bottom:0;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media(max-width:480px){

    .climate-adaptive-skincaare-second-section .heading-line{
        width:22px;
    }

    .climate-adaptive-skincaare-second-section .climate-second-heading h2{
        font-size:23px;
    }

    .climate-adaptive-skincaare-second-section .climate-title-row{
        gap:10px;
    }

    .climate-adaptive-skincaare-second-section .climate-icon{
        width:46px;
        height:46px;

        flex-basis:46px;

        font-size:20px;
    }

    .climate-adaptive-skincaare-second-section .climate-title-row h3{
        font-size:18px;
    }

    .climate-adaptive-skincaare-second-section .protection-main-title{
        font-size:20px;
    }

    .climate-adaptive-skincaare-second-section .protection-icon{
        width:46px;
        height:46px;

        flex-basis:46px;

        font-size:20px;
    }

    .climate-adaptive-skincaare-second-section .protection-item h4{
        font-size:14px;
    }

    .climate-adaptive-skincaare-second-section .protection-item p{
        font-size:12px;
    }

}
</style>

<!-- Bootstrap Icons -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<!-- CLIMATE ADAPTIVE SKINCARE SECOND SECTION END -->
<?php include 'footer.php'; ?>