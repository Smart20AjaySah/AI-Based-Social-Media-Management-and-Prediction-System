<?php
session_start();
include 'conn.php'; // Database connection

// ✅ Auto-login using "Remember Me" token if session is not set
if (!isset($_SESSION['user_id']) && isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];

    // Validate user in the database using login_token
    $query = "SELECT * FROM user WHERE login_token = '$token' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Restore session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
    } else {
        // Invalid token, clear cookie
        setcookie("login_token", "", time() - 3600, "/");
    }
}

// ✅ अगर session अभी भी नहीं है, तो लॉगिन पेज पर भेजो
if (!isset($_SESSION['user_id'])) {
    header("Location: /ajay/owner/index.php");
    exit();
}

// ✅ Fetch User Profile Image
$user_id = $_SESSION['user_id'];
$query = "SELECT profile_image FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$profile_image = isset($row['profile_image']) ? $row['profile_image'] : "default.png"; // Default image if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart 🌟</title>
    <link rel="stylesheet" href="/ajay/profile/profile.css"> <!-- Linking External CSS -->
    <link rel="icon" type="image/svg+xml" href="/ajay/owner/smart2.svg">
</head>
<body>
    <header class="header">
        <div class="profile-container">
            <a href="/ajay/home/search.php">
            <img src="/ajay/tabs-image/search.jpeg" alt="Profile Picture" class="profile-pic">
            </a>
            <a href="/ajay/home/show-pdf.php" style="margin-left:10px";>
                <img src="/ajay/tabs-image/pdfzip.jpeg" alt="Profile Picture" class="profile-pic">
            </a>
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
                <!-- <li><a href="/home/home.php">Home</a></li>
                <li><a href="/home/chat.php">Chat</a></li>
                <li><a href="/post/video.php">Videos</a></li>
                <li><a href="/post/user.php">Profile</a></li>
                <li><a href="/owner/admin-login.php">Admin</a></li>
                <li>
                <form method="post">
                    <input type="submit" name="logout" value="Logout" class="logout-btn">
                </form>
                </li> -->
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
