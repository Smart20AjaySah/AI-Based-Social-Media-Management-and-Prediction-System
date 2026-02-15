<?php include 'admin-header-user.php'; ?>

<main>
    <div class="admin-register-container">
        <h2>Add New Admin</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="new-username">Username</label>
                <input type="text" id="new-username" name="new_username" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" pattern="[0-9]{10}" maxlength="10" required>
            </div>
            <div class="input-group">
                <label for="new-password">Password</label>
                <input type="password" id="new-password" name="new_password" required>
            </div>
            <button name="admin-register" type="submit" class="register-admin-btn">SAVE</button>

            <?php
                if(isset($_POST['admin-register'])){
                    include 'conn.php';

                    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
                    $username = mysqli_real_escape_string($conn, $_POST['new_username']);
                    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                    $password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date("H:i M d, Y");

                    // Check if username already exists
                    $check_username = "SELECT username FROM admin WHERE username = '$username'";
                    $username_result = mysqli_query($conn, $check_username);

                    if (mysqli_num_rows($username_result) > 0) {
                        echo "<h2 style='color:red;'>Username already exists! Choose a different one.</h2>";
                    } else {
                        $sql = "INSERT INTO admin (fullname, username, phone, password, register_date)
                                VALUES ('{$fullname}', '{$username}', '{$phone}', '{$password}', '{$date}')";

                        if(mysqli_query($conn, $sql)){
                            echo "<h2 style='color:blue;'>Registration successful!</h2>";
                        } else {
                            echo "<h2 style='color:red;'>Registration Unsuccessful</h2>";
                        }
                    }
                }
            ?>
        </form>
    </div>
</main>

<?php include 'admin-footer-user.php'; ?>
