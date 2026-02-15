<?php include 'post-header.php'; ?>

<main>
    <div class="add-new-post">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
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

            <label><h2>Upload File (PDF/ZIP)</h2></label>
            <input name="post-file" type="file" class="input" accept=".pdf,.zip" required>
            
            <input class="add-new-button" name="add-new-post" type="submit" value="Upload File">
        </div>

        <?php
            if(isset($_POST['add-new-post'])){
                include 'conn.php';
                
                if(isset($_FILES['post-file'])){
                    $error = array();
                    $file_name = $_FILES['post-file']['name'];
                    $file_size = $_FILES['post-file']['size'];
                    $file_tmp = $_FILES['post-file']['tmp_name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    // Allowed file types
                    $allowed_types = array("pdf", "zip");

                    if(!in_array($file_ext, $allowed_types)){
                        $error[] = "Only PDF and ZIP files are allowed.";
                    }
                    if($file_size > 1073741824){ // 1GB limit
                        $error[] = "File size must be less than 1GB.";
                    }
                    if(empty($error)){
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/ajay/post/uploaded-files/";
                        $target_file = $target_dir . basename($file_name);

                        if(move_uploaded_file($file_tmp, $target_file)){
                            $file_url = "http://localhost:8080/ajay/post/uploaded-files/" . $file_name;
                            $file_type = $file_ext; // Save file type
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
                date_default_timezone_set("Asia/Kolkata");
                $date = date("H:i M d, Y");

                // Insert into pdf_posts table
                $sql = "INSERT INTO pdf_posts (username, title, category, post_date, description, file_url, file_type, user_id, downloads) 
                        VALUES ('{$username}','{$title}','{$category}','{$date}','{$desc}','{$file_url}','{$file_type}',{$user_id}, 0)";
                
                if(mysqli_query($conn,$sql)){
                    // Get last inserted post ID
                    $last_inserted_id = $conn->insert_id;
                    
                    // Insert into notifications table with correct type (pdf/zip)
                    $notiSQL = "INSERT INTO notifications (user_id, type, post_id, from_user_id, created_at) 
                                VALUES (?, ?, ?, NULL, NOW())";
                    $notiStmt = $conn->prepare($notiSQL);
                    $notiStmt->bind_param("isi", $user_id, $file_type, $last_inserted_id);
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
