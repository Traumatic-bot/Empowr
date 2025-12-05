<?php
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact_us.php');
    exit;
}


$name = isset($_POST['contact-name']) ? sanitize($_POST['contact-name']) : '';
$email = isset($_POST['contact-email']) ? sanitize($_POST['contact-email']) : '';
$subject = isset($_POST['contact-subject']) ? sanitize($_POST['contact-subject']) : '';
$message = isset($_POST['contact-message']) ? sanitize($_POST['contact-message']) : '';


$errors = [];

if (empty($name)) {
    $errors[] = 'Full name is required';
}

if (empty($email)) {
    $errors[] = 'Email address is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}


if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_form_data'] = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message
    ];
    header('Location: /contact_us.php');
    exit;
}

// Email config
$to = '240111586@aston.ac.uk'; 
$email_subject = "Contact Form: $subject";
$email_body = "
<html>
<head>
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; }
        .value { padding: 5px 0; }
        .footer { margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        
        <div class='field'>
            <div class='label'>From:</div>
            <div class='value'>$name ($email)</div>
        </div>
        
        <div class='field'>
            <div class='label'>Subject:</div>
            <div class='value'>$subject</div>
        </div>
        
        <div class='field'>
            <div class='label'>Message:</div>
            <div class='value' style='white-space: pre-wrap; padding: 10px; background-color: #f5f5f5; border-radius: 3px;'>$message</div>
        </div>
        
        <div class='field'>
            <div class='label'>Submitted:</div>
            <div class='value'>" . date('F j, Y, g:i a') . "</div>
        </div>
        
        <div class='field'>
            <div class='label'>IP Address:</div>
            <div class='value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
        </div>
        
        <div class='footer'>
            This email was sent from the contact form on your website.
        </div>
    </div>
</body>
</html>
";


$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: $name <$email>" . "\r\n";
$headers .= "Reply-To: $email" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$mail_sent = mail($to, $email_subject, $email_body, $headers);

if ($mail_sent) {
    $_SESSION['contact_success'] = 'Thank you for your message! We will get back to you soon.';

    if (isset($_SESSION['contact_form_data'])) {
        unset($_SESSION['contact_form_data']);
    }
} else {
    $_SESSION['contact_error'] = 'Sorry, there was an error sending your message. Please try again later.';
    $_SESSION['contact_form_data'] = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message
    ];
}

header('Location: /contact_us.php');
exit;
?>