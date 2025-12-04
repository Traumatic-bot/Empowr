<?php
require_once 'config.php';

$pageTitle = 'Login';
$error = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Check if user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if ($password === $user['password']) { 
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            
            // Redirect to dashboard
            header('Location: /dashboard.php');
            exit();
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'User not found';
    }
}

require_once 'header.php';
?>
<main>
<div class="form">
    <h1>Customer Login</h1>
    
    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="input-container">
            <input placeholder="Email Address" class="input-field" type="email" name="email" required>
            <label class="input-label">
                Email Address<span class="validity"></span>
            </label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input placeholder="Password" class="input-field" type="password" name="password" required>
            <label class="input-label">
                Password<span class="validity"></span>
            </label>
            <span class="input-highlight" style="bottom: 21px;"></span>
            <a href="./forgot_password.php" class="forgot-password">Forgotten password?</a>
        </div>

        <button class="submit" type="submit">Sign In</button>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>
</div>
</main>

<?php require_once 'footer.php'; ?>