<?php
require_once 'config.php';

if (!isStaff()) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Manage Orders';
require_once 'header.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = sanitize($_POST['status']);
    $updateQuery = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";
    if (mysqli_query($conn, $updateQuery)) {
        $message = "Order #$order_id status updated to $new_status.";
        $messageType = 'success';
    } else {
        $message = "Error updating order: " . mysqli_error($conn);
        $messageType = 'error';
    }
}

$ordersQuery = "SELECT o.*, u.first_name, u.last_name, u.email 
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                ORDER BY o.order_date DESC";
$ordersResult = mysqli_query($conn, $ordersQuery);
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
                <a href="admin_orders.php" class="account-nav-item is-active">
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
            <h1>Manage Orders</h1>

            <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>" style="...">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($ordersResult) > 0): ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">Order #</th>
                        <th style="padding: 10px; text-align: left;">Customer</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                        <th style="padding: 10px; text-align: left;">Total</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($ordersResult)): ?>
                    <tr>
                        <td style="padding: 10px;">#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                        <td style="padding: 10px;"><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                        <td style="padding: 10px;">£<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td style="padding: 10px;">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <select name="status" onchange="this.form.submit()" style="padding: 5px;">
                                    <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td style="padding: 10px;">
                            <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" target="_blank">View Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No orders found.</p>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>