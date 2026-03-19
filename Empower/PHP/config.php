<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cs2team6_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// LOAD USER TYPE
if (isset($_SESSION['user_id']) && !isset($_SESSION['user_type'])) {
    $uid = (int)$_SESSION['user_id']; 
    $result = mysqli_query($conn, "SELECT user_type FROM users WHERE user_id = $uid");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_type'] = $row['user_type'];
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// SAFE OUTPUT
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// OPTIONAL SQL ESCAPE
function escape($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isStaff()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == "staff";
}

function getUserInfo()
{
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

function isDarkModeEnabled($user_id)
{
    global $conn;
    if (!$user_id) return false;

    $query = "SELECT dark_mode FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (bool)$row['dark_mode'];
    }
    return false;
}

function toggleDarkMode($user_id)
{
    global $conn;
    if (!$user_id) return false;

    $current = isDarkModeEnabled($user_id);
    $new = $current ? 0 : 1;

    $query = "UPDATE users SET dark_mode = $new WHERE user_id = $user_id";
    return mysqli_query($conn, $query);
}

// ✅ UPDATED WITH DISCOUNTS
function calculateCartTotal($user_id)
{
    global $conn;
    $total = 0;

    if ($user_id) {
        $query = "SELECT c.quantity, p.price, p.discount_percent
                  FROM cart c 
                  JOIN products p ON c.product_id = p.product_id 
                  WHERE c.user_id = $user_id";

        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $price = $row['price'];

            if (!empty($row['discount_percent']) && $row['discount_percent'] > 0) {
                $price = $price - ($price * ($row['discount_percent'] / 100));
            }

            $total += $price * $row['quantity'];
        }
    }

    return number_format($total, 2);
}

function getCartCount($user_id)
{
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

function getDisplayOrderStatus($status, $orderDate)
{
    $display_status = $status;

    if (strtolower($status) === 'processing') {
        $days_since_order = floor((time() - strtotime($orderDate)) / 86400);

        if ($days_since_order >= 4) return 'Delivered';
        if ($days_since_order >= 3) return 'Out for Delivery';
        if ($days_since_order >= 2) return 'In Transit';
        if ($days_since_order >= 1) return 'Order Packed';
    }

    return $display_status;
}