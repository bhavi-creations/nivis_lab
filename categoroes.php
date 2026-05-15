<script>
    const API_URL = 'fetch_data.php';

    async function connectToBackend() {
        try {
            const response = await fetch(API_URL);
            
            // Response ని టెక్స్ట్‌గా తీసుకుని అది JSON అవునా కాదా అని చూస్తున్నాం
            const rawData = await response.text();
            
            let result;
            try {
                result = JSON.parse(rawData);
            } catch (e) {
                throw new Error("Received invalid JSON. Check fetch_data.php in browser.");
            }

            if (result.error) {
                showError(result.error);
                return;
            }

            if (result.data && result.data.products) {
                renderProducts(result.data.products.items);
            }
        } catch (error) {
            console.error("Connection Failed:", error);
            showError(error.message);
        }
    }

    function showError(msg) {
        document.getElementById('product-list').innerHTML = `
            <div class="alert alert-danger text-center w-100">
                <strong>Error:</strong> ${msg}
            </div>`;
    }

    function renderProducts(products) {
        const container = document.getElementById('product-list');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = '<p class="text-center w-100">No products found in EverShop.</p>';
            return;
        }

        products.forEach(product => {
            const imageUrl = product.image && product.image.url
                ? `http://localhost:3000${product.image.url}`
                : 'https://via.placeholder.com/200?text=No+Image';

            const productName = product.name || 'Unknown Product';
            const productPrice = product.price && product.price.regular ? product.price.regular.text : 'N/A';

            container.innerHTML += `
            <div class="col-md-3 mb-4 product-card">
                <div class="card shadow-sm h-100 border-0">
                    <img src="${imageUrl}" class="card-img-top rounded" alt="${productName}">
                    <div class="card-body text-center">
                        <h6 class="card-title text-truncate">${productName}</h6>
                        <p class="text-primary fw-bold">${productPrice}</p>
                        <div class="d-grid">
                            <button class="btn btn-sm btn-dark">View Details</button>
                        </div>
                    </div>
                </div>
            </div>`;
        });
    }

    window.onload = connectToBackend;
</script>