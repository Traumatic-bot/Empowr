<?php
require_once 'config.php';

<<<<<<< HEAD
// redirect if not logged in
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

<<<<<<< HEAD
// redirect staff to admin dashboard
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
if (isStaff()) {
    header('Location: /admin_dashboard.php');
    exit();
}

<<<<<<< HEAD
$pageTitle = 'My Account';
require_once 'header.php';

$user_id = $_SESSION['user_id'];

// ==========================
// FETCH DATA
// ==========================
=======


$pageTitle = 'My Account';
require_once 'header.php';
$user_id = $_SESSION['user_id'];
require_once 'header.php';
$user_id = $_SESSION['user_id'];


>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$orderCount = 0;
$totalSpent = 0;
$addressCount = 0;

<<<<<<< HEAD
// Orders count
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$orderQuery = "SELECT COUNT(*) as count FROM orders WHERE user_id = $user_id";
$orderResult = mysqli_query($conn, $orderQuery);
if ($orderResult) {
    $orderRow = mysqli_fetch_assoc($orderResult);
    $orderCount = $orderRow['count'];
}

<<<<<<< HEAD
// Total spent
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$totalQuery = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = $user_id";
$totalResult = mysqli_query($conn, $totalQuery);
if ($totalResult) {
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalSpent = $totalRow['total'] ? number_format($totalRow['total'], 2) : '0.00';
}

<<<<<<< HEAD
// Address count
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$addressQuery = "SELECT COUNT(*) as count FROM user_addresses WHERE user_id = $user_id";
$addressResult = mysqli_query($conn, $addressQuery);
if ($addressResult) {
    $addressRow = mysqli_fetch_assoc($addressResult);
    $addressCount = $addressRow['count'];
}
?>

<main class="account-main-content">
    <div class="account-wrapper">
<<<<<<< HEAD

        <!-- SIDEBAR -->
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
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
<<<<<<< HEAD
                <a href="order_history.php" class="account-nav-item">Order History</a>
                <a href="personal_details.php" class="account-nav-item">Personal Details</a>
                <a href="address_book.php" class="account-nav-item">Addresses</a>
                <a href="logout.php" class="account-nav-item logout">Sign Out</a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <section class="account-main">
            <div class="dashboard-welcome">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
                <p>Here’s an overview of your account.</p>
            </div>

            <!-- STATS -->
            <div class="dashboard-stats">

=======
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
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <div class="stat-card">
                    <div class="stat-value"><?php echo $orderCount; ?></div>
                    <div class="stat-label">Orders</div>
                </div>
<<<<<<< HEAD

=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <div class="stat-card">
                    <div class="stat-value">£<?php echo $totalSpent; ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
<<<<<<< HEAD

=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <div class="stat-card">
                    <div class="stat-value"><?php echo $addressCount; ?></div>
                    <div class="stat-label">Saved Addresses</div>
                </div>
<<<<<<< HEAD

=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <div class="stat-card">
                    <div class="stat-value"><?php echo getCartCount($user_id); ?></div>
                    <div class="stat-label">Cart Items</div>
                </div>
<<<<<<< HEAD

            </div>

            <!-- QUICK ACTIONS -->
            <div class="quick-actions">
                <h2>Quick Actions</h2>

=======
            </div>

            <div class="quick-actions">
                <h2>Quick Actions</h2>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <div class="action-buttons">
                    <a href="products.php" class="action-button">Shop Now</a>
                    <a href="order_history.php" class="action-button">View Orders</a>
                    <a href="checkout.php" class="action-button">View Cart</a>
                    <a href="personal_details.php" class="action-button">Edit Profile</a>
                </div>
            </div>

<<<<<<< HEAD
        </section>
=======
            <?php if (isStaff()): ?>
            <div class="admin-section" style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ffee32;">
                <h2>Admin Panel</h2>
                <div class="action-buttons">
                    <a href="admin_dashboard.php" class="action-button" style="background: #333; color: #fff;">Admin
                        Dashboard</a>
                </div>
            </div>
            <?php endif; ?>
        </main>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    </div>
</main>

<?php require_once 'footer.php'; ?>