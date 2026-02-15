<?php 
include 'header.php';
include 'conn.php';
// session_start(); // Session Start

if (isset($_SESSION['username'])) {
    header("Location: /ajay/home/home.php");
    exit();
}

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $remember = isset($_POST['remember']); // Check if "Remember Me" is ticked

    // ✅ Check phone_verified also
    $sql = "SELECT * FROM user WHERE username='{$username}' AND password='{$password}' AND phone_verified = 1";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        // Set session
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['user_id'];

        // ✅ Remember Me logic
        if ($remember) {
            $token = bin2hex(random_bytes(32)); // Generate secure token
            setcookie("login_token", $token, time() + (30 * 24 * 60 * 60), "/", "", true, true); // 30 days

            // Store token in database
            $updateToken = "UPDATE user SET login_token='$token' WHERE user_id='{$row['user_id']}'";
            
            mysqli_query($conn, $updateToken);
        }

        header("Location: /ajay/home/home.php");
        exit();
    } else {
        echo "<div><h1 style='color: red;'>Invalid Username, Password, or Unverified Phone Number.</h1></div>";
    }
}
?>

<main>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="input-box">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <input type="submit" name="login" class="btn" value="Login">
                </div>
                <div class="remember-box">
                    <input type="checkbox" name="remember" style="margin-bottom:30px";> Remember Me
                </div>
                <div class="create-account-box">
                    <a href="/ajay/user/register.php" class="btn create-account">Sign Up</a>
                </div>
                <div class="forgot-password-box-link">
                    <a href="/ajay/user/forgot_password.php" class="forgot-password">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>  
