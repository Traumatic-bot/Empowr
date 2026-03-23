<<<<<<< HEAD
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
=======
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


if (isset($_SESSION['user_id']) && !isset($_SESSION['user_type'])) {
    $uid = (int)$_SESSION['user_id']; 
    $result = mysqli_query($conn, "SELECT user_type FROM users WHERE user_id = $uid");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_type'] = $row['user_type'];
    }
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

function isStaff() {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "staff") {
        return true;
    } else{
        return false;
    }
    
}

function getTextSize($user_id) {
    global $conn;
    if (!$user_id) return 'normal';
    $query = "SELECT text_size FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['text_size'];
    }
    return 'normal';
}

function toggleTextSize($user_id) {
    global $conn;
    if (!$user_id) return false;
    $current = getTextSize($user_id);
    $new = ($current === 'normal') ? 'large' : 'normal';
    $query = "UPDATE users SET text_size = '$new' WHERE user_id = $user_id";
    return mysqli_query($conn, $query);
}

function getFontScale($user_id) {
    global $conn;
    if (!$user_id) return 1.00;
    $query = "SELECT font_scale FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (float)$row['font_scale'];
    }
    return 1.00;
}

function setFontScale($user_id, $scale) {
    global $conn;
    if (!$user_id) return false;
    $scale = min(max((float)$scale, 1.00), 2.00); 
    $query = "UPDATE users SET font_scale = $scale WHERE user_id = $user_id";
    return mysqli_query($conn, $query);
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
        $query = "SELECT SUM(COALESCE(c.discounted_price, p.price) * c.quantity) as total 
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

function sendLowStockEmail($productName, $stockQuantity, $alertLevel)
{
    $to = "empowrrtech.com"; 
    $subject = "Empowr Stock Alert - " . $productName;

    if ($alertLevel == 0) {
        $levelMessage = "This product is now OUT OF STOCK.";
    } else {
        $levelMessage = "This product has dropped to {$alertLevel} or below.";
    }

    $message = "Stock Alert\n\n";
    $message .= "Product Name: " . $productName . "\n";
    $message .= "Current Stock: " . $stockQuantity . "\n";
    $message .= "Alert Level: " . ($alertLevel == 0 ? "Out of Stock" : $alertLevel) . "\n\n";
    $message .= $levelMessage . "\n\n";
    $message .= "Please restock this product as soon as possible.";

    $headers = "From: noreply@empowr.local\r\n";
    $headers .= "Reply-To: noreply@empowr.local\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}

function checkAndSendStockAlert($productId)
{
    global $conn;

    $productId = (int)$productId;

    $query = "SELECT product_name, stock_quantity,
                     alert_100_sent, alert_50_sent, alert_25_sent, alert_10_sent, alert_0_sent
              FROM products
              WHERE product_id = $productId
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        return false;
    }

    $product = mysqli_fetch_assoc($result);

    $productName = $product['product_name'];
    $stock = (int)$product['stock_quantity'];

    if ($stock <= 0 && (int)$product['alert_0_sent'] === 0) {
        if (sendLowStockEmail($productName, $stock, 0)) {
            mysqli_query($conn, "UPDATE products SET alert_0_sent = 1 WHERE product_id = $productId");
        }
    } elseif ($stock <= 10 && (int)$product['alert_10_sent'] === 0) {
        if (sendLowStockEmail($productName, $stock, 10)) {
            mysqli_query($conn, "UPDATE products SET alert_10_sent = 1 WHERE product_id = $productId");
        }
    } elseif ($stock <= 25 && (int)$product['alert_25_sent'] === 0) {
        if (sendLowStockEmail($productName, $stock, 25)) {
            mysqli_query($conn, "UPDATE products SET alert_25_sent = 1 WHERE product_id = $productId");
        }
    } elseif ($stock <= 50 && (int)$product['alert_50_sent'] === 0) {
        if (sendLowStockEmail($productName, $stock, 50)) {
            mysqli_query($conn, "UPDATE products SET alert_50_sent = 1 WHERE product_id = $productId");
        }
    } elseif ($stock <= 100 && (int)$product['alert_100_sent'] === 0) {
        if (sendLowStockEmail($productName, $stock, 100)) {
            mysqli_query($conn, "UPDATE products SET alert_100_sent = 1 WHERE product_id = $productId");
        }
    }

    return true;
}

function resetStockAlertFlags($productId, $stockQuantity)
{
    global $conn;

    $productId = (int)$productId;
    $stockQuantity = (int)$stockQuantity;

    $updates = [];

    if ($stockQuantity > 100) {
        $updates[] = "alert_100_sent = 0";
    }
    if ($stockQuantity > 50) {
        $updates[] = "alert_50_sent = 0";
    }
    if ($stockQuantity > 25) {
        $updates[] = "alert_25_sent = 0";
    }
    if ($stockQuantity > 10) {
        $updates[] = "alert_10_sent = 0";
    }
    if ($stockQuantity > 0) {
        $updates[] = "alert_0_sent = 0";
    }

    if (!empty($updates)) {
        $sql = "UPDATE products SET " . implode(", ", $updates) . " WHERE product_id = $productId";
        mysqli_query($conn, $sql);
    }
}
?>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
