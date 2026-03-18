<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
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

    $shipping_address = "$address1\n$address2\n$city\n$postcode";

    $different_billing = isset($_POST['different_billing']);

    if ($different_billing) {
        $billing_address1 = sanitize($_POST['billing_address1']);
        $billing_address2 = sanitize($_POST['billing_address2']);
        $billing_city = sanitize($_POST['billing_city']);
        $billing_postcode = sanitize($_POST['billing_postcode']);

        $billing_address = "$billing_address1\n$billing_address2\n$billing_city\n$billing_postcode";
    } else {
        $billing_address = $shipping_address;
    }

    mysqli_begin_transaction($conn);

    try {
        $orderQuery = "INSERT INTO orders (user_id, total_amount, shipping_address, billing_address, payment_method) 
                      VALUES ($user_id, $total, '$shipping_address', '$billing_address', 'Credit Card')";

        if (!mysqli_query($conn, $orderQuery)) {
            throw new Exception('Failed to create order');
        }

        $order_id = mysqli_insert_id($conn);

        $itemsQuery = "SELECT c.*, p.product_name, p.price, p.stock_quantity 
                      FROM cart c 
                      JOIN products p ON c.product_id = p.product_id 
                      WHERE c.user_id = $user_id";
        $itemsResult = mysqli_query($conn, $itemsQuery);

        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $itemTotal = $item['price'] * $item['quantity'];

            if ($item['quantity'] > $item['stock_quantity']) {
                throw new Exception('Insufficient stock for ' . $item['product_name']);
            }

            $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                              VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']}, $itemTotal)";

            if (!mysqli_query($conn, $orderItemQuery)) {
                throw new Exception('Failed to add order item');
            }

            $updateStockQuery = "UPDATE products 
                                SET stock_quantity = stock_quantity - {$item['quantity']} 
                                WHERE product_id = {$item['product_id']}";

            if (!mysqli_query($conn, $updateStockQuery)) {
                throw new Exception('Failed to update stock');
            }
        }

        $clearCartQuery = "DELETE FROM cart WHERE user_id = $user_id";
        mysqli_query($conn, $clearCartQuery);

        mysqli_commit($conn);

        header("Location: /checkout_complete.php?order_id=$order_id");
        exit();
    } catch (Exception $e) {
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

            <div style="text-align:left; max-width: 350px; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 1px 20px; border-radius: 20px; position: relative; margin: 60px auto;">
                <p><strong>Logged in as</strong><br>
                <div style="padding-left: 40px;">
                    <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?><br>
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
                </p>
            </div>

            <form method="post" class="form" id="payment-form" style="text-align:left;">

                <h3>Card details</h3>

                <div id="card-fields" style="margin-bottom: 50px;">
                    <div class="input-container" style="margin-bottom: 10px; margin-top: 0;">
                        <input class="input-field" type="text" name="card_name"
                            value="<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>"
                            required>
                        <label class="input-label">Name on card</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 10px;">
                        <input class="input-field" type="text" name="card_number" maxlength="19" placeholder="Card number"
                            required>
                        <label class="input-label">Card number</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="form-grid" style="margin-bottom: 0; margin-top: 40px; align-items: start;">
                        <div class="input-container" style="margin-bottom: 0; margin-top: 0;">
                            <input class="input-field" type="text" name="expiry" placeholder="MM/YY" required>
                            <label class="input-label">Expiry date</label>
                            <span class="input-highlight"></span>
                        </div>

                        <div class="input-container" style="margin-bottom: 0; margin-top: 0;">
                            <input class="input-field" type="text" name="cvc" maxlength="4" placeholder="CVC" required>
                            <label class="input-label">CVC</label>
                            <span class="input-highlight"></span>
                        </div>
                    </div>
                </div>

                <h3>Delivery address</h3>

                <div id="delivery-fields" style="margin-bottom: 50px;">
                    <div class="input-container" style="margin-bottom: 10px; margin-top: 0;">
                        <input class="input-field" type="text" name="address1" placeholder="Address line 1" required>
                        <label class="input-label">Address line 1</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 10px;">
                        <input class="input-field" type="text" name="address2" placeholder="Address line 2 (optional)">
                        <label class="input-label">Address line 2 (optional)</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 10px;">
                        <input class="input-field" type="text" name="city" placeholder="Town / City" required>
                        <label class="input-label">Town / City</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 0;">
                        <input class="input-field" type="text" name="postcode" placeholder="Postcode" required>
                        <label class="input-label">Postcode</label>
                        <span class="input-highlight"></span>
                    </div>
                </div>

                <h3>Billing address</h3>

                <div style="margin-bottom: 15px;">
                    <label>
                        <input type="checkbox" name="different_billing" id="different_billing">
                        Billing address is different from delivery address
                    </label>
                </div>

                <div id="billing-fields" style="display: none; margin-bottom: 50px;">
                    <div class="input-container" style="margin-bottom: 10px; margin-top: 0;">
                        <input class="input-field" type="text" name="billing_address1" placeholder="Billing address line 1">
                        <label class="input-label">Billing address line 1</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 10px;">
                        <input class="input-field" type="text" name="billing_address2" placeholder="Billing address line 2 (optional)">
                        <label class="input-label">Billing address line 2 (optional)</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 10px;">
                        <input class="input-field" type="text" name="billing_city" placeholder="Billing town / city">
                        <label class="input-label">Billing town / city</label>
                        <span class="input-highlight"></span>
                    </div>

                    <div class="input-container" style="margin-bottom: 0;">
                        <input class="input-field" type="text" name="billing_postcode" placeholder="Billing postcode">
                        <label class="input-label">Billing postcode</label>
                        <span class="input-highlight"></span>
                    </div>
                </div>
            </form>
        </section>

        <aside class="checkout-summary">
            <h2>ORDER SUMMARY</h2>

            <div class="summary-row">
                <span>SUBTOTAL</span>
                <span>£<?php echo number_format((float)str_replace(',', '', $cartTotal), 2); ?></span>
            </div>
            <div class="summary-row">
                <span>DELIVERY</span>
                <span>£<?php echo number_format((float)$shipping, 2); ?></span>
            </div>
            <div class="summary-row summary-total">
                <span>TOTAL</span>
                <span>£<?php echo number_format((float)str_replace(',', '', $total), 2); ?></span>
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

<script>
    document.getElementById('different_billing').addEventListener('change', function() {
        const billingFields = document.getElementById('billing-fields');
        billingFields.style.display = this.checked ? 'block' : 'none';
    });
</script>

<?php require_once 'footer.php'; ?>