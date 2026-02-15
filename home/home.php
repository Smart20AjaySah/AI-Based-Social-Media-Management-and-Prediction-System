<?php include 'home-header.php'; ?>
<main>
    <div class="home-all-post-container">
    <?php
        include 'conn.php';

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        // Fetch posts
        $post_query = "SELECT posts.*, user.profile_image, user.user_id FROM posts INNER JOIN user ON posts.username = user.username ORDER BY post_id DESC";
        $post_result = mysqli_query($conn, $post_query) or die("Query Failed.");

        // Fetch suggested users (only once)
        $suggestion_query = "SELECT user_id, username, profile_image FROM user WHERE user_id != $user_id ORDER BY RAND() LIMIT 5";
        $suggestion_result = mysqli_query($conn, $suggestion_query);
        $suggested_users = mysqli_fetch_all($suggestion_result, MYSQLI_ASSOC);

        $post_count = 0; // Track number of posts

        if (mysqli_num_rows($post_result) > 0) {
            while ($row = mysqli_fetch_assoc($post_result)) {
                if ($row['post_img'] == "" && $row['link'] == "") {
                    continue;
                }
                $post_id = $row['post_id'];

                // Fetch total likes
                $like_query = "SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = $post_id AND liked = 1";
                $like_result = mysqli_query($conn, $like_query);
                $like_data = mysqli_fetch_assoc($like_result);
                $total_likes = $like_data['total_likes'];

                // Check if current user has liked this post
                $user_liked_query = "SELECT liked FROM likes WHERE post_id = $post_id AND user_id = $user_id";
                $user_liked_result = mysqli_query($conn, $user_liked_query);
                $user_liked = (mysqli_num_rows($user_liked_result) > 0) ? 'liked' : '';

                $post_count++; // Increase post count
    ?>
        <!-- ✅ Post Card -->
        <div class="post-card">
            <div class="post-user">
                <a href="/ajay/profile/profile.php?user_id=<?php echo $row['user_id']; ?>" class="profile-container-post">
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic-post">
                    <span class="profile-pic-username"><?php echo $row['username']; ?></span>
                </a>
            </div>

            <?php if ($row['post_img'] != "") { ?>
                <img src="<?php echo $row['post_img']; ?>" alt="Post Image" class="post-img" onclick="window.location.href='/ajay/home/post-detail.php?id=<?php echo $post_id; ?>'">
            <?php } ?>

            <div class="post-content">
                <button class="like-button <?php echo $user_liked; ?>" onclick="likePost(event, <?php echo $post_id; ?>)">
                    ❤️ Like (<span id="like-count-<?php echo $post_id; ?>"><?php echo $total_likes; ?></span>)
                </button>
                
                <div class="post-title">Title: <?php echo $row['title']; ?></div>
                <div class="post-category">Category: <?php echo $row['category']; ?></div>
                <div class="post-date">Posted on: <?php echo $row['post_date']; ?></div>
                <div class="post-description"><?php echo $row['description']; ?></div>

                <?php if (!empty($row['link'])) { ?>
                    <a href="<?php echo $row['link']; ?>" target="_blank" class="post-link-tap"><?php echo $row['link']; ?></a>
                <?php } ?>
            </div>

            <a href="/ajay/home/complain.php?post_id=<?php echo $post_id; ?>" id="complain-button" style="margin-top:10px;">Complain Box</a>
        </div>

        <?php 
            // ✅ **सिर्फ पहले पोस्ट के बाद suggestions दिखाएगा**
            if ($post_count == 1 && count($suggested_users) > 0) { 
        ?>
            <div class="home-users-suggestions">
                <h2 style="color:black";>Users You May Know</h2>
                <div class="home-users-suggestions-container">
                <?php foreach ($suggested_users as $suggestion) { ?>
                    <div class="home-suggested-user">
                        <img src="<?php echo $suggestion['profile_image']; ?>" alt="Profile" class="home-suggested-profile-pic">
                        <span><?php echo $suggestion['username']; ?></span>
                        <a href="/ajay/profile/profile.php?user_id=<?php echo $suggestion['user_id']; ?>" class="home-view-profile-btn">View</a>
                    </div>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
    
    <?php } } ?>
    </div>
</main>

<script defer>
function likePost(event, postId) {
    // Event bubbling रोकने के लिए
    event.stopPropagation();

    // Like button को select करना
    let likeButton = event.target;

    // Like count दिखाने वाले span को select करना
    let likeCountSpan = document.getElementById("like-count-" + postId);

    // AJAX request बनाने के लिए XMLHttpRequest का उपयोग करना
    let xhr = new XMLHttpRequest();

    // Request को open करना (POST method, like.php को target करना)
    xhr.open("POST", "/ajay/home/like.php", true);

    // Request header सेट करना (form data भेजने के लिए)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Response मिलने पर क्या करना है
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Response को JSON में बदलना
            let response = JSON.parse(xhr.responseText);

            // Like count को अपडेट करना
            likeCountSpan.innerText = response.likes;

            // अगर यूजर ने पोस्ट को लाइक किया है तो button को "liked" class दें, वरना हटा दें
            if (response.liked) {
                likeButton.classList.add("liked");
            } else {
                likeButton.classList.remove("liked");
            }
        }
    };

    // Server को request भेजना (post_id के साथ)
    xhr.send("post_id=" + postId);
}
</script>


<?php include 'home-footer.php'; ?>
