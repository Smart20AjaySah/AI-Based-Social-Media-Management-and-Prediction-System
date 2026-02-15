<?php include 'home-header.php'; ?>

<main>
    <?php
        include 'conn.php';

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $sql = "SELECT posts.*, `user`.profile_image, `user`.username 
                FROM posts 
                JOIN `user` ON posts.user_id = `user`.user_id 
                WHERE posts.post_id = {$_GET['id']}";

        $result = mysqli_query($conn, $sql) or die("Query Failed.");
        $row = mysqli_fetch_assoc($result);

        // Fetch total likes
        $post_id = $row['post_id'];
        $like_query = "SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = $post_id AND liked = 1";
        $like_result = mysqli_query($conn, $like_query);
        $like_data = mysqli_fetch_assoc($like_result);
        $total_likes = $like_data['total_likes'];

        // Check if current user has liked this post
        $user_liked_query = "SELECT liked FROM likes WHERE post_id = $post_id AND user_id = $user_id";
        $user_liked_result = mysqli_query($conn, $user_liked_query);
        $user_liked = (mysqli_num_rows($user_liked_result) > 0) ? 'liked' : '';
    ?>

    <!--<div class="big-post-details-container">-->
        <div class="big-post-card" style="margin-bottom:50px";>
            <!-- Profile Pic & Username (Top Position) -->
            <div class="post-user">
                <a href="/ajay/profile/profile.php?user_id=<?php echo $row['user_id']; ?>" class="profile-container-post">
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic-post">
                    <span class="profile-pic-username"> <?php echo $row['username']; ?> </span>
                </a>
            </div>

            <!-- Post Video -->
            <video class="big-post-video" controls>
                <source src="<?php echo $row['video_url']; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Like Button -->
            <button class="like-button <?php echo $user_liked; ?>" onclick="likePost(event, <?php echo $post_id; ?>)">
                ❤️ Like (<span id="like-count-<?php echo $post_id; ?>"><?php echo $total_likes; ?></span>)
            </button>

            <!-- Post Information -->
            <div class="big-post-info">
                <p class="big-post-date"> <?php echo $row['post_date']; ?> </p>
                <h2 class="big-post-title"> <?php echo $row['title']; ?> </h2>
                <p class="big-post-category">Category: <span class="category"> <?php echo $row['category']; ?> </span></p>
                <p class="big-post-description"> <?php echo $row['description']; ?> </p>
                
                <!-- Post Link -->
                <?php if (!empty($row['link'])) { ?>
                    <a href="<?php echo $row['link']; ?>" target="_blank" class="post-link-tap"><?php echo $row['link']; ?></a>
                <?php } ?>
            </div>
            <a href="/ajay/home/complain.php?post_id=<?php echo $post_id; ?>" id="complain-button" style="margin-top:10px";>Complain Box</a>
        </div>
    <!--</div>-->
</main>

<script>
    function likePost(event, postId) {
        event.stopPropagation();

        let likeButton = event.target;
        let likeCountSpan = document.getElementById("like-count-" + postId);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/ajay/home/like.php", true);
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

        xhr.send("post_id=" + postId);
    }
</script>

<?php include 'home-footer.php'; ?>
