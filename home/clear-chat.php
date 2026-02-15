<?php
session_start();
include 'conn.php'; // Database connection

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit();
}

$loggedInUser = $_SESSION['username'];
$receiver = isset($_POST['receiver']) ? $_POST['receiver'] : '';

if (!$receiver) {
    echo json_encode(["success" => false, "error" => "Receiver not found"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)");
$stmt->bind_param("ssss", $loggedInUser, $receiver, $receiver, $loggedInUser);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to clear chat"]);
}

$stmt->close();
$conn->close();
?>
