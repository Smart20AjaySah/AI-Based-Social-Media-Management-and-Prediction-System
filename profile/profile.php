<?php 
include 'profile-header.php';
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Logged-in user ID
$profile_id = $_GET['user_id'] ?? $user_id; // Profile page being viewed

// Check if the logged-in user is following this profile user
$sql_follow = "SELECT * FROM followers WHERE follower_id = ? AND following_id = ?";
$stmt_follow = $conn->prepare($sql_follow);
$stmt_follow->bind_param("ii", $user_id, $profile_id);
$stmt_follow->execute();
$result_follow = $stmt_follow->get_result();
$is_following = $result_follow->num_rows > 0;

// Fetch user profile data
$sql3 = "SELECT * FROM user WHERE user_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $profile_id);
$stmt3->execute();
$result3 = $stmt3->get_result();
$row3 = $result3->fetch_assoc();
?>

<main>
    <div class="main-profile-container">
        <div class="main-profile-container-header">
            <img src="<?php echo htmlspecialchars($row3['profile_image'] ?? 'default.jpg'); ?>" 
                 alt="Profile Picture" class="main-profile-container-profile-image">
            <div class="main-profile-container-info">
                <h2 class="main-profile-container-username"><?php echo htmlspecialchars($row3['username']); ?></h2>
                <p class="main-profile-container-bio"><?php echo htmlspecialchars($row3['bio']); ?></p>
                
                <a href="/ajay/post/edit-profile.php" class="main-profile-container-edit-link">Edit Profile</a>
                
                <?php if ($user_id !== $profile_id): ?>
                    <button id="follow-btn" class="follow-button" data-following="<?php echo $is_following ? '1' : '0'; ?>">
                        <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat & Blocked Users Buttons -->
        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 15px;">
            <a href="/ajay/home/chat-with-user.php?username=<?php echo htmlspecialchars($row3['username']); ?>" 
               style="padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px;">
                Chat
            </a>
            <?php if ($user_id === $profile_id): // केवल लॉगिन उपयोगकर्ता की प्रोफाइल पर दिखाएं ?>
                <a href="/ajay/home/show-blocked-users.php" 
                   style="padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px;">
                    Blocked Users
                </a>
            <?php endif; ?>
        </div>

        <div class="main-profile-container-stats" style="margin-top: 15px;">
            <div class="stat-box">
                <h3 class="stat-label">Posts</h3>
                <span class="stat-value">
                    <?php 
                    $sql_posts = "SELECT COUNT(*) AS total_posts FROM posts WHERE user_id=?";
                    $stmt_posts = $conn->prepare($sql_posts);
                    $stmt_posts->bind_param("i", $profile_id);
                    $stmt_posts->execute();
                    $result_posts = $stmt_posts->get_result();
                    $row_posts = $result_posts->fetch_assoc();
                    echo $row_posts['total_posts'];
                    ?>
                </span>
            </div>

            <div class="stat-box">
                <h3 class="stat-label">Followers</h3>
                <span class="stat-value" id="followers-count">
                    <?php 
                    $sql_followers = "SELECT COUNT(*) AS total_followers FROM followers WHERE following_id=?";
                    $stmt_followers = $conn->prepare($sql_followers);
                    $stmt_followers->bind_param("i", $profile_id);
                    $stmt_followers->execute();
                    $result_followers = $stmt_followers->get_result();
                    $row_followers = $result_followers->fetch_assoc();
                    echo $row_followers['total_followers'];
                    ?>
                </span>
            </div>

            <div class="stat-box">
                <h3 class="stat-label">Following</h3>
                <span class="stat-value">
                    <?php 
                    $sql_following = "SELECT COUNT(*) AS total_following FROM followers WHERE follower_id=?";
                    $stmt_following = $conn->prepare($sql_following);
                    $stmt_following->bind_param("i", $profile_id);
                    $stmt_following->execute();
                    $result_following = $stmt_following->get_result();
                    $row_following = $result_following->fetch_assoc();
                    echo $row_following['total_following'];
                    ?>
                </span>
            </div>
        </div>
        
        <div class="main-profile-container-show-posts">
            <div class="main-profile-container-post-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;"> 
                <?php 
                $sql = "SELECT post_id, post_img, video_url FROM posts WHERE user_id=? ORDER BY post_id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $profile_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<div class="main-profile-container-post" style="display: flex; align-items: flex-end; position: relative;">';
                        
                        // Display Image if exists
                        if (!empty($row['post_img'])) {
                            echo '<a href="/ajay/home/post-detail.php?id=' . htmlspecialchars($row['post_id']) . '" style="display: block;">';
                            echo '<img src="' . htmlspecialchars($row['post_img']) . '" alt="Post Image" style="width: 100%; height: auto;">';
                            echo '</a>';
                        }
        
                        // Display Video if exists
                        if (!empty($row['video_url'])) {
                            echo '<a href="/ajay/home/video-detail.php?id=' . htmlspecialchars($row['post_id']) . '" style="display: block; position: relative;">';
                            echo '<video style="width: 100%; height: auto; pointer-events: none;">';
                            echo '<source src="' . htmlspecialchars($row['video_url']) . '" type="video/mp4">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            // Invisible overlay for clicking
                            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>';
                            echo '</a>';
                        }
        
                        echo '</div>';
                    }
                } else {
                    echo "<p>No posts available</p>";
                }
                $stmt->close();
                ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let btn = document.getElementById("follow-btn");
        if (btn) {
            btn.addEventListener("click", function () {
                let following = btn.getAttribute("data-following");
                let action = following == "1" ? "unfollow" : "follow";
                let profileId = <?php echo json_encode($profile_id); ?>;

                fetch("follow_action.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `action=${action}&profile_id=${profileId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.innerText = action === "follow" ? "Unfollow" : "Follow";
                        btn.setAttribute("data-following", action === "follow" ? "1" : "0");
                        document.getElementById("followers-count").innerText = data.follower_count;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    alert("Something went wrong!");
                });
            });
        }
    });
    </script>
</main>

<?php include 'profile-footer.php'; ?>
