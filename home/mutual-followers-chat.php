<?php include 'home-header.php'; ?>
<?php 
    include 'conn.php'; // Database connection

    // Logged-in user ID (session से user_id लिया जा रहा है)
    $logged_in_user = $_SESSION['user_id'];

    // Mutual followers को fetch करने के लिए SQL query
    $query = "SELECT u.* FROM user u
              INNER JOIN followers f1 ON u.user_id = f1.following_id AND f1.follower_id = '$logged_in_user'
              INNER JOIN followers f2 ON u.user_id = f2.follower_id AND f2.following_id = '$logged_in_user'
              WHERE u.user_id != '$logged_in_user'";

    $result = mysqli_query($conn, $query);
?>

<main>
    <div class="chat-container">
        <h2>Chat with Users</h2>
        <div class="user-list">
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="user">
                    <!-- Profile Image -->
                    <a href="/ajay/profile/profile.php?user_id=<?php echo urlencode($row['user_id']); ?>">
                        <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic">
                    </a>

                    <!-- Username (Clickable for Profile) -->
                    <span class="username">
                        <a href="/ajay/profile/profile.php?user_id=<?php echo urlencode($row['user_id']); ?>">
                            <?php echo htmlspecialchars($row['username']); ?>
                        </a>
                    </span>

                    <!-- Chat Button -->
                    <a href="/ajay/home/chat-with-user.php?username=<?php echo urlencode($row['username']); ?>" 
                    class="chat-btn" 
                    onclick="markAsSeen('<?php echo $_SESSION['username']; ?>', '<?php echo $row['username']; ?>')">
                        Chat
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<script>
    function markAsSeen(sender, receiver) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "seen.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("sender=" + sender + "&receiver=" + receiver);
    }
</script>

<?php include 'home-footer.php'; ?>
