<?php
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$rawOrderId = $requestMethod === 'POST'
    ? ($_POST['order_id'] ?? '')
    : ($_GET['order_id'] ?? '');
$invalidOrderId = !is_string($rawOrderId) || strlen(trim($rawOrderId)) > 100;
$orderId = !$invalidOrderId && is_string($rawOrderId) ? trim($rawOrderId) : '';
$autoTrack = !$invalidOrderId && $orderId !== '' && in_array($requestMethod, ['GET', 'POST'], true);
$escapeOrderId = static fn($value) => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<?php include 'navbar.php'; ?>

<main class="order-tracking-page">
    <nav class="order-tracking-breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a>
        <span aria-hidden="true">/</span>
        <span>Track My Order</span>
    </nav>

    <section class="order-tracking-card" aria-labelledby="orderTrackingTitle">
        <span class="order-tracking-kicker">ORDER STATUS</span>
        <h1 id="orderTrackingTitle">Track My Order</h1>
        <p>Enter your order ID to view its latest status and details.</p>

        <form id="orderTrackingForm" action="tracking.php" method="post" data-auto-track="<?= $autoTrack ? 'true' : 'false' ?>">
            <label for="orderTrackingId">Order ID</label>
            <div class="order-tracking-field">
                <input id="orderTrackingId" name="order_id" type="text" maxlength="100" value="<?= $escapeOrderId($orderId) ?>" placeholder="Enter your order ID" autocomplete="off" required>
                <button type="submit">Track order</button>
            </div>
        </form>

        <p id="orderTrackingMessage" class="order-tracking-message" role="status" aria-live="polite"><?= $invalidOrderId ? 'Please enter a valid order ID.' : '' ?></p>
    </section>
</main>

<script src="assets/js/order-tracking.js?v=1" defer></script>
<?php include 'footer.php'; ?>
