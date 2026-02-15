<?php 
include 'admin-header.php'; 
include 'conn.php';
?>

<main>
    <div class="admin-login-container">
        <h2>Admin Login</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="admin-login" class="login-btn">Login</button>

            <?php
            if(isset($_POST['admin-login'])){
                include 'conn.php';

                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = md5($_POST['password']); // ✅ Encrypted password check

                $sql = "SELECT * FROM admin WHERE username='{$username}' AND password='{$password}'";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);

                    // ✅ Correct Session Variables
                    $_SESSION['admin_username'] = $row['username'];
                    $_SESSION['admin_id'] = $row['adm_id'];

                    // ✅ Redirect to admin dashboard or register page
                    header("Location: /ajay/owner/admin-register.php");
                    exit();
                } else {
                    echo "<div><h1 style='color: red;'>Invalid Username or Password.</h1></div>";
                }
            }
            ?>
        </form>
    </div>
</main>

<?php include 'admin-footer.php'; ?>
