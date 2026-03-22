<?php
require_once 'config.php';

if (!isStaff()) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Manage Returns';
require_once 'header.php';

$message     = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $return_id  = (int)$_POST['return_id'];
    $new_status = sanitize($_POST['status']);

    if (in_array($new_status, ['Pending', 'Approved', 'Rejected'])) {
        $updateQuery = "UPDATE `returns` SET status = '$new_status' WHERE return_id = $return_id";
        if (mysqli_query($conn, $updateQuery)) {
            $message     = "Return #$return_id status updated to $new_status.";
            $messageType = 'success';
        } else {
            $message     = "Error updating return: " . mysqli_error($conn);
            $messageType = 'error';
        }
    } else {
        $message     = "Invalid status value.";
        $messageType = 'error';
    }
}

$filter_status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$where = $filter_status ? "WHERE r.status = '$filter_status'" : '';

$returnsQuery = "SELECT
                    r.return_id,
                    r.order_id,
                    r.order_item_id,
                    r.reason,
                    r.status,
                    r.created_at,
                    u.first_name,
                    u.last_name,
                    u.email,
                    p.product_name,
                    p.image_url,
                    oi.quantity,
                    oi.unit_price
                 FROM `returns` r
                 JOIN users u      ON r.user_id         = u.user_id
                 JOIN order_items oi ON r.order_item_id = oi.order_item_id
                 JOIN products p   ON oi.product_id     = p.product_id
                 $where
                 ORDER BY r.created_at DESC";

$returnsResult = mysqli_query($conn, $returnsQuery);
$returns = [];
if ($returnsResult) {
    while ($row = mysqli_fetch_assoc($returnsResult)) {
        $returns[] = $row;
    }
}

$counts = [];
$countRes = mysqli_query($conn,
    "SELECT status, COUNT(*) as cnt FROM `returns` GROUP BY status");
if ($countRes) {
    while ($c = mysqli_fetch_assoc($countRes)) {
        $counts[$c['status']] = (int)$c['cnt'];
    }
}
$totalCount = array_sum($counts);
?>

<main class="account-main-content">
    <div class="account-wrapper">
        <aside class="account-sidebar">
            <div class="account-user">
                <div class="account-user-name">
                    Admin: <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                </div>
                <div class="account-user-email">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
            </div>

            <nav class="account-nav">
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
                <a href="admin_returns.php" class="account-nav-item is-active">
                    <span class="icon"></span>
                    <span>Manage Returns</span>
                </a>
                <a href="admin_users.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Users</span>
                </a>

                <p class="account-nav-section-label">------ Customer Dashboard ------</p>
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
            <h1>Manage Returns</h1>

            <?php if ($message): ?>
                <div class="admin-message admin-message--<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="ar-tabs">
                <a href="admin_returns.php"
                    class="ar-tab <?php echo $filter_status === '' ? 'is-active' : ''; ?>">
                    All <span class="ar-tab-count"><?php echo $totalCount; ?></span>
                </a>
                <a href="admin_returns.php?status=Pending"
                    class="ar-tab <?php echo $filter_status === 'Pending' ? 'is-active' : ''; ?>">
                    Pending <span class="ar-tab-count"><?php echo $counts['Pending'] ?? 0; ?></span>
                </a>
                <a href="admin_returns.php?status=Approved"
                    class="ar-tab <?php echo $filter_status === 'Approved' ? 'is-active' : ''; ?>">
                    Approved <span class="ar-tab-count"><?php echo $counts['Approved'] ?? 0; ?></span>
                </a>
                <a href="admin_returns.php?status=Rejected"
                    class="ar-tab <?php echo $filter_status === 'Rejected' ? 'is-active' : ''; ?>">
                    Rejected <span class="ar-tab-count"><?php echo $counts['Rejected'] ?? 0; ?></span>
                </a>
            </div>

            <?php if (empty($returns)): ?>
                <div class="ar-empty">
                    No return requests<?php echo $filter_status ? " with status \"$filter_status\"" : ''; ?>.
                </div>
            <?php else: ?>
                <div class="ar-list">
                    <?php foreach ($returns as $r):
                        $statusClass = strtolower($r['status']);
                    ?>
                    <div class="ar-card">
                        <div class="ar-card-header">
                            <div class="ar-card-meta">
                                <span class="ar-return-id">Return #<?php echo $r['return_id']; ?></span>
                                &nbsp;&nbsp;&nbsp;
                                <span>Order #<?php echo $r['order_id']; ?></span>
                                &nbsp;&nbsp;&nbsp;
                                <span><?php echo date('d M Y, H:i', strtotime($r['created_at'])); ?></span>
                            </div>
                            <span class="ar-status ar-status--<?php echo $statusClass; ?>">
                                <?php echo $r['status']; ?>
                            </span>
                        </div>

                        <div class="ar-card-body">
                            <div class="ar-product">
                                <?php if (!empty($r['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($r['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($r['product_name']); ?>"
                                        class="ar-product-img">
                                <?php endif; ?>
                                <div class="ar-product-info">
                                    <div class="ar-product-name"><?php echo htmlspecialchars($r['product_name']); ?></div>
                                    <div class="ar-product-meta">
                                        Qty: <?php echo (int)$r['quantity']; ?> &nbsp;·&nbsp;
                                        £<?php echo number_format($r['unit_price'], 2); ?> each
                                    </div>
                                </div>
                            </div>

                            <div class="ar-customer">
                                <div class="ar-customer-name">
                                    <?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?>
                                </div>
                                <div class="ar-customer-email">
                                    <?php echo htmlspecialchars($r['email']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="ar-reason">
                            <span class="ar-reason-label">Reason:</span>
                            <?php echo nl2br(htmlspecialchars($r['reason'])); ?>
                        </div>

                        <?php if ($r['status'] === 'Pending'): ?>
                        <div class="ar-card-actions">
                            <form method="post" class="form-inline">
                                <input type="hidden" name="return_id" value="<?php echo $r['return_id']; ?>">
                                <input type="hidden" name="status"    value="Approved">
                                <button type="submit" name="update_status"
                                    class="ar-btn ar-btn--approve">Approve</button>
                            </form>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="return_id" value="<?php echo $r['return_id']; ?>">
                                <input type="hidden" name="status"    value="Rejected">
                                <button type="submit" name="update_status"
                                    class="ar-btn ar-btn--reject">Reject</button>
                            </form>
                        </div>
                        <?php else: ?>
                        <div class="ar-card-actions">
                            <form method="post" class="form-inline">
                                <input type="hidden" name="return_id" value="<?php echo $r['return_id']; ?>">
                                <input type="hidden" name="status"    value="Pending">
                                <button type="submit" name="update_status"
                                    class="ar-btn ar-btn--reset">Reset to Pending</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>
