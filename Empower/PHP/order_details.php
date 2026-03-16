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

$query = "SELECT * FROM orders 
          WHERE order_id = $order_id 
          AND user_id = $user_id 
          LIMIT 1";

$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);
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
            <?php if ($order): ?>

                <h1>Order <?php echo $order['order_id']; ?></h1>

                <p>Purchased on <?php echo date('d M Y', strtotime($order['order_date'])); ?></p>
                <p>Order Summary: <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p>Billing address: <?php echo htmlspecialchars($order['billing_address']); ?></p>

            <?php else: ?>
                <div class="empty-box">
                    <p>Order not found.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>