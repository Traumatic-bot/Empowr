<?php
//conect to database
$host = 'localhost';
$username = 'cs2team6'; 
$password = 'FCyDO3BMeFyeQqthl69HyXhut'; 
$database = 'cs2team6_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function isDarkModeEnabled($user_id) {
    global $conn;
    if (!$user_id) return false;
    
    $query = "SELECT dark_mode FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (bool)$row['dark_mode'];
    }
    return false;
}

function toggleDarkMode($user_id) {
    global $conn;
    if (!$user_id) return false;
    
    $current = isDarkModeEnabled($user_id);
    $new = $current ? 0 : 1;
    
    $query = "UPDATE users SET dark_mode = $new WHERE user_id = $user_id";
    return mysqli_query($conn, $query);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserInfo() {
    if (isLoggedIn()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'email' => $_SESSION['email']
        ];
    }
    return null;
}

function calculateCartTotal($user_id) {
    global $conn;
    $total = 0;
    
    if ($user_id) {
        $query = "SELECT SUM(p.price * c.quantity) as total 
                  FROM cart c 
                  JOIN products p ON c.product_id = p.product_id 
                  WHERE c.user_id = $user_id";
        $result = mysqli_query($conn, $query);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $total = $row['total'] ?: 0;
        }
    }
    
    return number_format($total, 2);
}

function getCartCount($user_id) {
    global $conn;
    $count = 0;
    
    if ($user_id) {
        $query = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $count = $row['count'] ?: 0;
        }
    }
    
    return $count;
}
?>