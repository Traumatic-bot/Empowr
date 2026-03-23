<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
<<<<<<< HEAD
    $discounted_price = isset($_POST['discounted_price']) ? floatval($_POST['discounted_price']) : null;

    // check product exists
=======
    $discounted_price = isset($_POST['discounted_price']) && $_POST['discounted_price'] !== ''
        ? floatval($_POST['discounted_price'])
        : 'NULL';

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    $productQuery = "SELECT * FROM products WHERE product_id=$product_id";
    $productResult = mysqli_query($conn, $productQuery);
    if ($productResult && mysqli_num_rows($productResult) > 0) {
        $product = mysqli_fetch_assoc($productResult);
        if ($quantity > $product['stock_quantity']) {
            $_SESSION['error'] = 'Not enough stock';
<<<<<<< HEAD
            header('Location: products.php'); exit();
        }

        // check cart
        $checkQuery = "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            $updateQuery = "UPDATE cart SET quantity = quantity + $quantity, discounted_price=$discounted_price WHERE user_id=$user_id AND product_id=$product_id";
            mysqli_query($conn, $updateQuery);
        } else {
            $insertQuery = "INSERT INTO cart (user_id, product_id, quantity, discounted_price) VALUES ($user_id, $product_id, $quantity, $discounted_price)";
=======
            header('Location: /products.php');
            exit();
        }

        $checkQuery = "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            $updateQuery = "UPDATE cart 
                    SET quantity = quantity + $quantity, discounted_price = $discounted_price 
                    WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($conn, $updateQuery);
        } else {
            $insertQuery = "INSERT INTO cart (user_id, product_id, quantity, discounted_price) 
                    VALUES ($user_id, $product_id, $quantity, $discounted_price)";
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            mysqli_query($conn, $insertQuery);
        }

        $_SESSION['success'] = 'Product added to cart!';
    } else {
        $_SESSION['error'] = 'Product not found';
    }
}

header('Location: products.php');
exit();
<<<<<<< HEAD
?>
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
