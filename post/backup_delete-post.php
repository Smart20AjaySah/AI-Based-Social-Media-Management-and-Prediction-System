<?php
    include 'conn.php';

    $post_id = $_GET['id'];

    // Fetch post details
    $sql1 = "SELECT * FROM posts WHERE post_id={$post_id}";
    $result = mysqli_query($conn, $sql1) or die("Query Failed F.");
    $row = mysqli_fetch_assoc($result);

    // Delete post image & video if exists
    if (!empty($row['post_img'])) {
        unlink("/ajay/post/uploaded-image/" . $row['post_img']);
    }
    if (!empty($row['video_url'])) {
        unlink("/ajay/post/uploaded-video/" . basename($row['video_url']));
    }

    // Delete post
    $sql = "DELETE FROM posts WHERE post_id = {$post_id}";
    mysqli_query($conn, $sql) or die("Query Failed.");

    // Delete all notifications related to this post
    $notiSQL = "DELETE FROM notifications WHERE post_id = {$post_id}";
    mysqli_query($conn, $notiSQL) or die("Notification Delete Failed.");

    // Redirect after deletion
    header("Location: https://localhost:8080/ajay/post/user.php");    
?>
