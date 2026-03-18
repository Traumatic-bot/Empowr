<?php
require_once 'config.php';

if (!isStaff()) {
    header('Location: /index.php');
    exit();
}

$pageTitle = 'Admin Dashboard';
require_once 'header.php';


$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
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
                <p>------defaut dashboard------</p>
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
                    <div class="stat-value"><?php echo $totalProducts; ?></div>
                    <div class="stat-label">Products</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalOrders; ?></div>
                    <div class="stat-label">Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Users</div>
                </div>
            </div>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>