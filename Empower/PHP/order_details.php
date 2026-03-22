<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'My Orders';

require_once 'header.php';

$user_id = $_SESSION['user_id'];

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
                    oi.order_item_id,
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

$card_type = 'Visa';
$card_last4 = '1234';

$current_step = 1;
$progress_ratio = 0;
$display_status = 'Processing';
$status = 'processing';

if ($order) {
    $display_status = getDisplayOrderStatus($order['status'], $order['order_date']);
    $status = strtolower($display_status);

    $steps = [
        'processing'       => [1, 0],
        'order placed'     => [1, 0],
        'order packed'     => [2, 0.25],
        'in transit'       => [3, 0.5],
        'out for delivery' => [4, 0.75],
        'delivered'        => [5, 1],
    ];

    if (isset($steps[$status])) {
        [$current_step, $progress_ratio] = $steps[$status];
    }
}

$is_delivered = ($status === 'delivered');

$existingReturns = [];
$existingReviews = [];
$serviceReview   = null;

if ($order) {
    $returnsResult = mysqli_query($conn,
        "SELECT order_item_id FROM `returns`
         WHERE order_id = $order_id AND user_id = $user_id");
    while ($r = mysqli_fetch_assoc($returnsResult)) {
        $existingReturns[$r['order_item_id']] = true;
    }

    $reviewsResult = mysqli_query($conn,
        "SELECT product_id, review_type, rating, review_text
         FROM reviews
         WHERE order_id = $order_id AND user_id = $user_id");
    while ($rv = mysqli_fetch_assoc($reviewsResult)) {
        if ($rv['review_type'] === 'service') {
            $serviceReview = $rv;
        } else {
            $existingReviews[(int)$rv['product_id']] = $rv;
        }
    }
}

$flashSuccess = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$flashError   = isset($_SESSION['error'])   ? $_SESSION['error']   : null;
unset($_SESSION['success'], $_SESSION['error']);
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
                <?php if (isStaff()): ?>
                <a href="admin_dashboard.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Dashboard</span>
                </a>
                <a href="admin_products.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Products</span>
                </a>
                <a href="admin_orders.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Orders</span>
                </a>
                <a href="admin_returns.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Returns</span>
                </a>
                <a href="admin_users.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Users</span>
                </a>
                <p class="account-nav-section-label">------ Customer Dashboard ------</p>
                <?php endif; ?>
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

        <main class="account-main">

            <a href="order_history.php" class="od-back-link">
                <div class="od-back-inner">
                    <img src="../../Images/Dropdown_Arrow.svg" alt="" class="od-back-arrow">
                    <h3>Order Details</h3>
                </div>
            </a>

            <?php if ($flashSuccess): ?>
                <div class="od-flash od-flash--success"><?php echo htmlspecialchars($flashSuccess); ?></div>
            <?php endif; ?>
            <?php if ($flashError): ?>
                <div class="od-flash od-flash--error"><?php echo htmlspecialchars($flashError); ?></div>
            <?php endif; ?>

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
                            <?php foreach ($orderItems as $item):
                                $item_id   = (int)$item['order_item_id'];
                                $product_id = (int)$item['product_id'];
                                $hasReturn  = isset($existingReturns[$item_id]);
                                $hasReview  = isset($existingReviews[$product_id]);
                                $review     = $hasReview ? $existingReviews[$product_id] : null;
                            ?>
                                <div class="order-item-row">
                                    <div class="order-item-image">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    </div>

                                    <div class="order-item-info">
                                        <div class="order-item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="order-item-meta"><?php echo htmlspecialchars($item['category']); ?></div>
                                        <div class="order-item-meta"><?php echo htmlspecialchars($item['description']); ?></div>
                                        <div class="order-item-meta">Unit price: £<?php echo number_format($item['unit_price'], 2); ?></div>

                                        <?php if ($is_delivered): ?>
                                            <div class="od-item-actions">
                                                <?php if ($hasReturn): ?>
                                                    <span class="od-tag od-tag--return">Return Requested</span>
                                                <?php else: ?>
                                                    <button type="button" class="od-action-btn od-action-btn--return"
                                                        onclick="togglePanel('return-<?php echo $item_id; ?>')">
                                                        Return Item
                                                    </button>
                                                <?php endif; ?>

                                                <?php if ($hasReview): ?>
                                                    <button type="button" class="od-action-btn od-action-btn--review"
                                                        onclick="togglePanel('review-<?php echo $item_id; ?>')">
                                                        Edit Review
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="od-action-btn od-action-btn--review"
                                                        onclick="togglePanel('review-<?php echo $item_id; ?>')">
                                                        Rate Product
                                                    </button>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!$hasReturn): ?>
                                            <div id="return-<?php echo $item_id; ?>" class="od-panel" style="display:none;">
                                                <form method="post" action="submit_return.php">
                                                    <input type="hidden" name="order_id"      value="<?php echo $order_id; ?>">
                                                    <input type="hidden" name="order_item_id" value="<?php echo $item_id; ?>">
                                                    <label class="od-panel-label">Reason for return</label>
                                                    <textarea name="reason" class="od-panel-textarea"
                                                        placeholder="Please describe the reason for your return…" required></textarea>
                                                    <div class="od-panel-footer">
                                                        <button type="submit" class="od-submit-btn">Submit Return</button>
                                                        <button type="button" class="od-cancel-btn"
                                                            onclick="togglePanel('return-<?php echo $item_id; ?>')">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <?php endif; ?>

                                            <div id="review-<?php echo $item_id; ?>" class="od-panel" style="display:none;">
                                                <form method="post" action="submit_review.php"
                                                    id="review-form-<?php echo $item_id; ?>">
                                                    <input type="hidden" name="order_id"    value="<?php echo $order_id; ?>">
                                                    <input type="hidden" name="product_id"  value="<?php echo $product_id; ?>">
                                                    <input type="hidden" name="review_type" value="product">
                                                    <input type="hidden" name="rating"      value="<?php echo $hasReview ? $review['rating'] : 0; ?>">
                                                    <label class="od-panel-label">Your rating</label>
                                                    <div class="od-stars"
                                                        data-form="review-form-<?php echo $item_id; ?>">
                                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                                            <span class="od-star <?php echo ($hasReview && $s <= $review['rating']) ? 'is-active' : ''; ?>"
                                                                data-val="<?php echo $s; ?>">&#9733;</span>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <label class="od-panel-label od-panel-label--spaced">Your review <span class="od-optional">(optional)</span></label>
                                                    <textarea name="review_text" class="od-panel-textarea"
                                                        placeholder="Share your thoughts about this product…"><?php echo $hasReview ? htmlspecialchars($review['review_text']) : ''; ?></textarea>
                                                    <div class="od-panel-footer">
                                                        <button type="submit" class="od-submit-btn">
                                                            <?php echo $hasReview ? 'Update Review' : 'Submit Review'; ?>
                                                        </button>
                                                        <button type="button" class="od-cancel-btn"
                                                            onclick="togglePanel('review-<?php echo $item_id; ?>')">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
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

                <?php if ($is_delivered): ?>
                <section class="order-service-review">
                    <h4>Rate our Service</h4>
                    <p class="od-service-desc">How was your overall experience with this order?</p>

                    <?php if ($serviceReview): ?>
                        <div class="od-existing-review">
                            <div class="od-stars od-stars--static">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <span class="od-star <?php echo $s <= $serviceReview['rating'] ? 'is-active' : ''; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <?php if (!empty($serviceReview['review_text'])): ?>
                                <p class="od-existing-text">"<?php echo htmlspecialchars($serviceReview['review_text']); ?>"</p>
                            <?php endif; ?>
                            <button type="button" class="od-action-btn od-action-btn--review od-action-btn--spaced"
                                onclick="togglePanel('service-review-panel')">
                                Edit Review
                            </button>
                        </div>
                    <?php else: ?>
                        <button type="button" class="od-action-btn od-action-btn--review"
                            onclick="togglePanel('service-review-panel')">
                            Leave a Review
                        </button>
                    <?php endif; ?>

                    <div id="service-review-panel" class="od-panel" style="display:none;">
                        <form method="post" action="submit_review.php" id="service-review-form">
                            <input type="hidden" name="order_id"    value="<?php echo $order_id; ?>">
                            <input type="hidden" name="review_type" value="service">
                            <input type="hidden" name="rating"      value="<?php echo $serviceReview ? $serviceReview['rating'] : 0; ?>">
                            <label class="od-panel-label">Your rating</label>
                            <div class="od-stars" data-form="service-review-form">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <span class="od-star <?php echo ($serviceReview && $s <= $serviceReview['rating']) ? 'is-active' : ''; ?>"
                                        data-val="<?php echo $s; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <label class="od-panel-label od-panel-label--spaced">Your comments <span class="od-optional">(optional)</span></label>
                            <textarea name="review_text" class="od-panel-textarea"
                                placeholder="Tell us about your delivery, packaging, and overall experience…"><?php echo $serviceReview ? htmlspecialchars($serviceReview['review_text']) : ''; ?></textarea>
                            <div class="od-panel-footer">
                                <button type="submit" class="od-submit-btn">
                                    <?php echo $serviceReview ? 'Update Review' : 'Submit Review'; ?>
                                </button>
                                <button type="button" class="od-cancel-btn"
                                    onclick="togglePanel('service-review-panel')">Cancel</button>
                            </div>
                        </form>
                    </div>
                </section>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-box">
                    <p>Order not found.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</main>

<script>
function togglePanel(id) {
    var panel = document.getElementById(id);
    if (!panel) return;
    panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.od-stars').forEach(function (starGroup) {
        var formId = starGroup.getAttribute('data-form');
        var form   = formId ? document.getElementById(formId) : null;
        var stars  = starGroup.querySelectorAll('.od-star');

        stars.forEach(function (star, idx) {
            star.addEventListener('mouseenter', function () {
                stars.forEach(function (s, i) {
                    s.classList.toggle('is-hover', i <= idx);
                });
            });
            star.addEventListener('mouseleave', function () {
                stars.forEach(function (s) { s.classList.remove('is-hover'); });
            });
            star.addEventListener('click', function () {
                var val = parseInt(star.getAttribute('data-val'));
                if (form) {
                    form.querySelector('input[name="rating"]').value = val;
                }
                stars.forEach(function (s, i) {
                    s.classList.toggle('is-active', i < val);
                });
            });
        });
    });
});
</script>

<?php require_once 'footer.php'; ?>
