<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (toggleDarkMode($user_id)) {
    $referer = $_SERVER['HTTP_REFERER'] ?? '/index.php';
    header("Location: $referer");
} else {
    $_SESSION['error'] = 'Failed to toggle dark mode';
    header('Location: /index.php');
}
exit();
?>