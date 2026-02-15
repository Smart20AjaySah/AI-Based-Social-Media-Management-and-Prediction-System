<?php
include 'conn.php';

/* ===============================
   REMOVE POST LOGIC (TOP)
================================ */
if (isset($_POST['remove-post-video'])) {

    $post_id = intval($_POST['post_id']);

    // 1️⃣ Delete post
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    // 2️⃣ Delete complaint also (VERY IMPORTANT)
    $stmt2 = $conn->prepare("DELETE FROM complain WHERE post_id = ?");
    $stmt2->bind_param("i", $post_id);
    $stmt2->execute();

    // 3️⃣ Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include 'admin-header-user.php'; ?>

<main>
    <div class="complaint-action">
        <h2 class="complain-text">Complaint Details</h2>

        <div class="complain-card">
            <?php
            $sql = "SELECT * FROM complain ORDER BY complain_date DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <form method="post" class="complaint-form">

                    <p class="complain-info">
                        <strong>Post ID:</strong> <?= $row['post_id']; ?>
                    </p>

                    <p class="complain-info">
                        <strong>Category:</strong> <?= htmlspecialchars($row['category']); ?>
                    </p>

                    <p class="complain-info">
                        <strong>Reason:</strong> <?= htmlspecialchars($row['reason']); ?>
                    </p>

                    <p class="complain-info">
                        <strong>Complain Date:</strong> <?= $row['complain_date']; ?>
                    </p>

                    <!-- Hidden post_id -->
                    <input type="hidden" name="post_id" value="<?= $row['post_id']; ?>">

                    <button type="submit" name="remove-post-video" class="remove-post-video"
                        onclick="return confirm('Are you sure you want to remove this post?');">
                        Remove Post
                    </button>

                </form>
                <hr>
            <?php
                }
            } else {
                echo "<p style='text-align:center;'>No complaints found.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php include 'admin-footer-user.php'; ?>
