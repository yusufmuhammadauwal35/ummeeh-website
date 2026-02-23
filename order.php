<?php
session_start();
include("config.php");

$user_id = 1; // replace with logged-in user id

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Cart is empty");
}

$reference = "POCO" . rand(10000,99999);
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

mysqli_query($conn, "INSERT INTO orders (user_id, reference, total) 
VALUES ($user_id, '$reference', $total)");

$order_id = mysqli_insert_id($conn);

foreach ($_SESSION['cart'] as $item) {
    mysqli_query($conn, "INSERT INTO order_items (order_id, product_name, price, quantity)
    VALUES ($order_id, '{$item['name']}', {$item['price']}, {$item['quantity']})");
}

unset($_SESSION['cart']);

header("Location: order_history.php");
exit();
?>