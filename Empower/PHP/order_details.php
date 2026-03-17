<?php
require_once 'config.php';

// Check if user is logged in 
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'My Orders';

require_once 'header.php';

$user_id = $_SESSION['user_id'];

// Get orders
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$query = "SELECT 
            o.*, 
            COALESCE(SUM(oi.quantity), 0) AS item_count,
            COALESCE(SUM(oi.total_price), 0) AS subtotal
          FROM orders o
          LEFT JOIN order_items oi ON o.order_id = oi.order_id
          WHERE o.order_id = $order_id
          AND o.user_id = $user_id
          GROUP BY o.order_id
          LIMIT 1";

$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

$orderItems = [];

if ($order) {
    $itemsQuery = "SELECT 
                    oi.product_id,
                    oi.quantity,
                    oi.unit_price,
                    oi.total_price,
                    p.product_name,
                    p.price,
                    p.category,
                    p.description,
                    p.image_url
                   FROM order_items oi
                   LEFT JOIN products p ON oi.product_id = p.product_id
                   WHERE oi.order_id = $order_id";

    $itemsResult = mysqli_query($conn, $itemsQuery);

    while ($item = mysqli_fetch_assoc($itemsResult)) {
        $orderItems[] = $item;
    }
}

$subtotal = $order ? (float)$order['subtotal'] : 0;
$total = $order ? (float)$order['total_amount'] : 0;
$delivery = $total - $subtotal;

$card_type = 'Visa';       // placeholder
$card_last4 = '1234';      // placeholder

$display_status = getDisplayOrderStatus($order['status'], $order['order_date']);
$status = strtolower($display_status);

$current_step = 1;
$progress_ratio = 0;
$display_status = 'Processing';
$status = 'processing';

if ($order) {
    $display_status = getDisplayOrderStatus($order['status'], $order['order_date']);
    $status = strtolower($display_status);

    $steps = [
        'processing' => [1, 0],
        'order placed' => [1, 0],
        'order packed' => [2, 0.25],
        'in transit' => [3, 0.5],
        'out for delivery' => [4, 0.75],
        'delivered' => [5, 1],
    ];

    if (isset($steps[$status])) {
        [$current_step, $progress_ratio] = $steps[$status];
    }
}

?>

