<?php
include 'conn.php'; // Database connection

$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? 'all';
$offset = $_GET['offset'] ?? 0;
$limit = 10;

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$results = [];
$searchTerm = "%$query%";

// ✅ **Users Search**
if ($category == 'all' || $category == 'users') {
    $sql = "SELECT user_id, username FROM user WHERE username LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $searchTerm, $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'type' => 'user',
            'user_id' => $row['user_id'],
            'username' => $row['username']
        ];
    }
}

// ✅ **Posts Search (Fixed - Only image posts)**
if ($category == 'all' || $category == 'posts') {
    $sql = "SELECT post_id, title, COALESCE(description, '') as description FROM posts 
            WHERE (title LIKE ? OR COALESCE(description, '') LIKE ?) 
            AND (post_img IS NOT NULL AND post_img != '') 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'type' => 'post',
            'post_id' => $row['post_id'],
            'title' => $row['title'],
            'description' => $row['description']
        ];
    }
}

// ✅ **Videos Search (Fixed - Only video posts)**
if ($category == 'all' || $category == 'videos') {
    $sql = "SELECT post_id, title FROM posts 
            WHERE (video_url IS NOT NULL AND video_url != '') 
            AND (title LIKE ? OR COALESCE(description, '') LIKE ?) 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'type' => 'video',
            'post_id' => $row['post_id'],
            'title' => $row['title']
        ];
    }
}

echo json_encode($results);
?>
