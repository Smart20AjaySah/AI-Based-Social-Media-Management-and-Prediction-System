<?php
session_start();
include 'conn.php'; // Database connection

// ✅ Secure Auto-login using "Remember Me" token if session is not set
if (!isset($_SESSION['user_id']) && isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];

    // Validate user in the database using login_token (Prepared Statement)
    $stmt = $conn->prepare("SELECT * FROM user WHERE login_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

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

// ✅ Fetch User Profile Image (Prepared Statement)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_image FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$profile_image = isset($row['profile_image']) ? $row['profile_image'] : "default.png"; // Default image if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart 🌟</title>
    <link rel="stylesheet" href="/ajay/home/home.css"> <!-- Linking External CSS -->
    <link rel="icon" type="image/svg+xml" href="/ajay/owner/smart2.svg">
</head>
<body>
    <header class="header">
        <div class="profile-container">
            <a href="/ajay/home/search.php">
                <img src="/ajay/tabs-image/search.jpeg" alt="Search" class="profile-pic">
            </a>
            <a href="/ajay/home/show-pdf.php" style="margin-left:10px;">
                <img src="/ajay/tabs-image/pdfzip.jpeg" alt="PDF" class="profile-pic">
            </a>
            <h2 class="aventra">Smart 🌟</h2>
        </div>

        <!-- Mobile Menu Button -->
        <div class="menu-btn mobile-menu-btn" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!-- Desktop Menu Button -->
        <div class="menu-btn desktop-menu-btn" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <nav id="nav-menu">
            <ul>
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
