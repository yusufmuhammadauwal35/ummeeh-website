<?php
include "config.php";

if(isset($_GET['token'])){

    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT reset_expiry FROM users WHERE reset_token=?");
    $stmt->bind_param("s",$token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $user = $result->fetch_assoc();

        if(strtotime($user['reset_expiry']) < time()){
            echo "Reset link expired!";
            exit();
        }

        if(isset($_POST['password'])){

            $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users 
                                      SET password=?, 
                                          reset_token=NULL, 
                                          reset_expiry=NULL 
                                      WHERE reset_token=?");
            $update->bind_param("ss",$newPass,$token);
            $update->execute();

            echo "Password Updated Successfully!";
            echo "<br><a href='auth.php'>Login Now</a>";
            exit();
        }

    } else {
        echo "Invalid reset link!";
        exit();
    }
}
?>

<form method="POST">
<h2>Reset Password</h2>
<input type="password" name="password" placeholder="New Password" required>
<button>Update Password</button>
</form>