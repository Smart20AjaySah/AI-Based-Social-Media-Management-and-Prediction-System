<?php 
include 'post-header.php'; 
?>

<main>
    <div class="edit-profile-container">
        <div class="edit-profile-container-details">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

            <?php 
                include 'conn.php';
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM user WHERE user_id={$user_id}";
                $result = mysqli_query($conn, $sql) or die("Query Failed.");
                $row = mysqli_fetch_assoc($result);
            ?>

                <h1 class="edit-profile-container-details-header-text">UPDATE YOUR INFORMATION</h1>

                <!-- Profile Picture -->
                <label><h3 class="edit-profile-txt">Profile Picture</h3></label>
                <div class="profile-image-container">
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-img">
                </div>
                <input class="choosen-image" type="file" name="profile_image" accept="image/*">
                
                <label><h3 class="edit-profile-txt">Full Name</h3></label>
                <input type="text" name="fullname" class="edit-profile-fullname" value="<?php echo $row['fullname']; ?>" required>

                <label><h3 class="edit-profile-txt">Username</h3></label>
                <input type="text" name="username" class="edit-profile-username" value="<?php echo $row['username']; ?>" required>

                <label><h3 class="edit-profile-txt">Phone Number</h3></label>
                <input type="number" name="phone" class="edit-profile-phone" value="<?php echo $row['phone']; ?>" required maxlength="10"> 

                <label><h3 class="edit-profile-txt">Change Password (Leave blank if not changing)</h3></label>
                <input type="password" name="password" class="edit-profile-password">
                
                <label><h3 class="edit-profile-txt">Add Bio</h3></label>
                <input type="text" name="bio" class="edit-profile-username" value="<?php echo $row['bio']; ?>">

                <button type="submit" name="save-changes" class="save-changes">SAVE CHANGES</button>

            <?php
                if(isset($_POST['save-changes'])){
                    include 'conn.php';

                    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $new_phone = mysqli_real_escape_string($conn, $_POST['phone']);
                    $new_password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, md5($_POST['password'])) : $row['password'];
                    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

                    $phone_changed = ($new_phone !== $row['phone']);
                    $phone_verified = $phone_changed ? 0 : $row['phone_verified'];

                    // ✅ Profile Image Handling
                    $profile_image = $row['profile_image'];
                    if(!empty($_FILES['profile_image']['name'])){
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/post/profile-image/";
                        if (!file_exists($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
                        $target_file = $target_dir . $image_name;

                        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)){
                            $profile_image = "/post/profile-image/" . $image_name;
                        } else {
                            echo "<h2 style='color:red;'>Profile image upload failed!</h2>";
                        }
                    }

                    $phone_token = md5(rand());
                    $sql1 = "UPDATE user SET fullname='{$fullname}', username='{$username}', phone='{$new_phone}', password='{$new_password}', profile_image='{$profile_image}', phone_verified='{$phone_verified}', phone_token='{$phone_token}', bio='{$bio}' WHERE user_id={$user_id};";
                    $sql2 = "UPDATE posts SET username='{$username}', post_img='{$profile_image}' WHERE user_id={$user_id};";

                    if(mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)){
                        $_SESSION['username'] = $username;
                        $_SESSION['profile_image'] = $profile_image;

                        // ✅ WhatsApp Verification Link Send
                        if ($phone_changed) {
                            $verification_link = "/user/verify.php?phone_token=" . $phone_token;
                            $whatsapp_message = "Welcome to AjaySah.in! Click the link to verify your phone number: $verification_link";
                            $whatsapp_url = "https://wa.me/91$new_phone?text=" . urlencode($whatsapp_message);

                            echo "<h2 style='color:blue;'>Changes saved! Click <a href='$whatsapp_url' target='_blank'>here</a> to verify your new phone number.</h2>";
                        } else {
                            echo "<h2 style='color:green;'>Changes Successful</h2>";
                        }
                    } else {
                        echo "<h2 style='color:red;'>Changes Unsuccessful: " . mysqli_error($conn) . "</h2>";
                    }        
                }
            ?>
            
            </form>
        </div>
    </div>
</main>

<?php include 'post-footer.php'; ?> 
