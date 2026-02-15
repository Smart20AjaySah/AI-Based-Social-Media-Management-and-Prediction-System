<?php
session_start();
include 'conn.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$profile_id = $_POST['profile_id'] ?? 0;
$action = $_POST['action'] ?? '';

if ($profile_id == 0 || !in_array($action, ["follow", "unfollow"])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

if ($user_id == $profile_id) {
    echo json_encode(["success" => false, "message" => "You cannot follow yourself"]);
    exit;
}

/* ================= FOLLOW ================= */
if ($action === "follow") {

    // Prevent duplicate follow
    $check = $conn->prepare(
        "SELECT id FROM followers WHERE follower_id=? AND following_id=?"
    );
    $check->bind_param("ii", $user_id, $profile_id);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Already following"]);
        exit;
    }

    // Insert follow
    $stmt = $conn->prepare(
        "INSERT INTO followers (follower_id, following_id) VALUES (?, ?)"
    );
    $stmt->bind_param("ii", $user_id, $profile_id);
    $stmt->execute();

    // Insert notification
    $notiStmt = $conn->prepare(
        "INSERT INTO notifications (user_id, type, from_user_id, created_at)
         VALUES (?, 'follow', ?, NOW())"
    );
    $notiStmt->bind_param("ii", $profile_id, $user_id);
    $notiStmt->execute();

/* ================= UNFOLLOW ================= */
} else {

    $stmt = $conn->prepare(
        "DELETE FROM followers WHERE follower_id=? AND following_id=?"
    );
    $stmt->bind_param("ii", $user_id, $profile_id);
    $stmt->execute();

    // Delete notification
    $notiStmt = $conn->prepare(
        "DELETE FROM notifications
         WHERE user_id=? AND from_user_id=? AND type='follow'"
    );
    $notiStmt->bind_param("ii", $profile_id, $user_id);
    $notiStmt->execute();
}

/* ================= FOLLOWER COUNT ================= */
$countStmt = $conn->prepare(
    "SELECT COUNT(*) AS total FROM followers WHERE following_id=?"
);
$countStmt->bind_param("i", $profile_id);
$countStmt->execute();
$count = $countStmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    "success" => true,
    "follower_count" => $count
]);

$conn->close();
