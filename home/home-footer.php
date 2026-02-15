<script>
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mousemove', e => {
                const x = e.pageX - button.offsetLeft;
                const y = e.pageY - button.offsetTop;
                button.style.setProperty('--x', x + 'px');
                button.style.setProperty('--y', y + 'px');
            });
        });
    </script>
    <footer>
    <nav id="nav-menu-footer">
                <?php 
            include 'conn.php';
            $user_id = $_SESSION['user_id'];
            $sql4 = "SELECT * FROM user WHERE user_id={$user_id}";
            $result4 = mysqli_query($conn,$sql4) or die("Query Failed");
            $row4 = mysqli_fetch_assoc($result4);
        ?>
        <ul>
            <li><a href="/ajay/home/home.php"><img src="/ajay/tabs-image/home.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/ajay/home/chat.php"><img src="/ajay/tabs-image/chat.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/ajay/post/user.php"><img src="/ajay/tabs-image/add.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/ajay/post/video.php"><img src="/ajay/tabs-image/videos.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/ajay/home/notifications.php"><img src="/ajay/tabs-image/notification1.jpeg" alt="Profile Picture" class="profile-pic"></a></li>

            <li><a href="/ajay/profile/profile.php?user_id=<?php echo $row4['user_id']; ?>"><img src="<?php echo $profile_image; ?>" alt="Profile Picture" class="profile-pic"></a></li>
        </ul>
    </nav>    
    </footer>
</body>
</html>