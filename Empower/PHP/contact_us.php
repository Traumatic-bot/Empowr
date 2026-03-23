<?php
require_once 'config.php';
$pageTitle = 'Contact Us';
require_once 'header.php';

<<<<<<< HEAD
// Handle form submission if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Get and sanitize form data
    $name = trim($_POST['contact-name'] ?? '');
    $email = trim($_POST['contact-email'] ?? '');
    $subject = trim($_POST['contact-subject'] ?? '');
    $message = trim($_POST['contact-message'] ?? '');
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Please enter your full name.';
    }
    
    if (empty($email)) {
        $errors[] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($subject)) {
        $errors[] = 'Please enter a subject for your message.';
    }
    
    if (empty($message)) {
        $errors[] = 'Please enter your message.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Your message must be at least 10 characters long.';
    }
    
    // If no errors, proceed with sending email
    if (empty($errors)) {
        // Email configuration - CHOOSE ONE OPTION:
        
        // OPTION 1: Send to a shared email address
        $to = "support@empowrtech.com"; // Change this to your shared email
        
        // OPTION 2: Send to multiple team members (uncomment and modify as needed)
        /*
        $to = "team1@empowrtech.com, team2@empowrtech.com, team3@empowrtech.com";
        */
        
        // OPTION 3: Send to all team members from database (uncomment if you have a team members table)
        /*
        $teamQuery = "SELECT email FROM users WHERE role = 'admin' OR role = 'staff'";
        $teamResult = mysqli_query($conn, $teamQuery);
        $to = [];
        while ($row = mysqli_fetch_assoc($teamResult)) {
            $to[] = $row['email'];
        }
        $to = implode(', ', $to);
        */
        
        $email_subject = "Contact Form Message: " . $subject;
        
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Create HTML email body
        $email_body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #ffe100; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .field { margin-bottom: 20px; }
                .label { font-weight: bold; color: #1a1a1a; margin-bottom: 5px; display: block; }
                .value { background: white; padding: 10px; border-radius: 5px; border: 1px solid #ddd; }
                .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
                .badge { display: inline-block; background: #ffe100; color: #1a1a1a; padding: 5px 10px; border-radius: 5px; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>📧 New Contact Form Submission</h2>
                    <p class='badge'>Received from Empowr Website</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>👤 Name:</div>
                        <div class='value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>📧 Email:</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>📝 Subject:</div>
                        <div class='value'>" . htmlspecialchars($subject) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>💬 Message:</div>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>📅 Submitted:</div>
                        <div class='value'>" . date('F j, Y, g:i a') . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>🌐 IP Address:</div>
                        <div class='value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p>This message was sent from the Empowr contact form.<br>
                    Please reply directly to: " . htmlspecialchars($email) . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Send email
        $mail_sent = mail($to, $email_subject, $email_body, $headers);
        
        if ($mail_sent) {
            $_SESSION['contact_success'] = "Thank you for contacting us! Your message has been sent successfully. We'll get back to you within 24-48 hours.";
            
            // Optional: Send auto-reply to the customer
            $auto_reply_subject = "Thank you for contacting Empowr";
            $auto_reply_body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #ffe100; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                    .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Thank You for Contacting Empowr! 🎉</h2>
                    </div>
                    <div class='content'>
                        <p>Dear " . htmlspecialchars($name) . ",</p>
                        <p>Thank you for reaching out to us. We have received your message and will respond within 24-48 hours.</p>
                        <p><strong>Your message summary:</strong><br>
                        Subject: " . htmlspecialchars($subject) . "<br>
                        Message: " . htmlspecialchars($message) . "</p>
                        <p>If you need immediate assistance, please call us at <strong>01234 567890</strong> during business hours.</p>
                        <p>Best regards,<br>
                        <strong>The Empowr Team</strong></p>
                    </div>
                    <div class='footer'>
                        <p>This is an automated confirmation. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $auto_headers = "MIME-Version: 1.0" . "\r\n";
            $auto_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $auto_headers .= "From: support@empowrtech.com" . "\r\n";
            $auto_headers .= "Reply-To: support@empowrtech.com" . "\r\n";
            
            mail($email, $auto_reply_subject, $auto_reply_body, $auto_headers);
            
        } else {
            $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again later or call us directly.";
        }
        
        // Redirect to prevent form resubmission
        header("Location: contact_us.php");
        exit();
        
    } else {
        $_SESSION['contact_errors'] = $errors;
        header("Location: contact_us.php");
        exit();
    }
}

// Display success/error messages
if (isset($_SESSION['contact_success'])) {
    echo '<div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px auto; border-radius: 4px; border: 1px solid #c3e6cb; max-width: 1200px;">
=======
if (isset($_SESSION['contact_success'])) {
    echo '<div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #c3e6cb;">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <i class="fas fa-check-circle"></i> ' . $_SESSION['contact_success'] . '
          </div>';
    unset($_SESSION['contact_success']);
}

if (isset($_SESSION['contact_error'])) {
<<<<<<< HEAD
    echo '<div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px auto; border-radius: 4px; border: 1px solid #f5c6cb; max-width: 1200px;">
=======
    echo '<div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #f5c6cb;">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <i class="fas fa-exclamation-circle"></i> ' . $_SESSION['contact_error'] . '
          </div>';
    unset($_SESSION['contact_error']);
}

if (isset($_SESSION['contact_errors']) && !empty($_SESSION['contact_errors'])) {
<<<<<<< HEAD
    echo '<div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; margin: 20px auto; border-radius: 4px; border: 1px solid #ffeaa7; max-width: 1200px;">
            <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
            <ul style="margin: 10px 0 0 20px; padding: 0;">';
    foreach ($_SESSION['contact_errors'] as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
=======
    echo '<div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #ffeaa7;">
            <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
            <ul style="margin: 10px 0 0 20px; padding: 0;">';
    foreach ($_SESSION['contact_errors'] as $error) {
        echo '<li>' . $error . '</li>';
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    }
    echo '</ul>
          </div>';
    unset($_SESSION['contact_errors']);
}
?>

<style>
<<<<<<< HEAD
/* --- Contact Page Specific Styles --- */
:root {
    --primary-yellow: #ffe100;
    --primary-yellow-dark: #e0d129;
    --dark-color: #1a1a1a;
    --light-color: #f8f8f8;
    --secondary-color: #666;
}

.contact-content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
}

.contact-info-container h2,
.contact-form-container h2 {
    font-size: 28px;
    margin-bottom: 25px;
    color: var(--dark-color);
    position: relative;
    padding-bottom: 12px;
}

.contact-info-container h2:after,
.contact-form-container h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--primary-yellow);
}

.contact-info-content {
    background: var(--light-color);
    padding: 30px;
    border-radius: 15px;
}

.contact-info-container ul {
    list-style: none;
    padding: 0;
    margin-top: 25px;
}

.contact-info-container li {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 16px;
    color: var(--secondary-color);
}

.contact-info-container li i {
    width: 25px;
    color: var(--primary-yellow);
    font-size: 18px;
}

.contact-info-container li a {
    color: var(--secondary-color);
    text-decoration: none;
    transition: color 0.3s;
}

.contact-info-container li a:hover {
    color: var(--primary-yellow-dark);
}

.contact-image {
    margin-bottom: 25px;
}

.contact-image img {
    width: 100%;
    max-width: 300px;
    height: auto;
    border-radius: 10px;
    transition: transform 0.3s;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.contact-image img:hover {
    transform: scale(1.02);
}

/* Form Styling */
.contact-form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}
=======


.contact-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    align-items: start;
    margin-top: 10px;
}

