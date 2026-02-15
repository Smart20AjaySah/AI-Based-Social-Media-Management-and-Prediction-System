<?php include 'home-header.php'; ?>
<main>
    <div class="home-all-post-container">
    <?php
        include 'conn.php';

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        // Fetch PDFs & ZIPs
        $file_query = "SELECT pdf_posts.*, user.profile_image, user.user_id FROM pdf_posts 
                      INNER JOIN user ON pdf_posts.username = user.username 
                      ORDER BY file_id DESC";
        $file_result = mysqli_query($conn, $file_query) or die("Query Failed.");

        if (mysqli_num_rows($file_result) > 0) {
            while ($row = mysqli_fetch_assoc($file_result)) {
                $file_id = $row['file_id']; // ✅ Ensure correct column name
                $file_type = strtolower($row['file_type']);
                $file_icon = ($file_type == 'pdf') ? '📄' : '📁';
    ?>
        <!-- ✅ File Post Card -->
        <div class="post-card">
            <div class="post-user">
                <a href="/ajay/profile/profile.php?user_id=<?php echo $row['user_id']; ?>" class="profile-container-post">
                    <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" class="profile-pic-post">
                    <span class="profile-pic-username"><?php echo $row['username']; ?></span>
                </a>
            </div>

            <div class="post-content">
                <div class="post-title">Title: <?php echo $row['title']; ?></div>
                <div class="post-category">Category: <?php echo $row['category']; ?></div>
                <div class="post-date">Uploaded on: <?php echo $row['post_date']; ?></div>
                <div class="post-description"><?php echo $row['description']; ?></div>

                <a href="<?php echo $row['file_url']; ?>" target="_blank" class="post-link-tap">
                    <?php echo $file_icon; ?> View <?php echo strtoupper($file_type); ?>
                </a>
                
                <button class="download-button" onclick="downloadFile(<?php echo $file_id; ?>)">
                    ⬇️ Download (<span id="download-count-<?php echo $file_id; ?>"><?php echo $row['downloads']; ?></span>)
                </button>
            </div>

            <a href="/ajay/home/complain.php?file_id=<?php echo $file_id; ?>" id="complain-button" style="margin-top:10px;">Complain Box</a>
        </div>

    <?php } } ?>
    </div>
</main>

<script>
function downloadFile(fileId) {
    let downloadCountSpan = document.getElementById("download-count-" + fileId);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/ajay/home/download-pdf.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (xhr.status == 200 && response.success) {
                    downloadCountSpan.innerText = response.downloads; // ✅ Update Download Count

                    // **Trigger the actual download**
                    let link = document.createElement("a");
                    link.href = response.file_url;
                    link.setAttribute("download", "");  // ✅ Ensure Download Attribute is Used
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    alert("Failed to download: " + response.message);
                }
            } catch (error) {
                console.error("JSON Parsing Error:", error);
                alert("Invalid response from server.");
            }
        }
    };

    xhr.send("file_id=" + encodeURIComponent(fileId)); // ✅ Correctly Sending File ID
}
</script>

<?php include 'home-footer.php'; ?>
