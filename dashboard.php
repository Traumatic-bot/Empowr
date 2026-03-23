<?php
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'My Account';
require_once 'header.php';
$user_id = $_SESSION['user_id'];
require_once 'header.php';
$user_id = $_SESSION['user_id'];


$orderCount = 0;
$totalSpent = 0;
$addressCount = 0;


$orderQuery = "SELECT COUNT(*) as count FROM orders WHERE user_id = $user_id";
$orderResult = mysqli_query($conn, $orderQuery);
if ($orderResult) {
    $orderRow = mysqli_fetch_assoc($orderResult);
    $orderCount = $orderRow['count'];
}


$totalQuery = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = $user_id";
$totalResult = mysqli_query($conn, $totalQuery);
if ($totalResult) {
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalSpent = $totalRow['total'] ? number_format($totalRow['total'], 2) : '0.00';
}


$addressQuery = "SELECT COUNT(*) as count FROM user_addresses WHERE user_id = $user_id";
$addressResult = mysqli_query($conn, $addressQuery);
if ($addressResult) {
    $addressRow = mysqli_fetch_assoc($addressResult);
    $addressCount = $addressRow['count'];
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

        <main class="account-main">
            <div class="dashboard-welcome">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
                <p>Here's an overview of your account.</p>
            </div>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $orderCount; ?></div>
                    <div class="stat-label">Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">£<?php echo $totalSpent; ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $addressCount; ?></div>
                    <div class="stat-label">Saved Addresses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo getCartCount($user_id); ?></div>
                    <div class="stat-label">Cart Items</div>
                </div>
            </div>

            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="products.php" class="action-button">Shop Now</a>
                    <a href="order_history.php" class="action-button">View Orders</a>
                    <a href="checkout.php" class="action-button">View Cart</a>
                    <a href="personal_details.php" class="action-button">Edit Profile</a>
                </div>
            </div>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>