<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /ajay/user/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $pdf_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Fetch the PDF file path
    $query = "SELECT file_url FROM pdf_posts WHERE file_id = {$pdf_id} AND user_id = {$user_id}";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pdf_path = "C:/xampp/htdocs/ajay/post/uploaded-files/" . basename($row['file_url']);

        // Delete the PDF file from the server
        if (file_exists($pdf_path)) {
            unlink($pdf_path);
        }

        // Delete PDF from pdf_posts table
        $delete_pdf_query = "DELETE FROM pdf_posts WHERE file_id = {$pdf_id} AND user_id = {$user_id}";
        $delete_notifications_query = "DELETE FROM notifications WHERE post_id = {$pdf_id}";

        $pdf_deleted = mysqli_query($conn, $delete_pdf_query);
        $notifications_deleted = mysqli_query($conn, $delete_notifications_query);

        if ($pdf_deleted && $notifications_deleted) {
            $_SESSION['message'] = "PDF and its notifications deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete PDF or notifications from database.";
        }
    } else {
        $_SESSION['error'] = "PDF not found or you don't have permission to delete it.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: /ajay/post/user.php");
exit();
?>
