<?php
header("Content-Type: application/json");

// Read raw JSON from fetch
$input = file_get_contents("php://input");

// Safety: empty input check
if (!$input) {
    echo json_encode(["reply" => "No input received"]);
    exit;
}

// Base64 encode (shell-safe)
$encoded = base64_encode($input);

// Python paths (VERIFY ONCE)
$python = "C:\\Users\\RAMAN KUMAR\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";
$python_script = "C:\\xampp\\htdocs\\ajay\\python3\\python\\ask.py";

// Execute (stderr included)
$cmd = "\"$python\" \"$python_script\" " . escapeshellarg($encoded) . " 2>&1";
$output = shell_exec($cmd);

// Handle no output
if ($output === null || trim($output) === "") {
    echo json_encode(["reply" => "Python executed but returned no output"]);
    exit;
}

// Return Python JSON directly
echo $output;
