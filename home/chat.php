<?php 
include 'home-header.php'; 
include 'conn.php'; 

$logged_user_id = $_SESSION['user_id'];
$logged_user = $_SESSION['username'];

// Fetch users jo mutual followers hai **ya** jinse chat history hai
$query = "SELECT DISTINCT u.username, u.*, 
       (SELECT MAX(id) FROM messages WHERE sender = u.username OR receiver = u.username) AS last_msg_id
FROM messages m
JOIN user u ON m.sender = u.username OR m.receiver = u.username
WHERE (m.sender = '$logged_user' OR m.receiver = '$logged_user')
AND m.message IS NOT NULL
AND u.user_id NOT IN (SELECT blocked FROM blocked_users WHERE blocker = '$logged_user_id') 
ORDER BY last_msg_id DESC";

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
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" 
                    style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                </a>
                
                <span style="font-size: 14px; font-weight: bold; min-width: 80px; text-align: center; flex-grow: 1;">
                    <a href="/ajay/profile/profile.php?user_id=<?php echo urlencode($row['user_id']); ?>">
                        <?php echo htmlspecialchars($row['username']); ?>
                    </a>
                </span>
                
                <a href="chat-with-user.php?username=<?php echo urlencode($row['username']); ?>" 
                    style="padding: 3px 6px; font-size: 12px; border: none; border-radius: 5px; background: #007bff; color: white; cursor: pointer; margin-right:20px;" 
                    onclick="markAsSeen('<?php echo $_SESSION['username']; ?>', '<?php echo $row['username']; ?>')">
                    Chat
                </a>
                
                <?php 
                    $block_check = mysqli_query($conn, "SELECT * FROM blocked_users WHERE blocker='$logged_user' AND blocked='{$row['user_id']}'");
                    if(mysqli_num_rows($block_check) > 0) {
                ?>
                    <button onclick="unblockUser('<?php echo $row['user_id']; ?>')" 
                        style="padding: 3px 6px; font-size: 12px; border: none; border-radius: 5px; background: green; color: white; cursor: pointer;">
                        Unblock
                    </button>
                <?php } else { ?>
                    <button onclick="blockUser('<?php echo $row['user_id']; ?>')" 
                        style="padding: 3px 6px; font-size: 12px; border: none; border-radius: 5px; background: red; color: white; cursor: pointer;">
                        Block
                    </button>
                <?php } ?>
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

    function blockUser(userId) {
        if (confirm("Are you sure you want to block this user from chat?")) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "block-user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status == 200) {
                    location.reload(); // Refresh after blocking
                }
            };
            xhr.send("blocked_user=" + userId);
        }
    }

    function unblockUser(userId) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "unblock-user.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status == 200) {
                location.reload(); // Refresh after unblocking
            }
        };
        xhr.send("unblocked_user=" + userId);
    }
</script>

<?php include 'home-footer.php'; ?> 
