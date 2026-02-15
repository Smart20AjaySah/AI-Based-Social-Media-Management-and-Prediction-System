<?php include 'post-header.php' ?>

<main>
    <form class="specific-user-post" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="middle-post-container-column">
            <div class="column">
            </div>
            <div class="middle-post-container">
                <div class="add-post-edit-profile">
                    <a href="/ajay/post/add-video.php" class="add-video">ADD VIDEO</a>
                    <a href="/ajay/post/add-post.php" class="add-post">ADD POST</a>
                    <a href="/ajay/post/add-pdf.php" class="add-post">ADD PDF/ZIP</a>
                </div>

                <?php
                include 'conn.php';
                $user_id = $_SESSION['user_id'];

                $sql = "SELECT post_id, video_url, post_img, title, category, username, post_date 
                        FROM posts WHERE user_id={$user_id}
                        ORDER BY post_id DESC";

                $result = mysqli_query($conn, $sql) or die("Query Failed.");
                if (mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="post-container">
                            <label><strong>Sl_no:</strong> <?php echo $i; ?></label>
                            <label><strong>Content Type:</strong>
                                <?php
                                if (!empty($row['post_img'])) {
                                    echo "Image";
                                } elseif (!empty($row['video_url'])) {
                                    echo "Video";
                                } else {
                                    echo "Unknown";
                                }
                                ?>
                            </label>
                            <label><strong>Title:</strong> <?php echo $row['title']; ?></label>
                            <label><strong>Category:</strong> <?php echo $row['category']; ?></label>
                            <label><strong>Posted By:</strong> <?php echo $row['username']; ?></label>
                            <label><strong>Post Date:</strong> <?php echo $row['post_date']; ?></label>
                            <div class="post-actions">
                                <a href="/ajay/post/delete-post.php?id=<?php echo $row['post_id']; ?>" class="delete-post">DELETE</a>
                                <a href="/ajay/post/update-post.php?id=<?php echo $row['post_id']; ?>" class="update-post">UPDATE</a>
                            </div>
                        </div>
                <?php
                        $i++;
                    }
                } else {
                    echo "<h1 style='color:red;'>No Record Found.</h1>";
                }
                ?>

                <!-- PDF Posts Section -->
                <h2>Your Uploaded PDFs & ZIPs</h2>
                <?php
                $pdf_query = "SELECT file_id, file_url, file_type, title, category, username, post_date, downloads 
                              FROM pdf_posts WHERE user_id={$user_id} 
                              ORDER BY file_id DESC";

                $pdf_result = mysqli_query($conn, $pdf_query) or die("Query Failed.");
                if (mysqli_num_rows($pdf_result) > 0) {
                    $j = 1;
                    while ($pdf_row = mysqli_fetch_assoc($pdf_result)) {
                ?>
                        <div class="post-container">
                            <label><strong>Sl_no:</strong> <?php echo $j; ?></label>
                            <label><strong>Content Type:</strong> <?php echo $pdf_row['file_type']; ?></label>
                            <label><strong>Title:</strong> <?php echo $pdf_row['title']; ?></label>
                            <label><strong>Category:</strong> <?php echo $pdf_row['category']; ?></label>
                            <label><strong>Posted By:</strong> <?php echo $pdf_row['username']; ?></label>
                            <label><strong>Post Date:</strong> <?php echo $pdf_row['post_date']; ?></label>
                            <label><strong>Downloads:</strong> <span id="download-count-<?php echo $pdf_row['file_id']; ?>"><?php echo $pdf_row['downloads']; ?></span></label>
                            <div class="post-actions">
                                <a href="javascript:void(0);" onclick="downloadPDF(<?php echo $pdf_row['file_id']; ?>)" class="update-post">DOWNLOAD</a>
                                <a href="/ajay/post/delete-pdf.php?id=<?php echo $pdf_row['file_id']; ?>" class="delete-post">DELETE</a>
                            </div>
                        </div>
                <?php
                        $j++;
                    }
                } else {
                    echo "<h3 style='color:red;'>No PDFs & ZIPs Found.</h3>";
                }
                ?>
            </div>
        </div>
    </form>
</main>

<script>
function downloadPDF(fileId) {
    let downloadCountSpan = document.getElementById("download-count-" + fileId);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/ajay/home/download-pdf.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                downloadCountSpan.innerText = response.downloads;

                // **Trigger the actual download**
                let link = document.createElement("a");
                link.href = response.file_url;  // ✅ `file_url` now correct
                link.download = response.file_url.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert("Failed to download: " + response.message);
            }
        }
    };

    xhr.send("file_id=" + encodeURIComponent(fileId)); // ✅ file_id use ho raha hai
}
</script>

<?php include 'post-footer.php' ?>
