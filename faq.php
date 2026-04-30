<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --faq-primary: #8b0000;
            /* Dark Red color from image */
            --faq-text: #333;
            --faq-muted: #666;
            --faq-border: #eee;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            color: var(--faq-text);
        }

        .faq_section {
            padding: 60px 0;
        }

        .faq_header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq_header h1 {
            font-size: 2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .faq_header p {
            color: var(--faq-muted);
            font-size: 0.9rem;
        }

        /* Sidebar Navigation */
        .faq_sidebar {
            position: sticky;
            top: 20px;
        }

        .faq_sidebar_title {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .faq_sidebar_list {
            list-style: none;
            padding: 0;
        }

        .faq_sidebar_item {
            margin-bottom: 12px;
        }

        .faq_sidebar_link {
            text-decoration: none;
            color: #555;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .faq_sidebar_link:hover {
            color: var(--faq-primary);
        }

        /* Accordion Content */
        .faq_category_title {
            color: var(--faq-primary);
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--faq-border);
            padding-bottom: 5px;
        }

        .faq_accordion .accordion-item {
            border: none;
            border-bottom: 1px solid var(--faq-border);
        }

        .faq_accordion .accordion-button {
            padding: 15px 0;
            font-size: 0.95rem;
            background-color: transparent !important;
            color: var(--faq-text);
            box-shadow: none !important;
            font-weight: 400;
        }

        .faq_accordion .accordion-button:not(.collapsed) {
            color: var(--faq-primary);
        }

        .faq_accordion .accordion-button::after {
            background-size: 12px;
            width: 12px;
        }

        .faq_accordion .accordion-body {
            padding: 0 0 20px 0;
            font-size: 0.9rem;
            color: var(--faq-muted);
            line-height: 1.6;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
        /* .faq_side_section{
            background-color: lightblue;
            padding: 20px;
        }
     .faq_sidebar_item:hover{
        background-color: blue;
        padding-left: 10px;
        color: white !important;
     } */
    </style>
</head>

<body>

    <section class="faq_section">
        <div class="container">
            <!-- FAQ Title -->
            <div class="faq_header">
                <h1>Frequently Asked Questions</h1>
                <p>Find answers to common questions about our products and services.</p>
            </div>

            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="faq_sidebar faq_side_section">
                        <div class="faq_sidebar_title">Jump To</div>
                        <ul class="faq_sidebar_list">
                            <li class="faq_sidebar_item"><a href="#order" class="faq_sidebar_link">Order Related</a></li>
                            <li class="faq_sidebar_item"><a href="#payment" class="faq_sidebar_link">Payment Related</a></li>
                            <li class="faq_sidebar_item"><a href="#cancellation" class="faq_sidebar_link">Order Cancellation Related</a></li>
                            <li class="faq_sidebar_item"><a href="#refund" class="faq_sidebar_link">Refund/Return Related</a></li>
                            <li class="faq_sidebar_item"><a href="#delivery" class="faq_sidebar_link">Delivery Related</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">

                    <!-- Order Related Section -->
                    <div id="order">
                        <div class="faq_category_title">Order Related</div>
                        <div class="accordion faq_accordion" id="orderAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                        How can I track my order?
                                    </button>
                                </h2>
                                <div id="q1" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">
                                        After your order is shipped, you’ll receive a tracking link via email. Alternatively, you can also track it using this link
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                        How do I know that my order is confirmed?
                                    </button>
                                </h2>
                                <div id="q2" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        As soon as you place the order, you will receive a confirmation SMS and email with the order number and the estimated time of delivery.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                        Will I have to bear the shipping charges?
                                    </button>
                                </h2>
                                <div id="q3" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        We provide free shipping for orders above a certain amount, which is updated regularly. We also offer cash-on-delivery in many areas at no extra cost. The shipping charges shown at checkout are final.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                                        Do you have COD facility?
                                    </button>
                                </h2>
                                <div id="q4" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        Yes, we do have Cash On Delivery facility for all our orders.
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                                        What are the different ways I can order /PHD/ products?
                                    </button>
                                </h2>
                                <div id="q5" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        You can order /PHD/ products online from various stores including www.phdbeauty.com, Amazon and Flipkart.
                                    </div>
                                </div>
                            </div>



                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q6">
                                        What is the standard time of delivery?
                                    </button>
                                </h2>
                                <div id="q6" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        Once you place the order, it is shipped within 2 business days and you can expect it to be delivered within 4-5 business days.
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q7">
                                        Can the billing and the shipping address be different?
                                    </button>
                                </h2>
                                <div id="q7" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">

                                        -
                                        Yes, the billing and shipping addresses can be different.
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q7">
                                        Do you ship outside India?
                                    </button>
                                </h2>
                                <div id="q7" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">



                                        -
                                        No. We currently ship in India only.
                                    </div>
                                </div>
                            </div>



                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q7">
                                        How can I modify my order?
                                    </button>
                                </h2>
                                <div id="q7" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                                    <div class="accordion-body">


                                        -
                                        We regret to inform you that once you have placed an order, it cannot be modified. However, you can contact us via care@phdbeauty.com and we will do our best to assist you.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Related Section -->
                    <div id="payment" class="mt-4">
                        <div class="faq_category_title">Payment Related</div>
                        <div class="accordion faq_accordion" id="paymentAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                                        Can I modify/change my payment mode?
                                        -
                                    </button>
                                </h2>
                                <div id="p1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        Unfortunately, once an order is placed, the payment method cannot be altered.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                                        I am unable to make the payment
                                    </button>
                                </h2>
                                <div id="p1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        -
                                        We apologise for the inconvenience caused. Kindly try again after clearing your browser’s cache and cookies, or, try using a different payment method. However, if the issue still persists, kindly contact us at care@phdbeauty.com and we will assist you.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                                        What payment methods are accepted?

                                    </button>
                                </h2>
                                <div id="p1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        We accept credit/debit cards, Net banking, UPI, and Cash on Delivery (COD).
                                        *COD orders carry an extra convenience fee to be paid.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                                        My money has been deducted, but my order is not confirmed.
                                    </button>
                                </h2>
                                <div id="p1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        -
                                        We apologize for the inconvenience. Please allow up to 24 hours for the confirmation email to arrive. If you don’t receive it, be rest assured that any deducted amount will be refunded to your source account within 3-5 working days
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





                    <div id="cancellation" class="mt-4">
                        <div class="faq_category_title">Order Cancellation Related</div>
                        <div class="accordion faq_accordion" id="paymentAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
                                        How do I cancel my order?

                                    </button>
                                </h2>
                                <div id="c1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">



                                        You can cancel your order at your end within 15 minutes of placing it. After this period, please contact our customer support team for assistance. Kindly note that orders already shipped are not eligible for cancellation.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                                        I am not able to cancel my order
                                    </button>
                                </h2>
                                <div id="c2" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">



                                        -
                                        Orders can only be canceled before they are shipped. Once your order has been dispatched, it cannot be canceled.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
                                        My order has been canceled

                                    </button>
                                </h2>
                                <div id="c3" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">



                                        If your order was canceled on our end, it may have been due to a violation of our Terms and Conditions, such as exceeding order or product limits. For more information, please refer to our Terms and Conditions
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>









                    <div id="refund" class="mt-4">
                        <div class="faq_category_title"> Refund/Return Related</div>
                        <div class="accordion faq_accordion" id="paymentAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#r1">
                                        When will I get my refund?

                                    </button>
                                </h2>
                                <div id="r1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">





                                        For prepaid orders, eligible refunds are initiated within 24–48 hours of cancellation or approval. Once initiated, the amount is credited back to your original payment method within 3–5 working days (UPI refunds are typically much faster).
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#r2">
                                        How do I return my order?
                                    </button>
                                </h2>
                                <div id="r2" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">


                                        -
                                        We only allow return in specific cases. Please refer to our Return Policy before applying for return. You can view our return policy at https://phdbeauty.com/pages/shipping-returns
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div id="delivery" class="mt-4">
                        <div class="faq_category_title"> Delivery Related</div>
                        <div class="accordion faq_accordion" id="paymentAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#d1">
                                        What should I do if products are missing from my order?


                                    </button>
                                </h2>
                                <div id="d1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        Your order might be shipped in multiple packages. You will receive separate tracking details for each package. To track your order, please visit

                                        <br>
                                        If you don’t see separate tracking links for your items or need further assistance, feel free to reach out to us via email at care@phdbeauty.com or write to us on this link: https://phdbeauty.com/pages/contact. We will do our best to assist you.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#d2">
                                        Received wrong/expired/defective/damaged product(s)
                                    </button>
                                </h2>
                                <div id="d2" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        We apologise for the inconvenience caused. Please reach out to our customer support team via Email (care@phdbeauty.com) or write to us on this link: https://phdbeauty.com/pages/contact and we can promptly have this rectified.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#d3">
                                        The delivery person was unprofessional in his behaviour
                                    </button>
                                </h2>
                                <div id="d3" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">




                                        We take this matter seriously and sincerely apologize for the inconvenience you've faced. Please contact our customer support team on care@phdbeauty.com or write to us on this link: https://phdbeauty.com/pages/contact
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#d4">
                                        My order status is not updating?
                                    </button>
                                </h2>
                                <div id="d4" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">



                                        You can track your current order status by visiting our website https://phdbeauty.com/pages/track-your-order. If your query remains unresolved, please reach out to our customer support team on care@phdbeauty.com or write to us on this link: https://phdbeauty.com/pages/contact .You can track your current order status by visiting our website. If your query remains unresolved, please reach out to our customer support team on care@phdbeauty.com

                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#d5">
                                        My order was returned without any delivery attempt
                                    </button>
                                </h2>
                                <div id="d5" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">
                                        We’re sorry to hear that. Typically, our courier partner makes 3 delivery attempts before returning the order. Please check your SMS or email for any updates about the delivery. You can also check detailed status for your order by clicking on https://phdbeauty.com/pages/track-your-order
                                    </div>
                                </div>
                            </div>
                          


                        </div>

                    </div>









                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>