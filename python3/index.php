<?php
$uploadDir = '/var/www/html/ajay/python3/python/uploaded_csv/';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upload CSV — ML Trainer</title>

<style>
    *{margin:0;padding:0;box-sizing:border-box;}

    html, body {
        height: 100%;
    }

    body {
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        background-color: #1c1c1c; /* light black */
        color: #fff;
    }

    header {
        background: #2c2c2c; /* contrast header */
        padding: 15px 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    header img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        display: block;
        margin: 0 auto 5px auto;
    }

    header h1 {
        font-size: 24px;
        margin: 0;
        font-weight: bold;
        color: #fff;
        text-shadow: 0 0 2px #fff;
    }

    header h1 .star {
        color: #f7f753ff;
        text-shadow: 0 0 5px #ffeb3b, 0 0 10px #ffeb3b;
        animation: star-glow 2s ease-in-out infinite alternate;
    }

    @keyframes star-glow {
        0% { text-shadow: 0 0 3px #ffeb3b, 0 0 6px #ffeb3b; }
        50% { text-shadow: 0 0 6px #ffeb3b, 0 0 12px #ffeb3b; }
        100% { text-shadow: 0 0 3px #ffeb3b, 0 0 6px #ffeb3b; }
    }

    .container {
        max-width: 900px;
        width: 100%;
        margin: 30px auto;
        background: yellow;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.08);
        flex: 1;
        color: #000;
    }

    label {
        display: block;
        margin: 12px 0 5px;
        font-weight: bold;
    }

    input[type=file] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 6px;
        border: none;
        background: #007bff;
        color: white;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }

    .btn:hover {
        background: #0056c7;
    }

    /* Important note box */
    .note {
        background-color: #ffcccc;
        color: #900;
        padding: 12px;
        border-radius: 8px;
        font-weight: bold;
        margin-top: 15px;
    }

    footer {
        text-align: center;
        padding: 15px 0;
        color: #aaa;
        font-size: 14px;
        background: #2c2c2c;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.3);
    }

    @media (max-width: 600px) {
        .container { margin: 20px 10px; padding: 15px; }
        header h1 { font-size: 17px; }
    }
    #back-btn {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin-right: 10px;
            font-size: 1rem;
            transition: color 0.2s;
        }

        #back-btn:hover {
            color: #0056b3;
            text-decoration: underline;
        }
</style>
</head>

<body>

<header>
    <h1>
        Smart <span class="star">🌟</span>
    </h1>
</header>

<div class="container">
    <a id="back-btn" href="owner/index.php">&larr; Back</a><br>
    <h2>Upload CSV for Linear Regression (Adam)</h2>

    <form method="post" enctype="multipart/form-data" action="process.php">
        <label>Select CSV file</label>
        <input type="file" name="csvfile" accept=".csv" required>

        <p>After upload you'll choose columns to use as inputs (X) and output (Y).</p>

        <!-- Important note -->
        <div class="note">
            ⚠️ Important: Only CSV files are allowed. Column names <strong>must not contain spaces</strong>.
        </div>

        <button class="btn" type="submit">Upload & Continue</button>
    </form>
</div>

<footer>
    Ajay Sah 2025 | all rights reserved
</footer>

</body>
</html>
