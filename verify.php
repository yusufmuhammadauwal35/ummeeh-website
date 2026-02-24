<?php
header("Content-Type: application/json");
require "db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['reference'])) {
    echo json_encode(["status" => "error"]);
    exit();
}

$reference = $data['reference'];
$secretKey = "sk_test_41b6ac45355d68a73c2af4f545ee8d506270d6c4"; // 🔴 PUT YOUR SECRET KEY

// Verify payment from Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secretKey",
        "Cache-Control: no-cache"
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$result = json_decode($response, true);

if ($result && $result['data']['status'] === "success") {

    $order_id = "POCO-" . time();

    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $address = $conn->real_escape_string($data['address']);
    $total = $data['total'];

    // Insert into orders table
    $conn->query("
        INSERT INTO orders 
        (order_id, reference, customer_name, customer_email, customer_phone, customer_address, total_amount)
        VALUES 
        ('$order_id', '$reference', '$name', '$email', '$phone', '$address', '$total')
    ");

    // Insert each product into order_items
    foreach ($data['cart'] as $item) {

        $product = $conn->real_escape_string($item['name']);
        $price = $item['price'];
        $qty = $item['quantity'];

        $conn->query("
            INSERT INTO order_items
            (order_id, product_name, price, quantity)
            VALUES
            ('$order_id', '$product', '$price', '$qty')
        ");
    }

    echo json_encode([
        "status" => "success",
        "order_id" => $order_id
    ]);

} else {
    echo json_encode(["status" => "error"]);
}
?>