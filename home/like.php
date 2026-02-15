<?php
include 'conn.php';
session_start();

header('Content-Type: application/json'); // JSON response set karega

// Agar user login nahi hai, toh error bhej do
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Login Required"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

if ($post_id === 0) {
    echo json_encode(["error" => "Invalid Post ID"]);
    exit();
}

// Check karo ki user ne pehle se like kiya hai ya nahi
$check_like = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
$result = mysqli_query($conn, $check_like);

if (mysqli_num_rows($result) > 0) {
    // Agar like kiya tha, toh unlike karna hai
    $query = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    $liked = false;
} else {
    // Agar like nahi kiya tha, toh like karna hai
    $query = "INSERT INTO likes (post_id, user_id, liked) VALUES ($post_id, $user_id, 1)";
    $liked = true;
}

mysqli_query($conn, $query);

// Updated like count fetch karo
$like_count_query = "SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = $post_id AND liked = 1";
$like_result = mysqli_query($conn, $like_count_query);
$like_data = mysqli_fetch_assoc($like_result);

echo json_encode(["likes" => $like_data['total_likes'], "liked" => $liked]);
?>
