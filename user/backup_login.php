<?php include 'header.php'; ?>
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
                <div class="input-box create-account-box">
                    <a href="https://localhost:8080/ajay/user/register.php" class="btn create-account">Create_Account</a>
                </div>
            </form>
            <?php
                include 'conn.php';

                if(isset($_POST['login'])){
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $password = md5($_POST['password']);

                    $sql = "SELECT * FROM user WHERE username='{$username}' AND password='{$password}' AND email_verified = 1";
                    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);

                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_id'] = $row['user_id'];

                        header("Location: https://localhost:8080/ajay/home/home.php");
                        exit();
                    } else {
                        echo "<div><h1 style='color: red;'>Invalid Username, Password, or Unverified Email.</h1></div>";
                    }
                }
            ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
