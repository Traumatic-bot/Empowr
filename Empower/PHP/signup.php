<?php
require_once 'config.php';

$pageTitle = 'Sign Up';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password1'];
    $confirm_password = $_POST['password2'];
    
    // Validation
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'Email already registered';
        } else {
            
            $hashed_password = $password; 
            
            $query = "INSERT INTO users (title, first_name, last_name, email, phone, password) 
                      VALUES ('$title', '$first_name', '$last_name', '$email', '$phone', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $success = 'Registration successful! You can now log in.';
                // Clear form
                $_POST = array();
            } else {
                $error = 'Registration failed: ' . mysqli_error($conn);
            }
        }
    }
}

require_once 'header.php';
?>

<main>
<div class="form">
    <h1>Create Account</h1>
    
    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="color: green; margin-bottom: 15px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="input-container">
            <select class="input-field" style="width: 100%;" name="title">
                <option value="Mr" <?php echo isset($_POST['title']) && $_POST['title'] == 'Mr' ? 'selected' : ''; ?>>Mr</option>
                <option value="Mrs" <?php echo isset($_POST['title']) && $_POST['title'] == 'Mrs' ? 'selected' : ''; ?>>Mrs</option>
                <option value="Miss" <?php echo isset($_POST['title']) && $_POST['title'] == 'Miss' ? 'selected' : ''; ?>>Miss</option>
                <option value="Ms" <?php echo isset($_POST['title']) && $_POST['title'] == 'Ms' ? 'selected' : ''; ?>>Ms</option>
                <option value="Dr" <?php echo isset($_POST['title']) && $_POST['title'] == 'Dr' ? 'selected' : ''; ?>>Dr</option>
                <option value="Mx" <?php echo isset($_POST['title']) && $_POST['title'] == 'Mx' ? 'selected' : ''; ?>>Mx</option>
                <option value="Other" <?php echo isset($_POST['title']) && $_POST['title'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <label class="input-label">Title<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="first_name" placeholder="First Name" class="input-field" type="text" 
                   value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
            <label class="input-label">First Name<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="last_name" placeholder="Last Name" class="input-field" type="text" 
                   value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
            <label class="input-label">Last Name<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="email" placeholder="Email Address" class="input-field" type="email" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            <label class="input-label">Email Address<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="phone" placeholder="Telephone" class="input-field" type="text"
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            <label class="input-label">Telephone</label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="password1" placeholder="Password" class="input-field" type="password" required>
            <label class="input-label">Password<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <div class="input-container">
            <input name="password2" placeholder="Confirm Password" class="input-field" type="password" required>
            <label class="input-label">Confirm Password<span class="validity"></span></label>
            <span class="input-highlight"></span>
        </div>

        <button class="submit" type="submit">Submit</button>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </form>
</div>
</main>

<?php require_once 'footer.php'; ?>