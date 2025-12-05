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
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
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
            <h1>My Orders</h1>

            <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): 
                            $order_date = date('d M Y', strtotime($order['order_date']));
                            $status_class = strtolower($order['status']) == 'delivered' ? 'status-delivered' : 'status-processing';
                        ?>
                    <tr>
                        <td>#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $order_date; ?></td>
                        <td>£<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><span
                                class="order-status <?php echo $status_class; ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                        </td>
                        <td><a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="view-order">View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-box">
                <p>You haven't placed any orders yet.</p>
                <a href="products.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">
                    Start Shopping
                </a>
            </div>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>