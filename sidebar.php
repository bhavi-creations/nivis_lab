<div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="cartDrawerLabel">Mee Cart (1 Item)</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="cartContent">
        <div class="text-center py-5" id="emptyMsg">Cart is loading...</div>
    </div>
    <div class="offcanvas-footer p-3 border-top">
        <button class="btn btn-dark w-100 py-3 fw-bold">CHECKOUT</button>
    </div>
</div>
<script>
    document.querySelector('.video_section_add_btn').addEventListener('click', function() {
        const cartBody = document.getElementById('cartContent');

        // Example product card structure (Image lo unnattu)
        cartBody.innerHTML = `
        <div class="d-flex align-items-center border-bottom pb-3 mb-3">
            <img src="path_to_your_image.jpg" alt="Product" style="width: 80px;" class="rounded">
            <div class="ms-3">
                <p class="mb-0 fw-bold">Panthenol Hydrating Gel Sunscreen</p>
                <p class="mb-0 text-muted small">50ml | ₹899</p>
                <div class="mt-2 border d-inline-flex align-items-center px-2 py-1">
                    <span class="px-2" style="cursor:pointer">-</span>
                    <span class="px-3 border-start border-end">1</span>
                    <span class="px-2" style="cursor:pointer">+</span>
                </div>
            </div>
        </div>
        
        <h6 class="mt-4">Complete your routine</h6>
        <div class="border rounded p-2 d-flex align-items-center mt-2">
            <img src="serum_image.jpg" width="40" class="me-2">
            <div class="flex-grow-1">
                <small class="d-block">Niacinamide Serum</small>
                <strong class="small">₹350</strong>
            </div>
            <button class="btn btn-sm btn-outline-dark">ADD</button>
        </div>
    `;
    });
</script>