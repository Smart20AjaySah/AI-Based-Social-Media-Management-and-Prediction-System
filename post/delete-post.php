<?php 
include 'post-header.php'; 
include 'conn.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Invalid Post ID");
}
$post_id = (int) $_GET['id'];
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : "";

?>

<main>
    <div class="remove-post-container">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $post_id; ?>" method="post">
            <div class="remove-input-group">
                <label for="password">User Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="remove-post-btn" name="remove-post">Confirm</button>
        </form>

        <?php
        if(isset($_POST['remove-post'])){
            $password = md5($_POST['password']); // ✅ Secure password hashing check
            $user_name = mysqli_real_escape_string($conn, $user_name);
            $password = mysqli_real_escape_string($conn, $password);

            // 🔍 Step 1: Check user credentials
            $sql1 = "SELECT * FROM user WHERE username='{$user_name}' AND password='{$password}';";
            $result = mysqli_query($conn, $sql1);

            if(mysqli_num_rows($result) > 0){

                // 🔍 Step 2: Fetch post details (to delete images/videos)
                $fetchSql = "SELECT post_img, video_url FROM posts WHERE post_id={$post_id}";
                $fetchResult = mysqli_query($conn, $fetchSql);
                if ($fetchResult && mysqli_num_rows($fetchResult) > 0) {
                    $row = mysqli_fetch_assoc($fetchResult);

                    // 🖼️ Delete post image if exists
                    if (!empty($row['post_img'])) {
                        $imgFilename = basename($row['post_img']); // Extract filename from URL
                        $imgPath = "C:/xampp/htdocs/ajay/post/uploaded-image/" . $imgFilename;
                        
                        if (file_exists($imgPath)) {
                            unlink($imgPath);
                        }
                    }
                    
                    // 🎥 Delete video if exists
                    if (!empty($row['video_url'])) {
                        $videoFilename = basename($row['video_url']); // Extract filename from URL
                        $videoPath = "C:/xampp/htdocs/ajay/post/uploaded-video/" . $videoFilename;
                        
                        if (file_exists($videoPath)) {
                            unlink($videoPath);
                        }
                    }

                // 🔥 Step 3: Delete Post, Likes & Notifications
                if(mysqli_query($conn, "DELETE FROM posts WHERE post_id={$post_id}") && 
                   mysqli_query($conn, "DELETE FROM likes WHERE post_id={$post_id}") && 
                   mysqli_query($conn, "DELETE FROM notifications WHERE post_id={$post_id}")) {
                    
                    // ✅ Redirect after successful deletion
                    header("Location: /ajay/post/user.php");
                    exit;
                } else {
                    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p style='color:red;'>Invalid User Password!</p>";
            }
        }
        }
        ?>
    </div>
</main>

<?php include 'post-footer.php'; ?>  
