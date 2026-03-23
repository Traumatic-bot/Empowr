<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scale'])) {
    $scale = (float)$_POST['scale'];
    if (setFontScale($_SESSION['user_id'], $scale)) {
        $_SESSION['font_scale'] = $scale;
    }
}

$referer = $_SERVER['HTTP_REFERER'] ?? '/index.php';
header("Location: $referer");
exit();