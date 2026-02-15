<?php 
// session_start();
include 'admin-header-user.php'; 
include 'conn.php';

if(!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])){
    die("Invalid User ID");
}
$user_id = (int) $_GET['user_id'];
$user_name = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : "";

?>

<main>
    <div class="admin-login-container">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?user_id=" . $user_id; ?>" method="post">
            <div class="input-group">
                <label for="password">Admin Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="remove-user-btn" name="remove-user">Confirm</button>
        </form>

        <?php
        if(isset($_POST['remove-user'])){
            $password = md5($_POST['password']);
            $user_name = mysqli_real_escape_string($conn, $user_name);
            $password = mysqli_real_escape_string($conn, $password);

            // 🔍 Check Admin Credentials
            $sql1 = "SELECT * FROM admin WHERE username='{$user_name}' AND password='{$password}';";
            $result = mysqli_query($conn, $sql1);

            if(mysqli_num_rows($result) > 0){

                // 🔍 Fetch Profile Image
                $fetch_user = "SELECT profile_image FROM user WHERE user_id={$user_id}";
                $user_result = mysqli_query($conn, $fetch_user);
                $user_data = mysqli_fetch_assoc($user_result);

                if (!empty($user_data['profile_image'])) {
                    $profileImgFilename = basename($user_data['profile_image']); 
                    $profileImgPath = "C:/xampp/htdocs/ajay/profile-image/" . $profileImgFilename;

                    if (file_exists($profileImgPath)) {
                        unlink($profileImgPath);
                    }
                }

                // 🔍 Fetch User's Posts to Delete Images & Videos
                $fetch_posts = "SELECT post_img, video_url FROM posts WHERE user_id={$user_id}";
                $post_result = mysqli_query($conn, $fetch_posts);

                while ($row = mysqli_fetch_assoc($post_result)) {
                    // 🖼️ Delete Post Image if Exists
                    if (!empty($row['post_img'])) {
                        $imgFilename = basename($row['post_img']); 
                        $imgPath = __DIR__ . "/uploaded-image/" . $imgFilename;

                        if (file_exists($imgPath)) {
                            unlink($imgPath);
                        }
                    }

                    // 🎥 Delete Video if Exists
                    if (!empty($row['video_url'])) {
                        $videoFilename = basename($row['video_url']); 
                        $videoPath = "C:/xampp/htdocs/ajay/uploaded-video/" . $videoFilename;

                        if (file_exists($videoPath)) {
                            unlink($videoPath);
                        }
                    }
                }

                // ✅ Delete User, Posts, Likes, Followers
                $sql = "DELETE FROM user WHERE user_id={$user_id};";  
                $sql .= "DELETE FROM posts WHERE user_id={$user_id};";  
                $sql .= "DELETE FROM likes WHERE user_id={$user_id};";  
                $sql .= "DELETE FROM followers WHERE follower_id={$user_id} OR following_id={$user_id};";  

                if(!mysqli_multi_query($conn, $sql)){
                    die("Query Failed: " . mysqli_error($conn));
                }

                header("Location: /ajay/owner/all-users.php");
                exit;

            } else {
                echo "<p style='color:red;'>Invalid Admin Password!</p>";
            }
        }
        ?>
    </div>
</main>

<?php include 'admin-footer.php'; ?>
