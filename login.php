<?php
session_start();

$usersFile = "users.json";

if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([]));
}

$users = json_decode(file_get_contents($usersFile), true);

$message = "";
$success = "";

/* ================= SIGNUP ================= */
if (isset($_POST['signup'])) {

    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (!$name || !$email || !$password || !$confirm) {
        $message = "All fields are required!";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters!";
    } elseif (isset($users[$email])) {
        $message = "Email already registered!";
    } else {

        $users[$email] = [
            "name" => $name,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        file_put_contents($usersFile, json_encode($users));

        $success = "Account created successfully!";
    }
}

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    if (!$email || !$password) {
        $message = "All fields are required!";
    } elseif (!isset($users[$email])) {
        $message = "Invalid email or password!";
    } elseif (!password_verify($password, $users[$email]['password'])) {
        $message = "Invalid email or password!";
    } else {

        $_SESSION['user'] = $users[$email]['name'];
        header("Location: index.php");
        exit();
    }
}
?>