<?php
include 'conn.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    exit;
}

$loggedInUser = $_SESSION['username'];
$receiver = isset($_POST['receiver']) ? $_POST['receiver'] : null;

if (!$receiver) {
    exit;
}

// Update seen status
$query = "UPDATE messages SET seen = 1 WHERE sender = ? AND receiver = ? AND seen = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $receiver, $loggedInUser);
$stmt->execute();

$stmt->close();
$conn->close();
?>
