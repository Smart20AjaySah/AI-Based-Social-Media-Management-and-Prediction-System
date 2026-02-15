<?php include 'post-header.php'; ?>

<main>
    <div class="add-new-post">
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="new-post">
            <label><h2>Title</h2></label>
            <input name="title" class="input" type="text" required>
            
            <label><h2>Category</h2></label>
            <select name="category" class="input">
                <option selected>Entertainment</option>
                <option>Education</option>
                <option>Business</option>
                <option>News</option>
                <option>Personal</option>
            </select>
            
            <label><h2>Description</h2></label>
            <input name="desc" type="text" class="input-description" required>

            <label><h2>Add Link</h2></label>
            <input name="post_link" type="url" class="input" placeholder="https://example.com">

            <label><h2>Upload Image</h2></label>
            <input name="post-img" type="file" class="input" required>

            <input class="add-new-button" name="add-new-post" type="submit" value="Add Post">
        </div>

        <?php
            if(isset($_POST['add-new-post'])){
                include 'conn.php';

                if(isset($_FILES['post-img'])){
                    $error = array();
                    $file_name = $_FILES['post-img']['name'];
                    $file_size = $_FILES['post-img']['size'];
                    $file_tmp = $_FILES['post-img']['tmp_name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $extensions = array("jpeg","jpg","png");

                    if(!in_array($file_ext, $extensions)){
                        $error[] = "Only JPG, JPEG, PNG files are allowed.";
                    }
                    if($file_size > 5242880){
                        $error[] = "File size must be less than 5MB.";
                    }
                    if(empty($error)){
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-image/";
                        $target_file = $target_dir . basename($file_name);

                        if(move_uploaded_file($file_tmp, $target_file)){
                            $file_url = "http://localhost:8080/ajay/post/uploaded-image/" . $file_name;
                        } else {
                            die("<div style='color: red;'>File upload failed.</div>");
                        }
                    } else {
                        echo "<pre>";
                        print_r($error);
                        echo "</pre>";
                        die();
                    }
                }

                $username = $_SESSION['username'];
                $user_id = $_SESSION['user_id'];
                $title = mysqli_real_escape_string($conn,$_POST['title']);
                $category = mysqli_real_escape_string($conn,$_POST['category']);
                $desc = mysqli_real_escape_string($conn,$_POST['desc']);
                $post_link = !empty($_POST['post_link']) ? mysqli_real_escape_string($conn,$_POST['post_link']) : NULL;
                date_default_timezone_set("Asia/Kolkata");
                $date = date("H:i M d, Y");

                $sql = "INSERT INTO posts(username,title,category,post_date,description,post_img,user_id, link)
                        VALUES ('{$username}','{$title}','{$category}','{$date}','{$desc}','{$file_url}',{$user_id}, '{$post_link}')";
                
                if(mysqli_query($conn,$sql)){
                    // Insert into notifications table
                    $notiSQL = "INSERT INTO notifications (user_id, type, post_id, from_user_id, created_at) 
                                VALUES (?, 'post', LAST_INSERT_ID(), NULL, NOW())";
                    $notiStmt = $conn->prepare($notiSQL);
                    $notiStmt->bind_param("i", $user_id);
                    $notiStmt->execute();
                    
                    header("Location: /ajay/post/user.php");
                } else {
                    echo "<div><h1 style='color: red;'>Alert: Unsuccessful.</h1></div>";
                }
            }
        ?>
    </form>
    </div>
</main>

<?php include 'post-footer.php'; ?> 
