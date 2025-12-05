<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /empower/PHP/login.php');
    exit();
}

$pageTitle = 'Payment';

$user_id = $_SESSION['user_id'];
$error = '';

// Get cart total
$cartQuery = "SELECT SUM(p.price * c.quantity) as total 
             FROM cart c 
             JOIN products p ON c.product_id = p.product_id 
             WHERE c.user_id = $user_id";
$cartResult = mysqli_query($conn, $cartQuery);
$cartTotal = 0;

if ($cartResult && $row = mysqli_fetch_assoc($cartResult)) {
    $cartTotal = $row['total'] ?: 0;
}

$shipping = 4.99;
$total = $cartTotal + $shipping;

// Process payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address1 = sanitize($_POST['address1']);
    $address2 = sanitize($_POST['address2']);
    $city = sanitize($_POST['city']);
    $postcode = sanitize($_POST['postcode']);
    $card_name = sanitize($_POST['card_name']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // 1. Create order
        $shipping_address = "$address1\n$address2\n$city\n$postcode";
        $orderQuery = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                      VALUES ($user_id, $total, '$shipping_address', 'Credit Card')";
        
        if (!mysqli_query($conn, $orderQuery)) {
            throw new Exception('Failed to create order');
        }
        
        $order_id = mysqli_insert_id($conn);
        
        // 2. Add order items and update stock
        $itemsQuery = "SELECT c.*, p.product_name, p.price, p.stock_quantity 
                      FROM cart c 
                      JOIN products p ON c.product_id = p.product_id 
                      WHERE c.user_id = $user_id";
        $itemsResult = mysqli_query($conn, $itemsQuery);
        
        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $itemTotal = $item['price'] * $item['quantity'];
            
            // Check stock
            if ($item['quantity'] > $item['stock_quantity']) {
                throw new Exception('Insufficient stock for ' . $item['product_name']);
            }
            
            // Insert order item
            $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                              VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']}, $itemTotal)";
            
            if (!mysqli_query($conn, $orderItemQuery)) {
                throw new Exception('Failed to add order item');
            }
            
            // Update stock
            $updateStockQuery = "UPDATE products SET stock_quantity = stock_quantity - {$item['quantity']} 
                                WHERE product_id = {$item['product_id']}";
            
            if (!mysqli_query($conn, $updateStockQuery)) {
                throw new Exception('Failed to update stock');
            }
        }
        
        // 3. Clear cart
        $clearCartQuery = "DELETE FROM cart WHERE user_id = $user_id";
        mysqli_query($conn, $clearCartQuery);
        
        // Commit transaction
        mysqli_commit($conn);
        
        header("Location: /empower/PHP/checkout_complete.php?order_id=$order_id");
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }
}

require_once 'header.php';
?>

<main>
    <div class="checkout-page">
        <section class="checkout-items">
            <h1>PAYMENT</h1>

            <div style="text-align:left; margin-bottom:20px;">
                <p>Logged in as<br>
                    <strong><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></strong><br>
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </p>
            </div>

            <form method="post" class="form" id="payment-form" style="text-align:left;">
                <!-- Delivery address -->
                <h3>Delivery address</h3>

                <div class="input-container">
                    <input class="input-field" type="text" name="address1" placeholder="Address line 1" required>
                    <label class="input-label">Address line 1</label>
                    <span class="input-highlight"></span>
                </div>

                <div class="input-container">
                    <input class="input-field" type="text" name="address2" placeholder="Address line 2 (optional)">
                    <label class="input-label">Address line 2 (optional)</label>
                    <span class="input-highlight"></span>
                </div>

                <div class="input-container">
                    <input class="input-field" type="text" name="city" placeholder="Town / City" required>
                    <label class="input-label">Town / City</label>
                    <span class="input-highlight"></span>
                </div>

                <div class="input-container">
                    <input class="input-field" type="text" name="postcode" placeholder="Postcode" required>
                    <label class="input-label">Postcode</label>
                    <span class="input-highlight"></span>
                </div>

                <!-- Payment details -->
                <h3>Card details</h3>

                <div class="input-container">
                    <input class="input-field" type="text" name="card_name" 
                           value="<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>" required>
                    <label class="input-label">Name on card</label>
                    <span class="input-highlight"></span>
                </div>

                <div class="input-container">
                    <input class="input-field" type="text" name="card_number" maxlength="19" placeholder="Card number" required>
                    <label class="input-label">Card number</label>
                    <span class="input-highlight"></span>
                </div>

                <div class="form-grid">
                    <div class="input-container">
                        <input class="input-field" type="text" name="expiry" placeholder="MM/YY" required>
                        <label class="input-label">Expiry date</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container">
                        <input class="input-field" type="text" name="cvc" maxlength="4" placeholder="CVC" required>
                        <label class="input-label">CVC</label>
                        <span class="input-highlight"></span>
                    </div>
                </div>

            </form>
        </section>

        <aside class="checkout-summary">
            <h2>ORDER SUMMARY</h2>

            <div class="summary-row">
                <span>SUBTOTAL</span>
                <span>£<?php echo number_format($cartTotal, 2); ?></span>
            </div>
            <div class="summary-row">
                <span>DELIVERY</span>
                <span>£<?php echo number_format($shipping, 2); ?></span>
            </div>
            <div class="summary-row summary-total">
                <span>TOTAL</span>
                <span>£<?php echo number_format($total, 2); ?></span>
            </div>

            <button class="btn-primary" type="submit" form="payment-form">
                PLACE ORDER
            </button>
            <p style="text-align: center; margin-top: 10px; font-size: 0.9em;">
                <a href="checkout.php" style="color: #666; text-decoration: none;">← Back to basket</a>
            </p>
        </aside>
    </div>
</main>

<?php require_once 'footer.php'; ?>