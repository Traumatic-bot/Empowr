<?php
require_once 'config.php';

if (!isStaff()) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Manage Users';
require_once 'header.php';

$message = '';
$messageType = '';

<<<<<<< HEAD
// Update user type or delete
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_type'])) {
        $user_id = (int)$_POST['user_id'];
        $new_type = sanitize($_POST['user_type']);
        $updateQuery = "UPDATE users SET user_type = '$new_type' WHERE user_id = $user_id";
        if (mysqli_query($conn, $updateQuery)) {
            $message = "User type updated.";
            $messageType = 'success';
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageType = 'error';
        }
    } elseif (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['user_id'];
<<<<<<< HEAD
        // Optional: prevent deleting yourself
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        if ($user_id == $_SESSION['user_id']) {
            $message = "You cannot delete your own account.";
            $messageType = 'error';
        } else {
            $deleteQuery = "DELETE FROM users WHERE user_id = $user_id";
            if (mysqli_query($conn, $deleteQuery)) {
                $message = "User deleted.";
                $messageType = 'success';
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageType = 'error';
            }
        }
    }
}

<<<<<<< HEAD
// Fetch all users except maybe hide passwords
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$usersQuery = "SELECT user_id, title, first_name, last_name, email, phone, user_type, created_at FROM users ORDER BY user_id";
$usersResult = mysqli_query($conn, $usersQuery);
?>

<main class="account-main-content">
    <div class="account-wrapper">
        <aside class="account-sidebar">
<<<<<<< HEAD
            <!-- same sidebar with active highlight for Manage Users -->
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
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
<<<<<<< HEAD
=======
                <a href="admin_returns.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Returns</span>
                </a>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <a href="admin_users.php" class="account-nav-item is-active">
                    <span class="icon"></span>
                    <span>Manage Users</span>
                </a>
<<<<<<< HEAD
                <a href="dashboard.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Back to My Account</span>
                </a>
=======

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

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                <a href="logout.php" class="account-nav-item logout">
                    <span class="icon"></span>
                    <span>Sign Out</span>
                </a>
            </nav>
        </aside>

        <main class="account-main">
            <h1>Manage Users</h1>

            <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>" style="...">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($usersResult) > 0): ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th style="padding: 10px;">ID</th>
                        <th style="padding: 10px;">Name</th>
                        <th style="padding: 10px;">Email</th>
                        <th style="padding: 10px;">Phone</th>
                        <th style="padding: 10px;">Type</th>
                        <th style="padding: 10px;">Joined</th>
                        <th style="padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                    <tr>
                        <td style="padding: 10px;"><?php echo $user['user_id']; ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($user['title'] . ' ' . $user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td style="padding: 10px;">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <select name="user_type" onchange="this.form.submit()">
                                    <option value="customer" <?php echo $user['user_type'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    <option value="staff" <?php echo $user['user_type'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                </select>
                                <input type="hidden" name="update_type" value="1">
                            </form>
                        </td>
                        <td style="padding: 10px;"><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td style="padding: 10px;">
                            <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <input type="hidden" name="delete_user" value="1">
                                <button type="submit" style="background: none; border: none; color: #dc3545; text-decoration: underline; cursor: pointer;">Delete</button>
                            </form>
                            <?php else: ?>
                            <span style="color: #999;">Current</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No users found.</p>
            <?php endif; ?>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>