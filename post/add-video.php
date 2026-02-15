<?php include 'post-header.php'; ?>
<?php 
include 'conn.php';

if (isset($_POST['video-post'])) {
    if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
        echo "Error: User not logged in!";
        exit;
    }

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    date_default_timezone_set("Asia/Kolkata");
    $post_date = date("H:i M d, Y");
    $video_url = ""; 

    if (!empty($_FILES['video']['name'])) {
        $allowed_formats = ["mp4", "avi", "mov", "mkv", "wmv", "flv"];
        $video_extension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));

        $video_name = time() . "_" . rand(1000, 9999) . "_video." . $video_extension;
        $target_dir = "C:/xampp/htdocs/ajay/post/uploaded-video/";
        $target_file = $target_dir . $video_name;

        if (in_array($video_extension, $allowed_formats)) {
            if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
                $video_url = "http://localhost:8080/ajay/post/uploaded-video/" . $video_name;
            } else {
                echo "Error: Video upload failed!";
                exit;
            }
        } else {
            echo "Error: Only MP4, AVI, MOV, MKV, WMV, and FLV files are allowed.";
            exit;
        }
    }

    $sql = "INSERT INTO posts (username, title, category, post_date, description, post_img, user_id, video_url, link) 
            VALUES ('$username', '$title', '$category', '$post_date', '$description', NULL, '$user_id', '$video_url', '$link')";

    if (mysqli_query($conn, $sql)){
        // Notification insert code
        $notiSQL = "INSERT INTO notifications (user_id, type, post_id, from_user_id, created_at) 
                    VALUES (?, 'video', LAST_INSERT_ID(), NULL, NOW())";
        $notiStmt = $conn->prepare($notiSQL);
        $notiStmt->bind_param("i", $user_id);
        $notiStmt->execute();
        
        header("Location: /ajay/post/user.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<main>
    <div class="add-video-adjust">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="postForm" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="200000000">  
            <input class="video-title" type="text" name="title" placeholder="Title" required>
            <select name="category" class="video-title">
                <option selected>Entertainment</option>
                <option selected>Education</option>
                <option selected>Business</option>
                <option selected>News</option>
                <option selected>Personal</option>
            </select>
            <input class="video-video" type="file" name="video" accept="video/*" id="videoInput">
            <textarea class="video-description" name="description" placeholder="Description"></textarea>
            <input type="text" name="link" class="video-title" placeholder="Add link (optional)">
            <button name="video-post" class="video-post" type="submit">Post</button>
        </form>
    </div>
</main>

<script>
document.getElementById("videoInput").addEventListener("change", function(event) {
    var file = event.target.files[0];
    if (file) {
        var video = document.createElement("video");
        video.preload = "metadata";

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            if (video.duration > 60) { 
                alert("Error: Video must be 1 minute or less.");
                event.target.value = ""; 
            }
        };

        video.src = URL.createObjectURL(file);
    }
});
</script>

<?php include 'post-footer.php'; ?>
