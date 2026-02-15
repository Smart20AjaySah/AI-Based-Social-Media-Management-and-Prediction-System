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
        <ul>
            <!-- <li><a href="/home/home.php"><img src="/tabs-image/home.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/home/chat.php"><img src="/tabs-image/chat.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/post/user.php"><img src="/tabs-image/add.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/post/video.php"><img src="/tabs-image/videos.jpeg" alt="Profile Picture" class="profile-pic"></a></li> -->
            <!-- <li style="margin-left: 20px; margin-right:20px;"><a href="/home/notifications.php"><img src="/tabs-image/notification1.jpeg" alt="Profile Picture" class="profile-pic"></a></li> -->
            <li style="margin-left: 20px; margin-right:20px;"><a href="/ajay/owner/admin-register.php">👤</a></li> 
            <li style="margin-left: 20px; margin-right:20px;"><a href="/ajay/owner/complain-action.php">🚨</a></li>               
            <li style="margin-left: 20px; margin-right:20px;"><a href="/ajay/owner/all-users.php">👥</a></li>
        </ul>
    </nav> 
    </footer>
</body>
</html>