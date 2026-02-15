<?php
include 'conn.php'; // Database Connection

// Seen हुए messages को उनके seen_time के 24 घंटे बाद delete करो
$query = "DELETE FROM messages WHERE seen = 1 AND seen_time < NOW() - INTERVAL 24 HOUR";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(["success" => true, "message" => "Seen messages older than 24 hours deleted"]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
