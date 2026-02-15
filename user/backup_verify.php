<?php
include 'conn.php'; // Database connection
session_start();

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    // тЬЕ Check in the `user` table
    $sql_user = "SELECT * FROM user WHERE email='{$email}' AND token='{$token}' AND email_verified=0";
    $result_user = mysqli_query($conn, $sql_user);

    if(mysqli_num_rows($result_user) > 0){
        // тЬЕ Update email_verified for user
        $update_user = "UPDATE user SET email_verified=1, token='' WHERE email='{$email}'";
        if(mysqli_query($conn, $update_user)){
            if(isset($_SESSION['user_id'])){
                echo "<h2 style='color:green; margin-top: 100px;'>Email verified successfully! Redirecting to your profile...</h2>";
                header("refresh:3;url=https://localhost:8080/ajay/post/user.php"); // ЁЯЯв Edit-Profile рдХреЗ рдмрд╛рдж user.php рдкрд░ redirect
            } else {
                echo "<h2 style='color:green; margin-top: 100px;'>Email verified successfully! You can now login.</h2>";
                header("refresh:3;url=https://localhost:8080/ajay/user/login.php"); // ЁЯФ╡ Register рдХреЗ рдмрд╛рдж login.php рдкрд░ redirect
            }
            exit();
        } else {
            echo "<h2 style='color:red; margin-top: 100px;'>Verification failed. Try again later.</h2>";
            exit();
        }
    }

    // тЬЕ Check in the `admin` table
    $sql_admin = "SELECT * FROM admin WHERE email='{$email}' AND token='{$token}' AND email_verified=0";
    $result_admin = mysqli_query($conn, $sql_admin);

    if(mysqli_num_rows($result_admin) > 0){
        // тЬЕ Update email_verified for admin
        $update_admin = "UPDATE admin SET email_verified=1, token='' WHERE email='{$email}'";
        if(mysqli_query($conn, $update_admin)){
            if(isset($_SESSION['admin_id'])){
                echo "<h2 style='color:green; margin-top: 100px;'>Email verified successfully! Redirecting to admin dashboard...</h2>";
                header("refresh:3;url=https://localhost:8080/ajay/owner/all-users.php"); // ЁЯЯв Edit-Profile рдХреЗ рдмрд╛рдж admin-dashboard рдкрд░ redirect
            } else {
                echo "<h2 style='color:green; margin-top: 100px;'>Email verified successfully! You can now login.</h2>";
                header("refresh:3;url=https://localhost:8080/ajay/owner/admin-login.php"); // ЁЯФ╡ Register рдХреЗ рдмрд╛рдж admin-login.php рдкрд░ redirect
            }
            exit();
        } else {
            echo "<h2 style='color:red; margin-top: 100px;'>Verification failed. Try again later.</h2>";
            exit();
        }
    }

    // тЭМ If email is not found in either table
    echo "<h2 style='color:red; margin-top: 100px;'>Invalid verification link or email already verified.</h2>";
} else {
    echo "<h2 style='color:red; margin-top: 100px;'>Invalid request.</h2>";
}

mysqli_close($conn);
?>
