<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conn.php';

if(isset($_GET['phone_token'])){
    $token = mysqli_real_escape_string($conn, $_GET['phone_token']);

    // ✅ Check token in User Table only
    $sql_user = "SELECT * FROM user WHERE phone_token = '$token'";
    $result_user = mysqli_query($conn, $sql_user);

    if(mysqli_num_rows($result_user) > 0){
        if(isset($_POST['reset_password'])){
            $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password'])); // ✅ MD5 Hashing

            // ✅ Update User Password
            mysqli_query($conn, "UPDATE user SET password = '$new_password', phone_token = NULL WHERE phone_token = '$token'");

            echo "<h2 style='color:green; text-align:center; margin-top:50px;'>Password has been reset successfully! <br><br> <a href='login.php'>Login Here</a></h2>";
            exit();
        }
    } else {
        echo "<h2 style='color:red; text-align:center; margin-top:50px;'>Invalid or expired token.</h2>";
        exit();
    }
} else {
    echo "<h2 style='color:red; text-align:center; margin-top:50px;'>No reset token provided.</h2>";
    exit();
}
?>

<?php include 'header.php'; ?>
<main>
    <div class="reset-password-box">
        <h2>Reset Password</h2>
        <form action="" method="POST">
            <div class="input-box">
                <input type="password" name="new_password" required>
                <label>Enter New Password</label>
            </div>
            <div class="input-box">
                <input type="submit" name="reset_password" class="btn" value="Reset Password">
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