.contact-image {
    margin-top: 0;
}

.contact-image img {
    width: 60%;
    height: auto;
    border-radius: 2px;
}

@media (max-width: 992px) {
    .contact-info-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}


>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec

.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form label {
    display: block;
<<<<<<< HEAD
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark-color);
    font-size: 14px;
=======
    margin-bottom: 6px;
    font-weight: 600;
    color: var(--dark-color);
    font-size: 0.95em;
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
}

.contact-form .required {
    color: #dc3545;
    font-weight: bold;
    margin-left: 2px;
}

.contact-form input[type="text"],
.contact-form input[type="email"],
.contact-form textarea {
    width: 100%;
<<<<<<< HEAD
    padding: 12px 15px;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.3s ease;
=======
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    font-family: inherit;
    transition: border-color 0.3s ease;
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
}

.contact-form input:focus,
.contact-form textarea:focus {
<<<<<<< HEAD
    border-color: var(--primary-yellow);
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 225, 0, 0.1);
}

.contact-form textarea {
    resize: vertical;
    min-height: 150px;
}

.button {
    display: inline-block;
    padding: 12px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    font-size: 16px;
}

.button--primary {
    background-color: var(--primary-yellow);
    color: var(--dark-color);
    width: 100%;
}

.button--primary:hover {
    background-color: var(--primary-yellow-dark);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.contact-form .form-note {
    font-size: 12px;
    color: var(--secondary-color);
    margin-top: 15px;
    text-align: center;
}

/* Page Title Section */
.page-title-section {
    background: linear-gradient(135deg, var(--light-color) 0%, white 100%);
    text-align: center;
    padding: 60px 20px;
}

.page-title-section h1 {
    font-size: 42px;
    margin-bottom: 15px;
    color: var(--dark-color);
}

.page-title-section p {
    font-size: 18px;
    color: var(--secondary-color);
    line-height: 1.6;
}

/* Responsive Design */
=======
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 5px rgba(0, 86, 179, 0.3);
}

