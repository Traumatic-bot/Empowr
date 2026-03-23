<?php
require_once 'config.php';

if (!isStaff()) {
    header('Location: /index.php');
    exit();
}

$pageTitle = 'Admin Dashboard';

$totalProducts  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$totalOrders    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_date >= NOW() - INTERVAL 30 DAY"))['count'];
$totalRevenue   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE order_date >= NOW() - INTERVAL 30 DAY"))['total'] ?: 0;
$lowStockCount  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE stock_quantity < 5"))['count'];

$recentOrders = [];
$res = mysqli_query($conn, "SELECT o.order_id, o.order_date, o.total_amount, o.status, u.first_name, u.last_name
                             FROM orders o
                             JOIN users u ON o.user_id = u.user_id
                             ORDER BY o.order_date DESC LIMIT 5");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $recentOrders[] = $row;
}

require_once 'header.php';
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
                <a href="admin_dashboard.php" class="account-nav-item is-active">
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
                <a href="admin_users.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Users</span>
                </a>
                <p>------ Customer Dashboard------</p>
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

        <main class="account-main">
            <h1>Admin Dashboard</h1>
            <p>Welcome to the admin area. Use the navigation to manage the site.</p>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalOrders; ?></div>
                    <div class="stat-label">Orders (Last 30 Days)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">&pound;<?php echo number_format($totalRevenue, 2); ?></div>
                    <div class="stat-label">Revenue (Last 30 Days)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalProducts; ?></div>
                    <div class="stat-label">Products</div>
                </div>
            </div>

            <?php if ($lowStockCount > 0): ?>
            <div class="stat-card" style="margin-top: 20px; margin-bottom: 30px;">
                <div class="stat-value" style="color: #e74c3c;"><?php echo $lowStockCount; ?></div>
                <div class="stat-label">Products low on stock - <a href="admin_products.php">View Inventory</a></div>
            </div>
            <?php endif; ?>

            <h2 style="margin-top: 30px;">Recent Orders</h2>
            <?php if (!empty($recentOrders)): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                        <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                        <td>&pound;<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><a href="admin_order_details.php?order_id=<?php echo $order['order_id']; ?>" class="view-order">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No orders yet.</p>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>