<?php include 'post-header.php'; ?>

<main>
    <div class="update-post-user">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="new-post">
            <?php
                include 'conn.php';

                if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $post_id = $_GET['id'];
                    
                    $sql = "SELECT post_id, title, category, description, post_img, video_url, link FROM posts WHERE post_id={$post_id}";
                    $result = mysqli_query($conn, $sql) or die("Query Failed.");

                    if(mysqli_num_rows($result)){
                        $row = mysqli_fetch_assoc($result);
            ?>
        
            <label><h2>Title</h2></label>
            <input name="post-id" type="hidden" value="<?php echo $row['post_id']; ?>">
            <input name="title" class="input" type="text" value="<?php echo $row['title']; ?>">
            
            <label><h2>Category</h2></label>
            <select name="category" class="input">
                <option selected><?php echo $row['category']; ?></option>
            </select>
            
            <label><h2>Description</h2></label>
            <input name="desc" type="text" class="input-description" value="<?php echo $row['description']; ?>">
            
            <label><h2>Add Link</h2></label>
            <input name="post-link" type="text" class="post-link-tap" value="<?php echo $row['link']; ?>">
            
            <label><h2>Upload Image</h2></label>
            <input name="post-img" type="file" class="input">
            <img src="<?php echo $row['post_img']; ?>" height="150px" class="update-image">
            <input type="hidden" name="old-img" value="<?php echo $row['post_img']; ?>">
            
            <label><h2>Upload Video</h2></label>
            <input name="post-video" type="file" class="input">
            <video src="<?php echo $row['video_url']; ?>" height="150px" controls class="update-video"></video>
            <input type="hidden" name="old-video" value="<?php echo $row['video_url']; ?>">
            
            <input class="update-post-B" name="update-post" type="submit" value="UPDATE POST">
            <?php } else { echo "<h2 style='color:red;'>Post Not Found!</h2>"; } ?>
        </div>      
    </form>   
    </div>

    <?php
        } else {
            echo "<h2 style='color:red;'>Invalid Post ID!</h2>";
        }

        if(isset($_POST['update-post'])){
            include 'conn.php';

            $post_id = $_POST['post-id'] ?? '';
            if(empty($post_id) || !is_numeric($post_id)){
                echo "<h2 style='color:red;'>Invalid Post ID!</h2>";
                exit();
            }

            $file_old = $_POST['old-img'];
            $video_old = $_POST['old-video'];
            $post_link = $_POST['post-link'];

            // Image Upload Handling
            $file_name = $file_old;
            if(!empty($_FILES['post-img']['name'])){
                $file_tmp = $_FILES['post-img']['tmp_name'];
                $file_ext = strtolower(pathinfo($_FILES['post-img']['name'], PATHINFO_EXTENSION));
                $allowed_ext = array("jpeg", "jpg", "png");

                if(in_array($file_ext, $allowed_ext)){
                    $new_file_name = time() . "_" . basename($_FILES['post-img']['name']);
                    $upload_path = $_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-image/" . $new_file_name;

                    if(move_uploaded_file($file_tmp, $upload_path)){
                        $file_name = "/ajay/post/uploaded-image/" . $new_file_name;
                        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-image/" . basename($file_old))){
                            unlink($_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-image/" . basename($file_old));
                        }
                    }
                }
            }

            // Video Upload Handling
            $video_name = $video_old;
            if(!empty($_FILES['post-video']['name'])){
                $video_tmp = $_FILES['post-video']['tmp_name'];
                $video_ext = strtolower(pathinfo($_FILES['post-video']['name'], PATHINFO_EXTENSION));
                $allowed_video_ext = array("mp4", "avi", "mov", "wmv");

                if(in_array($video_ext, $allowed_video_ext)){
                    $new_video_name = time() . "_" . basename($_FILES['post-video']['name']);
                    $video_upload_path = $_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-video/" . $new_video_name;

                    if(move_uploaded_file($video_tmp, $video_upload_path)){
                        $video_name = "/ajay/post/uploaded-video/" . $new_video_name;
                        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-video/" . basename($video_old))){
                            unlink($_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-video/" . basename($video_old));
                        }
                    }
                }
            }

            date_default_timezone_set("Asia/Kolkata");
            $date = date("H:i M d, Y");

            $sql2 = "UPDATE posts SET 
                title = ?, 
                category = ?, 
                post_date = ?, 
                description = ?, 
                post_img = ?, 
                video_url = ?,
                link = ?
                WHERE post_id = ?";

            $stmt = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt, "sssssssi", $_POST['title'], $_POST['category'], $date, $_POST['desc'], $file_name, $video_name, $post_link, $post_id);

            if(mysqli_stmt_execute($stmt)){
                header("Location: /ajay/post/user.php");
                exit();
            } else {
                echo "<h2 style='color:red;'>Failed to update post.</h2>";
            }
        }
    ?>
</main>        

<?php include 'post-footer.php'; ?>
