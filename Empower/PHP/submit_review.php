<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order_history.php');
    exit();
}

$user_id     = (int)$_SESSION['user_id'];
$order_id    = isset($_POST['order_id'])    ? (int)$_POST['order_id']          : 0;
$review_type = isset($_POST['review_type']) ? sanitize($_POST['review_type'])  : '';
$rating      = isset($_POST['rating'])      ? (int)$_POST['rating']            : 0;
$review_text = isset($_POST['review_text']) ? sanitize($_POST['review_text'])  : '';
$product_id  = isset($_POST['product_id'])  ? (int)$_POST['product_id']        : null;

if (!$order_id || !in_array($review_type, ['product', 'service']) || $rating < 1 || $rating > 5) {
    $_SESSION['error'] = 'Invalid review submission.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

if ($review_type === 'product' && !$product_id) {
    $_SESSION['error'] = 'No product specified for review.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

$check = mysqli_query($conn,
    "SELECT order_id FROM orders WHERE order_id = $order_id AND user_id = $user_id LIMIT 1"
);
if (!$check || mysqli_num_rows($check) === 0) {
    $_SESSION['error'] = 'Invalid order.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

if ($review_type === 'product') {
    $itemCheck = mysqli_query($conn,
        "SELECT order_item_id FROM order_items
         WHERE order_id = $order_id AND product_id = $product_id LIMIT 1"
    );
    if (!$itemCheck || mysqli_num_rows($itemCheck) === 0) {
        $_SESSION['error'] = 'You can only review products from this order.';
        header("Location: order_details.php?order_id=$order_id");
        exit();
    }
}

$productVal = ($review_type === 'product' && $product_id) ? $product_id : 'NULL';

$insert = mysqli_query($conn,
    "INSERT INTO reviews (user_id, order_id, product_id, review_type, rating, review_text)
     VALUES ($user_id, $order_id, $productVal, '$review_type', $rating, '$review_text')
     ON DUPLICATE KEY UPDATE rating = $rating, review_text = '$review_text'"
);

if ($insert) {
    $_SESSION['success'] = $review_type === 'service'
        ? 'Thank you for rating our service!'
        : 'Thank you for your product review!';
} else {
    $_SESSION['error'] = 'Failed to submit review. You may have already reviewed this.';
}

header("Location: order_details.php?order_id=$order_id");
exit();
?>
