<?php 
include 'header.php'; 
include 'conn.php'; // Database connection

if(isset($_POST['register'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $token = md5(rand()); // Random token generate होगा

    // ✅ पहले चेक करेंगे कि username पहले से मौजूद है या नहीं
    $check_sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($result) > 0){
        echo "<h2 style='color:red; margin-top: 100px; margin-bottom:80px; margin-left:10px;'>Username already exists! Try diffeent Username.</h2>";
    } else {
        // ✅ Profile Picture Upload
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/ajay/post/profile-image/";  
        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)){
            $profile_image_url = "https://localhost:8080/ajay/post/profile-image/" . $image_name;
        } else {
            echo "<p style='color:red;'>Image upload failed.</p>";
            die();
        }

        date_default_timezone_set("ASIA/KOLKATA");
        $date = date("H:i M d, Y");

        // ✅ अगर username नहीं मिला तो User को database में insert करेंगे
        $sql = "INSERT INTO user (fullname, username, email, password, register_date, profile_image, email_verified, token)
                VALUES ('{$fullname}', '{$username}', '{$email}', '{$password}', '{$date}', '{$profile_image_url}', 0, '{$token}')";

        if(mysqli_query($conn, $sql)){
            include($_SERVER['DOCUMENT_ROOT'] . "/ajay/email/send_email.php");

            // User registration के बाद email भेजो
            if (sendVerificationEmail($email, $fullname, $token)) {
                echo "<h2 style='color:green; margin-top: 100px; margin-bottom:80px; margin-left:10px;'>Registration successful! Check your email to verify your account.</h2>";
            } else {
                echo "<h2 style='color:red; margin-top: 100px; margin-bottom:80px; margin-left:10px;'>Email sending failed! Please try again.</h2>";
            }
        }
    }

    mysqli_close($conn);
}
?>

<main>
    <div class="container">
        <div class="register-box">
            <h2>Create New Account</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <div class="input-box">
                    <input type="text" name="fullname" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest".rand(000,999); ?>" required>
                    <label>Full Name</label>
                </div>
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <input type="file" name="profile_image" accept="image/*" required>
                </div>
                <div class="input-box">
                    <input type="submit" name="register" class="btn" value="Register">
                </div>
                <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>  
