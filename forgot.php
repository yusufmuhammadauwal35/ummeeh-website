<?php
include "config.php";
$message = "";

if(isset($_POST['email'])){

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE users 
                                  SET reset_token=?, reset_expiry=? 
                                  WHERE email=?");
        $update->bind_param("sss",$token,$expiry,$email);
        $update->execute();

        echo "<h3>Reset Link:</h3>";
        echo "<a href='reset.php?token=$token'>Click Here To Reset</a>";

    } else {
        $message = "Email not found!";
    }
}
?>

<form method="POST">
<h2>Forgot Password</h2>
<input type="email" name="email" required>
<button>Send Reset Link</button>
<p style="color:red;"><?php echo $message; ?></p>
</form>