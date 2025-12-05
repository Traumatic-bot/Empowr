<?php
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'Order Complete';

require_once 'header.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;


$orderQuery = "SELECT * FROM orders WHERE order_id = $order_id AND user_id = {$_SESSION['user_id']}";
$orderResult = mysqli_query($conn, $orderQuery);

if (!$orderResult || mysqli_num_rows($orderResult) == 0) {
    header('Location: dashboard.php');
    exit();
}

$order = mysqli_fetch_assoc($orderResult);
?>

<main>
    <div class="checkout-page">
        <section class="checkout-items">
            <h1>Thank you for your order</h1>

            <p>Your order has been placed successfully.</p>
            <p>We've sent a confirmation email with your order details.</p>

            <a href="index.php" class="btn-primary">
                Back to home
            </a>
        </section>

        <aside class="checkout-summary">
            <h2>Order summary</h2>

            <div class="summary-row">
                <span>Order number</span>
                <span>#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="summary-row">
                <span>Status</span>
                <span><?php echo htmlspecialchars($order['status']); ?></span>
            </div>
            <div class="summary-row">
                <span>Items total</span>
                <span>£<?php echo number_format($order['total_amount'] - 4.99, 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Delivery</span>
                <span>£4.99</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span>£<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
        </aside>
    </div>
</main>

<?php require_once 'footer.php'; ?>