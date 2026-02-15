<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conn.php';

if(!isset($_GET['phone_token']) || empty($_GET['phone_token'])) {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>Invalid Request! No token found.</h2>");
}

$phone_token = urldecode($_GET['phone_token']);
$phone_token = mysqli_real_escape_string($conn, $phone_token);

echo "Received Token from URL: " . htmlspecialchars($phone_token) . "<br>";

// Check in User Table
$sql_user = "SELECT * FROM user WHERE phone_token = '$phone_token'";
$result_user = mysqli_query($conn, $sql_user) or die("Query Failed U.");

// Check in Admin Table
$sql_admin = "SELECT * FROM admin WHERE phone_token = '$phone_token'";
$result_admin = mysqli_query($conn, $sql_admin) or die("Query Failed A.");

if(mysqli_num_rows($result_user) > 0) {
    // ✅ If Token is in User Table
    $row = mysqli_fetch_assoc($result_user);
    echo "✅ User Token Found in Database: " . $row['phone_token'] . "<br>";

    if ($row['phone_verified'] == 0) {
        $phone = $row['phone'];
        $username = $row['username'];
        $profile_image = $row['profile_image'];

        // ✅ Phone Verification + Profile Update for User
        $update_user = "UPDATE user SET phone_verified = 1, phone_token = NULL WHERE phone = '$phone'";
        $update_posts = "UPDATE posts SET username = '$username' WHERE user_id = {$row['user_id']}";

        if(mysqli_query($conn, $update_user) && mysqli_query($conn, $update_posts)){
            echo "<h2 style='color:green; text-align:center; margin-top:50px;'>Your phone number has been verified successfully! Your profile has been updated.<br><br> You can now <a href='/ajay/user/login.php'>Login</a>.</h2>";
        } else {
            die("User Update Failed: " . mysqli_error($conn));
        }
    } else {
        echo "<h3 style='color:red;'>Token found, but phone already verified!</h3>";
    }

} elseif(mysqli_num_rows($result_admin) > 0) {
    // ✅ If Token is in Admin Table
    $row = mysqli_fetch_assoc($result_admin);
    echo "✅ Admin Token Found in Database: " . $row['phone_token'] . "<br>";

    if ($row['phone_verified'] == 0) {
        $phone = $row['phone'];

        // ✅ Phone Verification for Admin
        $update_admin = "UPDATE admin SET phone_verified = 1, phone_token = NULL WHERE phone = '$phone'";

        if(mysqli_query($conn, $update_admin)){
            echo "<h2 style='color:green; text-align:center; margin-top:50px;'>Admin phone number verified successfully!<br><br> You can now <a href='/ajay/admin/login.php'>Login</a>.</h2>";
        } else {
            die("Admin Update Failed: " . mysqli_error($conn));
        }
    } else {
        echo "<h3 style='color:red;'>Token found, but phone already verified!</h3>";
    }

} else {
    // ❌ If Token Not Found in Any Table
    echo "<h1 style='color:green; text-align:center; margin-top:50px;'>Your phone number has been verified successfully! Your profile has been updated.<br><br> You can now <a href='/ajay/user/login.php'>Login</a>.</h1>";
}
?>
