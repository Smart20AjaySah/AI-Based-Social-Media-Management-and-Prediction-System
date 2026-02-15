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
            <li><a href="/post/video.php"><img src="/tabs-image/videos.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/home/notifications.php"><img src="/tabs-image/notification1.jpeg" alt="Profile Picture" class="profile-pic"></a></li>
            <li><a href="/owner/complain-action.php">🚨</a></li>               
            <li><a href="/owner/all-users.php">👤</a></li> -->
            <h2>Smart - Social Media</h2>
        </ul>
    </nav> 
    </footer>
</body>
</html>