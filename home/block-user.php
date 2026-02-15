<?php
include 'conn.php';
session_start();

$blocker = $_SESSION['user_id'];
$blocked = $_POST['blocked_user'];

// पहले check करो कि पहले से block तो नहीं किया
$check = mysqli_query($conn, "SELECT * FROM blocked_users WHERE blocker='$blocker' AND blocked='$blocked'");

if (mysqli_num_rows($check) == 0) {
    $query = "INSERT INTO blocked_users (blocker, blocked) VALUES ('$blocker', '$blocked')";
    if (mysqli_query($conn, $query)) {
        echo "User blocked successfully!";
    } else {
        echo "Error blocking user!";
    }
} else {
    echo "User is already blocked!";
}
?>
