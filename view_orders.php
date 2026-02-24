<?php
$file = "orders.json";

if (!file_exists($file)) {
    echo "No orders yet.";
    exit();
}

$orders = json_decode(file_get_contents($file), true);
?>

<!DOCTYPE html>
<html>
<head>
<title>All Orders</title>
<style>
body{font-family:Arial;background:#f2f2f2;padding:20px;}
.order{
background:white;
padding:15px;
margin-bottom:15px;
border-radius:8px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<h2>All Orders</h2>

<?php foreach($orders as $order): ?>
<div class="order">
<strong>Order ID:</strong> <?= $order['order_id'] ?><br>
<strong>Name:</strong> <?= $order['name'] ?><br>
<strong>Email:</strong> <?= $order['email'] ?><br>
<strong>Total:</strong> ₦<?= number_format($order['total']) ?><br>
<strong>Date:</strong> <?= $order['date'] ?>
</div>
<?php endforeach; ?>

</body>
</html>