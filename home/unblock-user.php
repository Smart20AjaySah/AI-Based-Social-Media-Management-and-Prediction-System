<?php
include 'conn.php';
session_start();

$blocker = $_SESSION['user_id'];
$unblocked = $_POST['unblocked_user'];

$query = "DELETE FROM blocked_users WHERE blocker='$blocker' AND blocked='$unblocked'";
if (mysqli_query($conn, $query)) {
    echo "User unblocked successfully!";
} else {
    echo "Error unblocking user!";
}
?>
