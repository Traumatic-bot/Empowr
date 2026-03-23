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

$user_id       = (int)$_SESSION['user_id'];
$order_id      = isset($_POST['order_id'])      ? (int)$_POST['order_id']      : 0;
$order_item_id = isset($_POST['order_item_id']) ? (int)$_POST['order_item_id'] : 0;
$reason        = isset($_POST['reason'])        ? sanitize($_POST['reason'])    : '';

if (!$order_id || !$order_item_id || empty($reason)) {
    $_SESSION['error'] = 'Please provide a reason for your return.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

$check = mysqli_query($conn,
    "SELECT oi.order_item_id
     FROM orders o
     JOIN order_items oi ON o.order_id = oi.order_id
     WHERE o.order_id = $order_id
       AND o.user_id  = $user_id
       AND oi.order_item_id = $order_item_id
     LIMIT 1"
);
if (!$check || mysqli_num_rows($check) === 0) {
    $_SESSION['error'] = 'Invalid return request.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

$existing = mysqli_query($conn,
    "SELECT return_id FROM `returns`
     WHERE order_item_id = $order_item_id AND user_id = $user_id
     LIMIT 1"
);
if ($existing && mysqli_num_rows($existing) > 0) {
    $_SESSION['error'] = 'You have already submitted a return request for this item.';
    header("Location: order_details.php?order_id=$order_id");
    exit();
}

$insert = mysqli_query($conn,
    "INSERT INTO `returns` (order_id, order_item_id, user_id, reason, status)
     VALUES ($order_id, $order_item_id, $user_id, '$reason', 'Pending')"
);

if ($insert) {
    $_SESSION['success'] = 'Your return request has been submitted. We will be in touch shortly.';
} else {
    $_SESSION['error'] = 'Failed to submit return request. Please try again.';
}

header("Location: order_details.php?order_id=$order_id");
exit();
?>
