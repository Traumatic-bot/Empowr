<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    
    if (isset($_POST['remove']) && $_POST['remove'] == '1') {
        // Remove item from cart
        $query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $query);
        
        echo json_encode(['success' => true, 'message' => 'Item removed']);
        exit();
    }
    
    if (isset($_POST['quantity_change'])) {
        $change = intval($_POST['quantity_change']);
        
        // Get current quantity
        $checkQuery = "SELECT quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $cartItem = mysqli_fetch_assoc($checkResult);
            $newQuantity = $cartItem['quantity'] + $change;
            
            if ($newQuantity <= 0) {
                // Remove if quantity becomes 0 or less
                $query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
            } else {
                // Update quantity
                $query = "UPDATE cart SET quantity = $newQuantity WHERE user_id = $user_id AND product_id = $product_id";
            }
            
            mysqli_query($conn, $query);
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
        }
    }
}
?>