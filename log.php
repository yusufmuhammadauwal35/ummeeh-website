<?php
session_start();

$file = "users.json";

// Create users.json if it doesn't exist
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$users = json_decode(file_get_contents($file), true);
$message = "";
$success = false;

// ================= SIGNUP =================
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

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        $_SESSION['user'] = $name;
        header("Location: home.php");
        exit();
    }
}

// ================= LOGIN =================
if (isset($_POST['login'])) {

    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    if (!$email || !$password) {
        $message = "All fields are required!";
    } elseif (isset($users[$email]) && password_verify($password, $users[$email]['password'])) {
        $_SESSION['user'] = $users[$email]['name'];
        header("Location: home.php");
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Poco - Login / Signup</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
body{background:#f2f2f2;display:flex;justify-content:center;align-items:center;height:100vh;}
.container{background:white;width:400px;padding:30px;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.1);}
.logo{text-align:center;font-size:24px;font-weight:bold;color:#f68b1e;margin-bottom:20px;}
.tabs{display:flex;margin-bottom:20px;}
.tabs button{flex:1;padding:10px;border:none;cursor:pointer;font-weight:bold;background:#eee;}
.tabs button.active{background:#f68b1e;color:white;}
.form{display:none;}
.form.active{display:block;}
input{width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;}
.btn{width:100%;padding:10px;border:none;background:#f68b1e;color:white;font-weight:bold;cursor:pointer;border-radius:5px;}
.btn:hover{background:black;}
.message{text-align:center;margin-top:10px;font-size:14px;color:red;}
.success{color:green;}
</style>
</head>
<body>

<div class="container">
<div class="logo">Poco</div>

<div class="tabs">
<button id="loginTab" class="active" onclick="showLogin()">Login</button>
<button id="signupTab" onclick="showSignup()">Signup</button>
</div>

<!-- LOGIN FORM -->
<div id="loginForm" class="form active">
<form method="POST">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<button class="btn" name="login">Login</button>
</form>
</div>

<!-- SIGNUP FORM -->
<div id="signupForm" class="form">
<form method="POST">
<input type="text" name="name" placeholder="Full Name">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<input type="password" name="confirm" placeholder="Confirm Password">
<button class="btn" name="signup">Create Account</button>
</form>
</div>

<?php if ($message): ?>
<div class="message">
<?php echo $message; ?>
</div>
<?php endif; ?>

</div>

<script>
function showLogin(){
document.getElementById("loginForm").classList.add("active");
document.getElementById("signupForm").classList.remove("active");
document.getElementById("loginTab").classList.add("active");
document.getElementById("signupTab").classList.remove("active");
}
function showSignup(){
document.getElementById("signupForm").classList.add("active");
document.getElementById("loginForm").classList.remove("active");
document.getElementById("signupTab").classList.add("active");
document.getElementById("loginTab").classList.remove("active");
}
</script>

</body>
</html>