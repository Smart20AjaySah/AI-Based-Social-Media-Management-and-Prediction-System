<?php
include 'conn.php';
session_start();

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$sender = $_SESSION['username'];  // Sender username
$receiver = isset($_POST['receiver']) ? $_POST['receiver'] : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

// Debugging - Check if Receiver and Message are set
if (!$receiver || !$message) {
    echo json_encode(["error" => "Receiver or message is empty"]);
    exit;
}

// Prepare and execute the query
$query = "INSERT INTO messages (sender, receiver, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "SQL Prepare Error: " . $conn->error]);
    exit;
}

$stmt->bind_param("sss", $sender, $receiver, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => "Message sent successfully"]);
} else {
    echo json_encode(["error" => "Error inserting message: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
