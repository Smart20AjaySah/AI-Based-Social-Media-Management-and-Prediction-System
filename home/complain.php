<?php include 'home-header.php' ?>

<main>
    <div class="user-complain">
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="complain-container">

                <label for="complain-category-label">Category</label>
                <select name="category" class="complain-category" required>
                    <option selected>Sexual Content</option>
                    <option selected>Harmfull Content</option>
                    <option selected>Personal Reasons</option>
                    <option selected>Other Reasons</option>
                </select>

                <label for="complain-reason-label">Type In Words</label>
                <input type="text" name="reason" class="complain-reason" required>

                <button name="complain" class="complain-submit" type="submit">SUBMIT</button>
            </div>
            <?php
                if(isset($_POST['complain'])){
                    include 'conn.php';

                    $post_id = $_GET['post_id'];
                    $username = $_SESSION['username'];
                    $category = mysqli_real_escape_string($conn,$_POST['category']);
                    $reason = mysqli_real_escape_string($conn,$_POST['reason']);
                    date_default_timezone_set("ASIA/KOLKATA");
                    $date = date("H:i M d, Y");

                    $sql = "INSERT INTO complain(post_id,username,category,reason,complain_date)
                            VALUES ('{$post_id}','{$username}','{$category}','{$reason}','{$date}')";

                    if(mysqli_query($conn,$sql)){
                        header("Location: /ajay/home/home.php");
                        exit();
                    }else{
                        echo "<h2 style='color:red'>Complaint not sent.</h2>";
                    }
                }
            ?>
        </form>
    </div>
</main>

<?php include 'home-footer.php' ?>