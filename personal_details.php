<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'Personal Details';
require_once 'header.php';

$user_id = $_SESSION['user_id'];

// Get user details from database
$userQuery = "SELECT title, first_name, last_name, email, phone FROM users WHERE user_id = $user_id";
$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $title = sanitize($_POST['title']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $phone = sanitize($_POST['phone']);
    
    // Check if email is being changed
    $email = sanitize($_POST['email']);
    if ($email !== $_SESSION['email']) {
        // Check if new email already exists
        $emailCheckQuery = "SELECT user_id FROM users WHERE email = '$email' AND user_id != $user_id";
        $emailCheckResult = mysqli_query($conn, $emailCheckQuery);
        
        if (mysqli_num_rows($emailCheckResult) > 0) {
            $message = "Email address already exists. Please use a different email.";
            $messageType = "error";
        } else {
            $updateQuery = "UPDATE users SET 
                           title = '$title',
                           first_name = '$first_name',
                           last_name = '$last_name',
                           email = '$email',
                           phone = '$phone'
                           WHERE user_id = $user_id";
            
            if (mysqli_query($conn, $updateQuery)) {
                // Update session variables
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;
                
                $message = "Personal details updated successfully!";
                $messageType = "success";
                
                // Refresh user data
                $userResult = mysqli_query($conn, $userQuery);
                $userData = mysqli_fetch_assoc($userResult);
            } else {
                $message = "Error updating details: " . mysqli_error($conn);
                $messageType = "error";
            }
        }
    } else {
        // Email not changed, just update other fields
        $updateQuery = "UPDATE users SET 
                       title = '$title',
                       first_name = '$first_name',
                       last_name = '$last_name',
                       phone = '$phone'
                       WHERE user_id = $user_id";
        
        if (mysqli_query($conn, $updateQuery)) {
            // Update session variables
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            $message = "Personal details updated successfully!";
            $messageType = "success";
            
            // Refresh user data
            $userResult = mysqli_query($conn, $userQuery);
            $userData = mysqli_fetch_assoc($userResult);
        } else {
            $message = "Error updating details: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
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
            <h1>Personal Details</h1>

            <?php if (isset($message)): ?>
            <div class="message <?php echo $messageType; ?>" style="
                    padding: 10px;
                    margin-bottom: 20px;
                    border-radius: 4px;
                    background: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>;
                    color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>;
                    border: 1px solid <?php echo $messageType === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;
                ">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="personal_details.php" class="details-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <select id="title" name="title"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Select Title</option>
                        <option value="Mr" <?php echo ($userData['title'] === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                        <option value="Mrs" <?php echo ($userData['title'] === 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                        <option value="Ms" <?php echo ($userData['title'] === 'Ms') ? 'selected' : ''; ?>>Ms</option>
                        <option value="Miss" <?php echo ($userData['title'] === 'Miss') ? 'selected' : ''; ?>>Miss
                        </option>
                        <option value="Dr" <?php echo ($userData['title'] === 'Dr') ? 'selected' : ''; ?>>Dr</option>
                        <option value="Prof" <?php echo ($userData['title'] === 'Prof') ? 'selected' : ''; ?>>Prof
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name*</label>
                    <input type="text" id="first_name" name="first_name"
                        value="<?php echo htmlspecialchars($userData['first_name']); ?>" required
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name*</label>
                    <input type="text" id="last_name" name="last_name"
                        value="<?php echo htmlspecialchars($userData['last_name']); ?>" required
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group">
                    <label for="email">Email Address*</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($userData['email']); ?>" required
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <button type="submit" name="update_details" class="save-btn" style="
                    background: #ffee32;
                    border: none;
                    padding: 12px 30px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    font-size: 1em;
                ">Save Changes</button>
            </form>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
                <h2>Change Password</h2>
                <p style="color: #666; margin-bottom: 20px;">You can change your password using the link below.</p>
                <a href="change_password.php" class="save-btn" style="
                    background: #f5f5f5;
                    border: 1px solid #ddd;
                    padding: 12px 30px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    text-decoration: none;
                    color: #333;
                    display: inline-block;
                ">Change Password</a>
            </div>
        </main>
    </div>
</main>

<?php require_once 'footer.php'; ?>