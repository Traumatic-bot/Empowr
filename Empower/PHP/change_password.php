<?php
require_once 'config.php';

$pageTitle = 'Change Password';
$error = '';
$success = '';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } else {
        $query = "SELECT password FROM users WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if ($current_password === $user['password']) {
                $safe_new_password = sanitize($new_password);

                $updateQuery = "UPDATE users SET password = '$safe_new_password' WHERE user_id = $user_id";
                if (mysqli_query($conn, $updateQuery)) {
                    $success = 'Password changed successfully';
                } else {
                    $error = 'Failed to update password';
                }
            } else {
                $error = 'Current password is incorrect';
            }
        } else {
            $error = 'User not found';
        }
    }
}

require_once 'header.php';
?>

<main>
    <div class="form">
        <a href="dashboard.php" style="text-decoration: none; color: inherit;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer;">
                <img src="../../Images/Dropdown_Arrow.svg" alt="" style="width: 40px; transform: rotate(90deg);">
                <h3 style="margin: 0;">Go Back</h3>
            </div>
        </a>

        <h1>Change Password</h1>

        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="input-container">
                <input placeholder="Current Password" class="input-field" type="password" name="current_password" required>
                <label class="input-label">
                    Current Password<span class="validity"></span>
                </label>
                <span class="input-highlight"></span>
            </div>

            <div class="input-container">
                <input placeholder="New Password" class="input-field" type="password" name="new_password" required>
                <label class="input-label">
                    New Password<span class="validity"></span>
                </label>
                <span class="input-highlight"></span>
            </div>

            <div class="input-container">
                <input placeholder="Confirm Password" class="input-field" type="password" name="confirm_password" required>
                <label class="input-label">
                    Confirm Password<span class="validity"></span>
                </label>
                <span class="input-highlight"></span>
            </div>

            <button class="submit" type="submit">Change Password</button>
        </form>
    </div>
</main>

<?php require_once 'footer.php'; ?>