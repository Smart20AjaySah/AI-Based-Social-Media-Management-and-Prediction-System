<?php include 'home-header.php'; ?>
<?php 
    include 'conn.php';
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($post_id == 0) {
        die("Invalid post ID.");
    }

    // Fetch post details
    $stmt = $conn->prepare("SELECT posts.*, `user`.profile_image, `user`.username 
                            FROM posts 
                            JOIN `user` ON posts.user_id = `user`.user_id 
                            WHERE posts.post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("No post found for this ID.");
    }

    $row = $result->fetch_assoc();

    // Fetch total likes
    $like_stmt = $conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = ? AND liked = 1");
    $like_stmt->bind_param("i", $post_id);
    $like_stmt->execute();
    $like_result = $like_stmt->get_result();
    $like_data = $like_result->fetch_assoc();
    $total_likes = $like_data['total_likes'] ?? 0;

    // Check if user liked this post
    $user_liked_stmt = $conn->prepare("SELECT liked FROM likes WHERE post_id = ? AND user_id = ?");
    $user_liked_stmt->bind_param("ii", $post_id, $user_id);
    $user_liked_stmt->execute();
    $user_liked_result = $user_liked_stmt->get_result();
    $user_liked = ($user_liked_result->num_rows > 0) ? 'liked' : '';
?>

<div class="big-post-card" style="margin-bottom:50px; margin-top:80px; margin-left:10px;";>
    <div class="post-user">
        <a href="/ajay/profile/profile.php?user_id=<?php echo $row['user_id']; ?>" class="profile-container-post">
            <img src="<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Picture" class="profile-pic-post">
            <span class="profile-pic-username"><?php echo htmlspecialchars($row['username']); ?></span>
        </a>
    </div>

    <img src="<?php echo htmlspecialchars($row['post_img']); ?>" 
         alt="Post Image" 
         class="big-post-image" 
         onclick="openFullImage(this.src)">

    <div class="big-post-info">
        <button class="like-button <?php echo $user_liked; ?>" onclick="likePost(event, <?php echo $post_id; ?>, <?php echo $user_id; ?>)">
            ❤️ Like (<span id="like-count-<?php echo $post_id; ?>"><?php echo $total_likes; ?></span>)
        </button>
        <p class="big-post-date"><?php echo htmlspecialchars($row['post_date']); ?></p>
        <h2 class="big-post-title"><?php echo htmlspecialchars($row['title']); ?></h2>
        <p class="big-post-category">Category: <span class="category"><?php echo htmlspecialchars($row['category']); ?></span></p>
        <p class="big-post-description"><?php echo htmlspecialchars($row['description']); ?></p>
        
        <?php if (!empty($row['link'])) { ?>
            <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" class="post-link-tap"><?php echo htmlspecialchars($row['link']); ?></a>
        <?php } ?>
    </div>
    <a href="complain.php?post_id=<?php echo $post_id; ?>" id="complain-button" style="margin-top:10px;">Complain Box</a>
</div>

<div id="image-modal" class="image-modal">
    <span class="close-btn" onclick="closeFullImage()">✖</span>
    <img id="full-image" class="modal-content">
</div>

<script>
    function openFullImage(src) {
        document.getElementById("image-modal").style.display = "flex";
        document.getElementById("full-image").src = src;
    }

    function closeFullImage() {
        document.getElementById("image-modal").style.display = "none";
    }

    function likePost(event, postId, userId) {
        event.stopPropagation();

        let likeButton = event.target.closest(".like-button");
        let likeCountSpan = document.getElementById("like-count-" + postId);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "like.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                likeCountSpan.innerText = response.likes;

                if (response.liked) {
                    likeButton.classList.add("liked");
                } else {
                    likeButton.classList.remove("liked");
                }
            }
        };

        xhr.send("post_id=" + postId + "&user_id=" + userId);
    }
</script>

<?php include 'home-footer.php'; ?>
