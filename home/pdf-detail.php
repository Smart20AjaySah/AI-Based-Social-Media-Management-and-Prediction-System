<?php include 'home-header.php'; ?>

<main>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <?php
            include 'conn.php';
            
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $pdf_id = isset($_GET['id']) ? $_GET['id'] : 0;

            $sql = "SELECT pdf_posts.*, user.profile_image, user.username 
                    FROM pdf_posts 
                    JOIN user ON pdf_posts.user_id = user.user_id 
                    WHERE pdf_posts.file_id = $pdf_id";
            
            $result = mysqli_query($conn, $sql) or die("Query Failed.");
            $row = mysqli_fetch_assoc($result);
        ?>
        
        <div class="big-post-card" style="margin-bottom:50px;">
            <!-- Profile Pic & Username -->
            <div class="post-user">
                <a href="/ajay/profile/profile.php?user_id=<?php echo $row['user_id']; ?>" class="profile-container-post">
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic-post">
                    <span class="profile-pic-username"><?php echo $row['username']; ?></span>
                </a>
            </div>

            <!-- File Preview (PDF or ZIP) -->
            <div class="file-preview">
                <?php 
                    $file_url = $row['file_url'];
                    $file_extension = pathinfo($file_url, PATHINFO_EXTENSION);
                    if ($file_extension == 'pdf') {
                        echo '<img src="/ajay/tabs-image/pdf1.jpeg" alt="PDF File" class="big-post-image">';
                    } elseif ($file_extension == 'zip') {
                        echo '<img src="/ajay/tabs-image/zip-icon.jpeg" alt="ZIP File" class="big-post-image">';
                    } else {
                        echo '<img src="/ajay/tabs-image/default-file-icon.png" alt="File" class="big-post-image">';
                    }
                ?>
            </div>

            <!-- Post Information -->
            <div class="big-post-info">
                <p class="big-post-date">Uploaded on: <?php echo $row['post_date']; ?></p>
                <h2 class="big-post-title">Title: <?php echo $row['title']; ?></h2>
                <p class="big-post-category">Category: <span class="category"><?php echo $row['category']; ?></span></p>
                <p class="big-post-description"><?php echo $row['description']; ?></p>
                
                <!-- File Download Button -->
                <a href="<?php echo $file_url; ?>" target="_blank" class="post-link-tap">📥 VIEW PDF</a>
                
                <!-- Post Link (if available) -->
                <?php if (!empty($row['link'])) { ?>
                    <a href="<?php echo $row['link']; ?>" target="_blank" class="post-link-tap">🔗 Visit Link</a>
                <?php } ?>
            </div>

        </div>
    </form>
</main>

<?php include 'home-footer.php'; ?>
