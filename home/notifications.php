<?php include 'home-header.php'; ?>

<?php
include 'conn.php'; // Database connection

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch last 30 days notifications
$sql = "SELECT * FROM notifications WHERE (user_id = ? OR type IN ('post', 'video', 'pdf', 'zip')) AND created_at >= NOW() - INTERVAL 30 DAY ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <div class="main-notification-container" style="margin-bottom:50px;">
        <h2>Notifications</h2>
        <div class="notification-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification">
                    <?php if (in_array($row['type'], ['post', 'video', 'pdf', 'zip'])): ?>
                        <?php 
                        // Fetch title based on content type
                        if ($row['type'] == 'pdf' || $row['type'] == 'zip') {
                            $query = "SELECT file_id, title FROM pdf_posts WHERE file_id = ?";
                            $contentType = ($row['type'] == 'pdf') ? 'PDF' : 'Zip'; // सिर्फ notification में दिखाने के लिए
                            $detailPage = "pdf-detail.php"; // दोनों के लिए pdf-detail.php ही खुलेगा
                        } else {
                            $query = "SELECT post_id, title FROM posts WHERE post_id = ?";
                            $contentType = ucfirst($row['type']); // post और video के लिए
                            $detailPage = "{$row['type']}-detail.php"; // post और video के लिए dynamic set हो जाएगा
                        }

                        $contentStmt = $conn->prepare($query);
                        $contentStmt->bind_param("i", $row['post_id']);
                        $contentStmt->execute();
                        $contentResult = $contentStmt->get_result();
                        $content = $contentResult->fetch_assoc();
                        $contentTitle = htmlspecialchars($content['title']);
                        $contentId = ($row['type'] == 'pdf' || $row['type'] == 'zip') ? $content['file_id'] : $content['post_id'];
                        ?>
                        <a href="/ajay/home/<?= $detailPage ?>?id=<?= $contentId ?>">
                            <?= $contentType ?>: <?= $contentTitle ?>
                        </a>

                    <?php elseif ($row['type'] == 'follow' && $row['user_id'] == $user_id): ?>
                        <?php 
                        // Fetch follower username
                        $followerQuery = "SELECT username FROM user WHERE user_id = ?";
                        $followerStmt = $conn->prepare($followerQuery);
                        $followerStmt->bind_param("i", $row['from_user_id']);
                        $followerStmt->execute();
                        $followerResult = $followerStmt->get_result();
                        $follower = $followerResult->fetch_assoc();
                        ?>
                        <span>New Follower: <?= htmlspecialchars($follower['username']) ?></span>
                        <a href="/ajay/profile/profile.php?user_id=<?= $row['from_user_id'] ?>" class="follow-back">Follow Back</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>   

<?php include 'home-footer.php'; ?>
