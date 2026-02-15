<?php
    // Database credentials
    $servername = "localhost";
    $username = "root";  // VPS par jo MySQL user tumne banaya
    $password = "";  // tumhara MySQL password
    $dbname = "ajayembr";  // VPS par jo database import kiya

    // Connection create
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Connection check
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 🌍 Dynamic base URL — automatically chooses between domain and IP
    if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost:8080') !== false) {
        $location = "https://localhost:8080/ajay";
    } else {
        // Replace with your VPS IP
        $location = "http://YOUR_SERVER_IP/ajay";
    }
?>