<main class="account-main-content">
    <div class="account-wrapper">
        <aside class="account-sidebar">
            <div class="account-user">
                <div class="account-user-name">
                    Hi <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                </div>
                <div class="account-user-email">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
            </div>

            <nav class="account-nav">
                <a href="order_history.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Order History</span>
                </a>

                <a href="personal_details.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Personal Details</span>
                </a>

                <a href="address_book.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Addresses</span>
                </a>

                <a href="logout.php" class="account-nav-item logout">
                    <span class="icon"></span>
                    <span>Sign Out</span>
                </a>
            </nav>
        </aside>

        <main class="account-main" style="margin: 50px 20px;">

            <a href="order_history.php" style="text-decoration: none; color: inherit;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer;">
                    <img src="../../Images/Dropdown_Arrow.svg" alt="" style="width: 40px; transform: rotate(90deg);">
                    <h3 style="margin: 0;">Order Details</h3>
                </div>
            </a>

            <?php if ($order): ?>

                <div class="order-details-summary">
                    <div class="order-detail-item">
                        <span class="order-detail-label">Order Number</span>
                        <span class="order-detail-value"><?php echo $order['order_id']; ?></span>
                    </div>

                    <div class="order-detail-item">
                        <span class="order-detail-label">Order Placed</span>
                        <span class="order-detail-value"><?php echo date('M jS, Y', strtotime($order['order_date'])); ?></span>
                    </div>

                    <div class="order-detail-item">
                        <span class="order-detail-label">Order Delivered</span>
                        <span class="order-detail-value"><?php echo date('M jS, Y', strtotime($order['order_date'])); ?></span>
                    </div>

                    <div class="order-detail-item">
                        <span class="order-detail-label">No of items</span>
                        <span class="order-detail-value"><?php echo $order['item_count']; ?> <?php echo $order['item_count'] == 1 ? 'item' : 'items'; ?></span>
                    </div>

                    <div class="order-detail-item">
                        <span class="order-detail-label">Status</span>
                        <span class="order-detail-value"><?php echo htmlspecialchars($display_status); ?></span>
                    </div>
                </div>

                <section class="order-tracking-section">
                    <h4>Order Tracking</h4>

                    <div class="tracking-progress">
                        <div class="tracking-progress-bar" style="width: calc(80% * <?php echo $progress_ratio; ?>);"></div>

                        <div class="tracking-step">
                            <div class="tracking-icon">1</div>
                            <div class="tracking-dot <?php echo $current_step >= 1 ? 'is-active' : ''; ?>"></div>
                            <div class="tracking-label">Order Placed</div>
                        </div>

                        <div class="tracking-step">
                            <div class="tracking-icon">2</div>
                            <div class="tracking-dot <?php echo $current_step >= 2 ? 'is-active' : ''; ?>"></div>
                            <div class="tracking-label">Order Packed</div>
                        </div>

                        <div class="tracking-step">
                            <div class="tracking-icon">3</div>
                            <div class="tracking-dot <?php echo $current_step >= 3 ? 'is-active' : ''; ?>"></div>
                            <div class="tracking-label">In Transit</div>
                        </div>

                        <div class="tracking-step">
                            <div class="tracking-icon">4</div>
                            <div class="tracking-dot <?php echo $current_step >= 4 ? 'is-active' : ''; ?>"></div>
                            <div class="tracking-label">Out for Delivery</div>
                        </div>

                        <div class="tracking-step">
                            <div class="tracking-icon">5</div>
                            <div class="tracking-dot <?php echo $current_step >= 5 ? 'is-active' : ''; ?>"></div>
                            <div class="tracking-label">Delivered</div>
                        </div>
                    </div>
                </section>

                <section class="order-items-section">
                    <h4>Items from this order</h4>

                    <?php if (!empty($orderItems)): ?>
                        <div class="order-items-list">
                            <?php foreach ($orderItems as $item): ?>
                                <div class="order-item-row">
                                    <div class="order-item-image">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width: 80px; height: auto;">
                                    </div>

                                    <div class="order-item-info">
                                        <div class="order-item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="order-item-meta"><?php echo htmlspecialchars($item['category']); ?></div>
                                        <div class="order-item-meta"><?php echo htmlspecialchars($item['description']); ?></div>
                                        <div class="order-item-meta">Unit price: £<?php echo number_format($item['unit_price'], 2); ?></div>
                                    </div>

                                    <div class="order-item-qty">
                                        Qty: <?php echo $item['quantity']; ?>
                                    </div>

                                    <div class="order-item-total">
                                        £<?php echo number_format($item['total_price'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No items found for this order.</p>
                    <?php endif; ?>
                </section>

                <div class="order-bottom-sections">
                    <section class="order-extra-details">
                        <h4>Order Summary</h4>

                        <div class="order-summary-row">
                            <span>Card</span>
                            <span><?php echo $card_type; ?> ending <?php echo $card_last4; ?></span>
                        </div>

                        <div class="order-summary-row">
                            <span>Subtotal</span>
                            <span>£<?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="order-summary-row">
                            <span>Delivery</span>
                            <span>£<?php echo number_format($delivery, 2); ?></span>
                        </div>

                        <div class="order-summary-row total">
                            <span>Total</span>
                            <span>£<?php echo number_format($total, 2); ?></span>
                        </div>
                    </section>

                    <section class="order-address-details">
                        <h4>Addresses</h4>

                        <?php if (trim($order['billing_address']) === trim($order['shipping_address'])): ?>
                            <div class="address-block">
                                <h5>Billing & Delivery Address</h5>
                                <div class="address-text">
                                    <?php echo nl2br(htmlspecialchars($order['billing_address'])); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="address-block">
                                <h5>Billing Address</h5>
                                <div class="address-text">
                                    <?php echo nl2br(htmlspecialchars($order['billing_address'])); ?>
                                </div>
                            </div>

                            <div class="address-block">
                                <h5>Delivery Address</h5>
                                <div class="address-text">
                                    <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>

            <?php else: ?>
                <div class="empty-box">
                    <p>Order not found.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>