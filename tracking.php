<?php include "navbar.php"; ?>


<div class="tracking">
    <a href="">Index</a>
    <a href="">/Track Your Order</a>
</div>


<div class="d-flex align-items-center  justify-content-center my-5">
    <div class="track_your_order-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="fw-normal">Track Your Order</h2>
            <p class="text-muted">Enter your order details to track your order</p>
        </div>

        <!-- Tabs -->
        <div class="track_your_order-tabs d-flex mb-4" id="trackTabs">
            <div class="track_your_order-tab-item flex-fill active" onclick="switchTab('orderId', 0)">Order ID</div>
            <div class="track_your_order-tab-item flex-fill" onclick="switchTab('waybill', 1)">Waybill</div>
            <div class="track_your_order-underline" id="movingLine" style="left: 0;"></div>
        </div>

        <!-- Form -->
        <form action="" method="POST">
            <!-- Order ID Form Section -->
            <div id="orderIdContent">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Order ID</label>
                    <input type="text" class="form-control" placeholder="Enter Order ID" name="order_id">
                </div>
            </div>

            <!-- Waybill Form Section (Hidden by default) -->
            <div id="waybillContent" class="d-none">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Tracking ID/Waybill/AWB</label>
                    <input type="text" class="form-control" placeholder="Enter Tracking ID/Waybill/AWB Number"
                        name="waybill_id">
                </div>
            </div>

            <!-- Mobile Input (Common for both) -->
            <div class="mb-4">
                <label class="form-label fw-bold small">Mobile</label>
                <input type="text" class="form-control" placeholder="Enter Mobile" name="mobile">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-danger w-100 track_your_order-submit-btn">
                Track Your Order
            </button>
        </form>
    </div>

</div>



<script>
    function switchTab(type, index) {
        const orderIdContent = document.getElementById('orderIdContent');
        const waybillContent = document.getElementById('waybillContent');
        const movingLine = document.getElementById('movingLine');
        const tabs = document.querySelectorAll('.track_your_order-tab-item');

        // 1. Switch Form Visibility
        if (type === 'orderId') {
            orderIdContent.classList.remove('d-none');
            waybillContent.classList.add('d-none');
        } else {
            orderIdContent.classList.add('d-none');
            waybillContent.classList.remove('d-none');
        }

        // 2. Update Underline Position
        movingLine.style.left = (index * 50) + '%';

        // 3. Update Active Tab Color
        tabs.forEach(tab => tab.classList.remove('active'));
        tabs[index].classList.add('active');
    }
</script>

<?php include 'footer.php'; ?>