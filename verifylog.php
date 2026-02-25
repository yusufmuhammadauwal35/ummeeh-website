<?php
include "config.php";

if(isset($_GET['token'])){

    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $update = $conn->prepare("UPDATE users 
                                  SET is_verified = 1, 
                                      verification_token = NULL 
                                  WHERE verification_token = ?");
        $update->bind_param("s", $token);
        $update->execute();

        echo "<h2>Account Verified Successfully!</h2>";
        echo "<a href='auth.php'>Login Now</a>";

    } else {
        echo "<h2>Invalid or Expired Verification Link!</h2>";
    }
}
?>