<?php
include 'conn.php';

if(isset($_POST['file_id'])) {
    $file_id = intval($_POST['file_id']); // Ensure file_id is an integer

    // Check if file exists in database
    $query = "SELECT file_url, downloads FROM pdf_posts WHERE file_id = $file_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(["success" => false, "message" => "Database Error: " . mysqli_error($conn)]);
        exit;
    }

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_url = $row['file_url'];
        $current_downloads = $row['downloads'];

        // 🔥 **Fix: Convert URL to Server Path**
        $file_path = str_replace("http://localhost:8080", "C:/xampp/htdocs/", $file_url);

        // Debugging logs (check error log)
        error_log("File URL: " . $file_url);
        error_log("Converted File Path: " . $file_path);

        // ✅ Check if file exists on server
        if (!file_exists($file_path)) {
            echo json_encode(["success" => false, "message" => "File does not exist on server"]);
            exit;
        }

        // ✅ Update download count
        $update_query = "UPDATE pdf_posts SET downloads = downloads + 1 WHERE file_id = $file_id";
        mysqli_query($conn, $update_query);

        // ✅ Fetch updated count
        $new_result = mysqli_query($conn, "SELECT downloads FROM pdf_posts WHERE file_id = $file_id");
        $new_row = mysqli_fetch_assoc($new_result);
        $updated_downloads = $new_row['downloads'];

        // ✅ Send response
        echo json_encode(["success" => true, "downloads" => $updated_downloads, "file_url" => $file_url]);
    } else {
        echo json_encode(["success" => false, "message" => "PDF not found in database"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
