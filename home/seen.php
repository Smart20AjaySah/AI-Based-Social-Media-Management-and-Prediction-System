<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['username'])) {
    exit("User not logged in");
}

$loggedInUser = $_SESSION['username'];  // Jo user abhi login hai
$sender = $_POST['sender'];  // Message bhejne wala
$receiver = $_POST['receiver'];  // Message receive karne wala

// ✅ Sirf receiver jab chat dekhega tab hi "seen" update hoga
if ($loggedInUser == $receiver) {
    $query = "UPDATE messages SET seen=1 WHERE sender=? AND receiver=? AND seen=0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $sender, $receiver);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
