<?php
if (!isset($_GET['order_id'])) {
    header("Location: cart.html");
    exit();
}

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Successful</title>
<style>
body{
font-family:Arial;
background:#f2f2f2;
text-align:center;
padding:80px;
}
.box{
background:white;
padding:40px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
display:inline-block;
}
h2{color:green;}
a{
display:inline-block;
margin-top:20px;
background:#f68b1e;
color:white;
padding:10px 20px;
text-decoration:none;
border-radius:5px;
}
</style>
</head>
<body>

<div class="box">
<h2>✅ Payment Successful!</h2>
<p>Order ID:</p>
<strong><?php echo htmlspecialchars($order_id); ?></strong>
<br>
<a href="cart.html">Back to Home</a>
</div>

</body>
</html>