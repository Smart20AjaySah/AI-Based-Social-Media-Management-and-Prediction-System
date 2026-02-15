<?php include 'admin-header-user.php' ?>

<main>
    <div class="all-user-container">
        <?php 
            include 'conn.php';

            $sql = "SELECT * FROM user ORDER BY user_id DESC";
            $result = mysqli_query($conn, $sql) or die("Query Failed.");

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="user-card">
            <label>User ID:</label>
            <span><?php echo $row['user_id']; ?></span>

            <label>Full Name:</label>
            <span><?php echo $row['fullname']; ?></span>

            <label>Username:</label>
            <span><?php echo $row['username']; ?></span>

            <label>Phone:</label>
            <span><?php echo $row['phone']; ?></span>

            <a href="/ajay/owner/remove-user.php?user_id=<?php echo $row['user_id']; ?>" class="remove-user-btn">Remove</a>
        </div>
        <?php 
                $_SESSION['r_user_id'] = $row['user_id']; 
                } 
            } 
        ?>
    </div>

</main>

<?php include 'admin-footer-user.php' ?>