.contact-form textarea {
    resize: none;
    min-height: 150px;
}

.contact-form button[type="submit"] {
    width: 100%;
    padding: 12px 20px;
    font-size: 1.1em;
    margin-top: 10px;
}

.contact-form .form-note {
    font-size: 0.85em;
    color: var(--secondary-color);
    margin-top: 15px;
}

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
@media (max-width: 992px) {
    .contact-content-wrapper {
        grid-template-columns: 1fr;
        gap: 40px;
<<<<<<< HEAD
        padding: 30px 20px;
    }
    
    .contact-info-container h2:after,
    .contact-form-container h2:after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .contact-info-container h2,
    .contact-form-container h2 {
        text-align: center;
    }
    
    .contact-info-container li {
        justify-content: center;
    }
    
    .contact-image {
        text-align: center;
    }
}

@media (max-width: 768px) {
    .page-title-section h1 {
        font-size: 32px;
    }
    
    .page-title-section p {
        font-size: 16px;
    }
    
    .contact-info-content {
        padding: 20px;
    }
    
    .contact-form {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .contact-info-container li {
        font-size: 14px;
        flex-wrap: wrap;
        text-align: center;
    }
    
    .button--primary {
        padding: 10px 24px;
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    }
}
</style>

<main class="main">
<<<<<<< HEAD
    <section class="page-title-section">
        <div class="container">
            <h1>Reach Out To Us</h1>
            <p>We are happy to assist you! Use the form below to send a message to our admin team, or view our contact information.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-content-wrapper">
            <div class="contact-info-container">
                <h2>Contact Information</h2>
                <div class="contact-info-content">
                    <div class="contact-image">
                        <a href="https://maps.app.goo.gl/TNNfdAcCTrwwNSJw8" target="_blank" rel="noopener noreferrer">
                            <img src="http://localhost/Empowr/Images/aston_map.png" alt="Click to view map on Google Maps">
                        </a>
                    </div>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Aston Lane, City Centre, CV1 1JB</li>
                        <li><i class="fas fa-phone-alt"></i> 01234 567890</li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:support@empowrtech.com">support@empowrtech.com</a></li>
=======

    <section class="page-title-section section"
        style="background-color: var(--light-color); padding-top: 40px; padding-bottom: 40px;">

        <div class="container">
            <h1 style="text-align: center;">Reach Out To Us</h1>
            <p style="text-align: center; max-width: 700px; margin: 0 auto;">We are happy to assist you! Use the form
                below to send a message to our admin team, or view our contact information.</p>

        </div>

    </section>



    <section class="contact-section section">



        <div class="container contact-content-wrapper">

            <div class="contact-info-container">
                <h2>Contact Information</h2>

                <div class="contact-info-content">
                    <div class="contact-image">
                        <a href="https://maps.app.goo.gl/TNNfdAcCTrwwNSJw8" target="_blank" rel="noopener noreferrer">
                            <img src="Images/aston_map.png" alt="Click to view map on Google Maps">
                        </a>
                    </div>
                    <ul class="contact-info-container">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Aston Lane, City Centre, CV1 1JB</li>
                        <li><i class="fas fa-phone-alt"></i> 01234 567890</li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:support@empowrtech.com MOCK">support@empowrtech.com
                            </a></li>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                        <li><i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 6:00 PM</li>
                        <li><i class="fas fa-clock"></i> Saturday: By Appointment</li>
                        <li><i class="fas fa-clock"></i> Sunday: Closed</li>
                    </ul>
                </div>
            </div>

<<<<<<< HEAD
            <div class="contact-form-container">
                <h2>Send Us a Message</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="contact-form">
                    <div class="form-group">
                        <label for="contact-name">Full Name <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="contact-name" required
                               placeholder="Enter your full name"
                               value="<?php echo isset($_POST['contact-name']) ? htmlspecialchars($_POST['contact-name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact-email">Email Address <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="contact-email" required
                               placeholder="Enter your email"
                               value="<?php echo isset($_POST['contact-email']) ? htmlspecialchars($_POST['contact-email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact-subject">Subject <span class="required">*</span></label>
                        <input type="text" id="contact-subject" name="contact-subject" required
                               placeholder="Enter the message subject"
                               value="<?php echo isset($_POST['contact-subject']) ? htmlspecialchars($_POST['contact-subject']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact-message">Your Message <span class="required">*</span></label>
                        <textarea id="contact-message" name="contact-message" rows="7" required
                                  placeholder="Type your message here..."><?php echo isset($_POST['contact-message']) ? htmlspecialchars($_POST['contact-message']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="button button--primary">Send Message</button>
                    </div>

                    <p class="form-note"><span class="required">*</span> Required fields. We'll respond within 24-48 hours.</p>
                </form>
            </div>
        </div>
    </section>
=======


            <div class="contact-form-container">

                <h2>Send Us a Message</h2>

                <form action="submit-message.php" method="POST" class="contact-form">

                    <div class="form-group">

                        <label for="contact-name">Full Name <span class="required">*</span></label>

                        <input type="text" id="contact-name" name="contact-name" required
                            placeholder="Enter your full name">

                    </div>

                    <div class="form-group">

                        <label for="contact-email">Email Address <span class="required">*</span></label>

                        <input type="email" id="contact-email" name="contact-email" required
                            placeholder="Enter your email">

                    </div>

                    <div class="form-group">

                        <label for="contact-subject">Subject <span class="required">*</span></label>

                        <input type="text" id="contact-subject" name="contact-subject" required
                            placeholder="Enter the message subject">

                    </div>

                    <div class="form-group">

                        <label for="contact-message">Your Message <span class="required">*</span></label>

                        <textarea id="contact-message" name="contact-message" rows="7" required
                            placeholder="Type your message here..."></textarea>

                    </div>

                    <div class="form-group">

                        <button type="submit" class="button button--primary">Send Message</button>

                    </div>

                    <p class="form-note"><span class="required">*</span> Required fields.</p>

                </form>

            </div>



        </div>
    </section>



>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
</main>

<?php require_once 'footer.php'; ?>