<?php
// MUST BE FIRST LINE
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    // Use a relative redirect (login.php lives in the same folder)
    header('Location: login.php');
    exit();
}

$pageTitle = 'Checkout';
require_once 'header.php';
$user_id = (int) $_SESSION['user_id'];

// Get cart items (cast user id to int to avoid injection)
$cartQuery = "SELECT c.*, p.product_name, p.price, p.stock_quantity
             FROM cart c
             JOIN products p ON c.product_id = p.product_id
             WHERE c.user_id = $user_id";
$cartResult = mysqli_query($conn, $cartQuery);

$subtotal = 0;
$shipping = 4.99;

// If the query failed, show a friendly message instead of warnings
if ($cartResult === false) {
    error_log('Cart query failed: ' . mysqli_error($conn));
    $cartCount = 0;
} else {
    $cartCount = mysqli_num_rows($cartResult);
}
?>

<main>
    <div class="checkout-page">
        <section class="checkout-items">
            <h1>Your basket</h1>
            
            <?php if ($cartCount > 0 && $cartResult !== false): ?>
                <?php while ($item = mysqli_fetch_assoc($cartResult)): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                ?>
                    <article class="checkout-item">
                        <div class="checkout-thumb">
                            <div class="checkout-thumb-placeholder"></div>
                        </div>

                        <div class="checkout-main">
                            <h2><?php echo htmlspecialchars($item['product_name']); ?></h2>
                            <p>£<?php echo number_format($item['price'], 2); ?> each</p>

                            <div class="checkout-meta">
                                <div>
                                    <span>Qty: </span>
                                    <button onclick="updateCartQuantity(<?php echo $item['product_id']; ?>, -1)" 
                                            style="background: #eee; border: 1px solid #ccc; padding: 2px 8px; cursor: pointer;">-</button>
                                    <span style="padding: 0 10px;"><?php echo $item['quantity']; ?></span>
                                    <button onclick="updateCartQuantity(<?php echo $item['product_id']; ?>, 1)" 
                                            style="background: #eee; border: 1px solid #ccc; padding: 2px 8px; cursor: pointer;">+</button>
                                    <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)" 
                                            style="background: #ff4444; color: white; border: none; padding: 5px 10px; 
                                                   margin-left: 10px; cursor: pointer; border-radius: 3px;">
                                        Remove
                                    </button>
                                </div>
                                <span style="font-weight: bold;">£<?php echo number_format($itemTotal, 2); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-basket">
                    <p>Your basket is empty</p>
                    <a href="products.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">
                        Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <?php if ($cartCount > 0): ?>
            <aside class="checkout-summary">
                <h2>Order summary</h2>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>£<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Delivery</span>
                    <span>£<?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span>£<?php echo number_format($subtotal + $shipping, 2); ?></span>
                </div>
                <div class="summary-row">
                   <a href="checkout_payment.php" class="btn-primary" style="width:80%;">
                    Checkout
                </a>
                </div>
                <p style="text-align: center; margin-top: 10px;">
                    <a href="products.php" style="color: #666; text-decoration: none;">Continue shopping</a>
                </p>
            </aside>
        <?php endif; ?>
    </div>
    </div>
</main>

<?php
// Free result set when done
if (isset($cartResult) && $cartResult !== false) {
    mysqli_free_result($cartResult);
}

require_once 'footer.php';
?>