<?php
session_start();
include 'conn.php';

// ✅ User logout handling
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE user SET login_token = NULL WHERE user_id = '$user_id'");
}

// ✅ Destroy sessions & cookies
setcookie("login_token", "", time() - 3600, "/");
session_unset();
session_destroy();

// ✅ Disable cache for this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Redirect with auto-refresh
header("Location: /ajay/owner/index.php");
exit();
?>
