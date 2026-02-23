<?php
session_start();

/*
|--------------------------------------------------------------------------
| SIMPLE USER SYSTEM (NO DATABASE)
|--------------------------------------------------------------------------
*/

// Temporary user storage (in real app this would be database)
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == "register") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $_SESSION['users'][$email] = [
        "username" => $username,
        "password" => $password
    ];

    echo "Registration successful";
    exit();
}

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == "login") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_SESSION['users'][$email])) {

        if (password_verify($password, $_SESSION['users'][$email]['password'])) {
            $_SESSION['user'] = $email;
            echo "Login successful";
        } else {
            echo "Wrong password";
        }

    } else {
        echo "User not found";
    }

    exit();
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == "logout") {
    session_destroy();
    echo "Logged out";
    exit();
}

/*
|--------------------------------------------------------------------------
| CART SYSTEM (NO DATABASE)
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['action']) && $_POST['action'] == "add_to_cart") {

    if (!isset($_SESSION['user'])) {
        echo "Login required";
        exit();
    }

    $product = $_POST['product'];
    $price   = $_POST['price'];

    if (isset($_SESSION['cart'][$product])) {
        $_SESSION['cart'][$product]['quantity']++;
    } else {
        $_SESSION['cart'][$product] = [
            "price" => $price,
            "quantity" => 1
        ];
    }

    echo "Added to cart";
    exit();
}

/*
|--------------------------------------------------------------------------
| VIEW CART
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == "view_cart") {

    if (!isset($_SESSION['user'])) {
        echo "Login required";
        exit();
    }

    $total = 0;

    foreach ($_SESSION['cart'] as $name => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;

        echo $name . " - ₦" . number_format($item['price']) .
             " x " . $item['quantity'] .
             " = ₦" . number_format($subtotal) . "<br>";
    }

    echo "<h3>Total: ₦" . number_format($total) . "</h3>";
    exit();
}
?>