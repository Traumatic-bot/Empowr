<?php
require_once 'config.php';

// START SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// CHECK REQUEST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact_us.php');
    exit;
}

// SAFE SANITIZE FUNCTION (if missing)
if (!function_exists('sanitize')) {
    function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

// GET DATA
$name = isset($_POST['contact-name']) ? sanitize($_POST['contact-name']) : '';
$email = isset($_POST['contact-email']) ? sanitize($_POST['contact-email']) : '';
$subject = isset($_POST['contact-subject']) ? sanitize($_POST['contact-subject']) : '';
$message = isset($_POST['contact-message']) ? sanitize($_POST['contact-message']) : '';

$errors = [];

// VALIDATION
if (empty($name)) $errors[] = 'Full name is required';
if (empty($email)) $errors[] = 'Email is required';
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if (empty($subject)) $errors[] = 'Subject is required';
if (empty($message)) $errors[] = 'Message is required';

// IF ERRORS
if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_form_data'] = compact('name','email','subject','message');
    header('Location: contact_us.php');
    exit;
}

// EMAIL (SIMULATED FOR XAMPP)
$mail_sent = true;

// RESULT
if ($mail_sent) {
    $_SESSION['contact_success'] = 'Message sent successfully!';
    unset($_SESSION['contact_form_data']);
} else {
    $_SESSION['contact_error'] = 'Failed to send message.';
    $_SESSION['contact_form_data'] = compact('name','email','subject','message');
}

header('Location: contact_us.php');
exit;
?>