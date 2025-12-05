<?php
$host = 'localhost';
$username = 'cs2team6'; 
$password = 'FCyDO3BMeFyeQqthl69HyXhut'; 
$database = 'cs2team6_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper functions
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
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