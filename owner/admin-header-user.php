<?php
session_start();
include 'conn.php'; // Database connection

// ✅ अगर admin का session नहीं है तो login page पर redirect करो
if (!isset($_SESSION['admin_id'])) {
    header("Location: /ajay/owner/admin-login.php");
    exit();
}

// ✅ Admin info निकालो (optional)
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE adm_id = '$admin_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    $admin_username = $admin['username'];
    $admin_fullname = $admin['fullname'];
} else {
    // अगर database में admin नहीं मिला तो logout कर दो
    session_destroy();
    header("Location: /ajay/owner/admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart 🌟</title>
    <link rel="stylesheet" href="/ajay/owner/admin.css"> <!-- Linking External CSS -->
    <link rel="icon" type="image/svg+xml" href="/ajay/owner/smart2.svg">
</head>
<body>
    <header class="header">
        <div class="profile-container">
            <!-- <a href="/home/search.php">
            <img src="/tabs-image/search.jpeg" alt="Profile Picture" class="profile-pic">
            </a>
            <a href="/home/show-pdf.php" style="margin-left:10px";>
                <img src="/tabs-image/pdfzip.jpeg" alt="Profile Picture" class="profile-pic">
            </a> -->
            <h2 class="aventra">Smart 🌟</h2>
        </div>

        <!-- Existing Menu Button for Mobile -->
        <div class="menu-btn mobile-menu-btn" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!-- New Menu Button for Desktop -->
        <div class="menu-btn desktop-menu-btn" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <nav id="nav-menu">
            <ul>
            <!-- <li><a href="/home/home.php">🏠</a></li>
                <li><a href="/home/chat.php">💬</a></li>
                <li><a href="/post/video.php">🎥</a></li> -->
                <!-- <li><a href="/owner/complain-action.php">🚨</a></li>               
                <li><a href="/owner/all-users.php">👤</a></li>
                <li><a href="/owner/admin-login.php">⚙️</a></li> -->
                <li><a href="/ajay/home/logout.php" class="logout-btn">🚪</a></li>
            </ul>
        </nav>
    </header>

    <script>
        function toggleMenu() {
            document.getElementById("nav-menu").classList.toggle("active");
        }
    </script>
</body>
</html>
