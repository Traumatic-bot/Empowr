<?php
require_once 'config.php';

$referer = $_SERVER['HTTP_REFERER'] ?? '/index.php';

// Logged-in //
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];

    if (toggleDarkMode($user_id)) {
        
        $enabled = isDarkModeEnabled($user_id) ? '1' : '0';
        setcookie('empowr_darkmode', $enabled, time() + 60 * 60 * 24 * 365, '/');
        header("Location: $referer");
        exit();
    }

    $_SESSION['error'] = 'Failed to toggle dark mode';
    header('Location: /index.php');
    exit();
}


$current = $_COOKIE['empowr_darkmode'] ?? '0';
$new = ($current === '1') ? '0' : '1';
setcookie('empowr_darkmode', $new, time() + 60 * 60 * 24 * 365, '/');
header("Location: $referer");
exit();
?>