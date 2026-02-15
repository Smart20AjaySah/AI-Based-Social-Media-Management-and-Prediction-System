<?php
include 'conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$loggedInUser = $_SESSION['username'];
$receiver = isset($_GET['receiver']) ? $_GET['receiver'] : null;

if (!$receiver) {
    echo json_encode(["error" => "Receiver not provided"]);
    exit;
}

// ✅ Messages Fetch Query (Seen Status Include Karo)
$query = "SELECT sender, receiver, message, seen, created_at FROM messages 
          WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) 
          ORDER BY created_at ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $loggedInUser, $receiver, $receiver, $loggedInUser);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// ✅ **Sirf Receiver Jab Dekhega Tab Seen Update Hoga**
if ($loggedInUser == $receiver) {
    $updateQuery = "UPDATE messages SET seen=1 WHERE sender=? AND receiver=? AND seen=0";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ss", $receiver, $loggedInUser);
    $updateStmt->execute();
    $updateStmt->close();
}

// ✅ Latest Message Ke Seen Status Ko Send Karo
$latestSeen = 0;
if (!empty($messages)) {
    $latestSeen = $messages[count($messages) - 1]['seen']; // Last message ka seen status
}

echo json_encode(["success" => true, "messages" => $messages, "latest_seen" => $latestSeen]);

$stmt->close();
$conn->close();
?>
