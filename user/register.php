<?php 
include 'header.php'; 
include 'conn.php'; // Database connection

if(isset($_POST['register'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $phone_token = mysqli_real_escape_string($conn, md5(rand())); // Phone verification token

    // ✅ पहले चेक करेंगे कि username पहले से मौजूद है या नहीं
    $check_sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($result) > 0){
        echo "<h2 style='color:red; margin-top: 100px; margin-bottom:80px; margin-left:10px;'>Username already exists! Try a different username.</h2>";
    } else {
        // ✅ Phone Number Validation (10 digits & only numbers)
        if(!preg_match('/^[0-9]{10}$/', $phone)){
            echo "<h2 style='color:red; margin-top: 100px; margin-bottom:80px; margin-left:10px;'>Invalid phone number! Please enter a 10-digit number.</h2>";
            exit();
        }

        // ✅ Profile Picture Upload
        $target_dir = "C:/xampp/htdocs/ajay/post/profile-image/";  
        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)){
            $profile_image_url = "http://localhost:8080/ajay/post/profile-image/" . $image_name;
        } else {
            echo "<p style='color:red;'>Image upload failed.</p>";
            die();
        }

        date_default_timezone_set("Asia/Kolkata");
        $date = date("H:i M d, Y");

        // ✅ अगर username नहीं मिला तो User को database में insert करेंगे
        $sql = "INSERT INTO user (fullname, username, phone, password, register_date, profile_image, phone_verified, phone_token)
                VALUES ('{$fullname}', '{$username}', '{$phone}', '{$password}', '{$date}', '{$profile_image_url}', 0, '{$phone_token}')";

        if(mysqli_query($conn, $sql)){
            // ✅ WhatsApp verification link send करो
            $verification_link = "http://localhost:8080/ajay/user/verify.php?phone_token=" . $phone_token;
            $whatsapp_message = "Welcome! Click the link to verify your account: $verification_link";
            $whatsapp_url = "https://wa.me/91$phone?text=" . urlencode($whatsapp_message);
            
            // ✅ Show WhatsApp Button
            echo "<h2 style='color:green; text-align:center;margin-top:100px;'>
                Click the button below to send yourself the Verification link on WhatsApp:
            </h2>
            <div style='text-align:center; margin-top:20px; margin-bottom:40px;'>
                <a href='$whatsapp_url' style='background-color:#25D366; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; font-size:18px;'>
                    Open WhatsApp
                </a>
            </div>";
        } else {
            echo "<h3 style='color:red; text-align:center; margin-top:100px;'>
                Database Error: " . mysqli_error($conn) . "
            </h3>";
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
                    <input type="text" name="fullname" required>
                    <label>Full Name</label>
                </div>
                <div class="input-box">
                    <input type="number" name="phone" required maxlength="10">
                    <label>Phone Number</label>
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
