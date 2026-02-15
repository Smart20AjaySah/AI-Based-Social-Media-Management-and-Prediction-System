<?php
include 'home-header.php';
include 'conn.php';

$logged_in_user = $_SESSION['user_id'];

// Blocked users को fetch करना
$query = "SELECT u.* FROM user u 
          INNER JOIN blocked_users b ON u.user_id = b.blocked 
          WHERE b.blocker = '$logged_in_user'";

$result = mysqli_query($conn, $query);
?>

<main>
    <div class="blocked-container">
        <h2>Blocked Users</h2>
        <div class="user-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="user">
                    <!-- Profile Image -->
                    <a href="/profile/profile.php?user_id=<?php echo urlencode($row['user_id']); ?>">
                        <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic">
                    </a>

                    <!-- Username -->
                    <span class="username" style="margin-left:10px";>
                        <a href="/profile/profile.php?user_id=<?php echo urlencode($row['user_id']); ?>">
                            <?php echo htmlspecialchars($row['username']); ?>
                        </a>
                    </span>

                    <!-- Unblock Button -->
                    <button style="margin-left:20px"; class="unblock-btn" onclick="unblockUser('<?php echo $row['user_id']; ?>')">Unblock</button>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<script>
    function unblockUser(userId) {
        if (confirm("Are you sure you want to unblock this user?")) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "unblock-user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    location.reload(); // Refresh after unblocking
                }
            };
            xhr.send("unblocked_user=" + userId);
        }
    }
</script>

<?php include 'home-footer.php'; ?>
