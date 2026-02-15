<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conn.php';

if(isset($_POST['submit'])){
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // ✅ Check if phone exists in User Table only
    $sql_user = "SELECT * FROM user WHERE phone = '$phone' AND phone_verified = 1";
    $result_user = mysqli_query($conn, $sql_user);

    if(mysqli_num_rows($result_user) > 0){
        $token = bin2hex(random_bytes(20)); // Generate Secure Token

        // ✅ Update User Token
        mysqli_query($conn, "UPDATE user SET phone_token = '$token' WHERE phone = '$phone'");

        // ✅ Generate Reset Link
        $reset_link = "/ajay/user/reset_password.php?phone_token=" . urlencode($token);

        // ✅ Generate WhatsApp Message Link
        $whatsapp_msg = urlencode("Your Password Reset Link: $reset_link \n\nClick to reset your password.");
        $whatsapp_link = "https://wa.me/91$phone?text=$whatsapp_msg";

        // ✅ Show WhatsApp Button
        echo "<h2 style='color:green; text-align:center;margin-top:-100px;'>
                Click the button below to send yourself the reset link on WhatsApp:
              </h2>
              <div style='text-align:center; margin-top:20px; margin-bottom:40px;'>
                <a href='$whatsapp_link' style='background-color:#25D366; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; font-size:18px;'>
                    Open WhatsApp
                </a>
              </div>";

    } else {
        echo "<h2 style='color:red; text-align:center; margin-top:50px;'>Phone number not found or not verified.</h2>";
    }
}
?>

<?php include 'header.php'; ?>
<main>
    <div class="forgot-password-box">
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="input-box">
                <input type="text" name="phone" required>
                <label>Enter your Phone Number</label>
            </div>
            <div class="input-box">
                <input type="submit" name="submit" class="btn" value="Send Reset Link">
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
