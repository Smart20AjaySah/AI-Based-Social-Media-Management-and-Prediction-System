<?php
$uploadDir = 'C:/xampp/htdocs/ajay/python3/python/uploaded_csv/';
if (!isset($_GET['r'])) die('Result file required');
$f = basename($_GET['r']);
$path = $uploadDir . $f;
if (!file_exists($path)) die('Result file not found');
$data = json_decode(file_get_contents($path), true);
if (isset($data['error'])) die('Model error: ' . $data['error']);

$theta = $data['theta'];
$xcols = $data['x_columns'];
$ycol  = $data['y_column'];
$divisor = $data['divisor'];

$pred = null;

// ---- Prediction Logic ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vals = [];
    foreach ($xcols as $i => $col) {
        $v = floatval($_POST['x'.($i+1)] ?? 0);
        $vals[] = $v;
    }

    $X = [1.0];
    foreach ($vals as $v) $X[] = $v / $divisor;

    $sum = 0.0;
    for ($i = 0; $i < count($theta); $i++) {
        $sum += $theta[$i][0] * $X[$i];
    }

    $pred = $sum * $divisor;
}
?>
<!doctype html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prediction</title>

<style>
    * { margin:0; padding:0; box-sizing:border-box; }

    html, body {
        height: 100%;
    }

    body {
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        background-color: #1c1c1c; /* thoda light black */
        color: #fff;
    }

    header {
        background: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    header {
        background: #2c2c2c; /* thoda contrast ke liye */
        padding: 15px 20px;
        text-align: center; /* center header content */
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    header h1 {
    font-size: 24px;
    margin: 0;
    font-weight: bold;
    color: #fff; /* Smart white */
    text-shadow: 0 0 2px #fff; /* subtle glow for Smart */
    }

    header h1 .star {
        color: #f7f753ff; /* yellow star */
        text-shadow: 0 0 5px #ffeb3b, 0 0 10px #ffeb3b; /* gentle glow */
        animation: star-glow 2s ease-in-out infinite alternate;
    }

    @keyframes star-glow {
        0% {
            text-shadow: 0 0 3px #ffeb3b, 0 0 6px #ffeb3b;
        }
        50% {
            text-shadow: 0 0 6px #ffeb3b, 0 0 12px #ffeb3b;
        }
        100% {
            text-shadow: 0 0 3px #ffeb3b, 0 0 6px #ffeb3b;
        }
    }

    .container {
        max-width: 900px;
        width: 100%;
        background: yellow;
        padding: 20px;
        margin: 30px auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.08);
        flex: 1;
    }

    label {
        font-weight: bold;
        display: block;
        margin: 12px 0 5px;
    }

    input {
        padding: 10px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 6px;
        border: none;
        background: #007bff;
        color: white;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .btn:hover {
        background: #005bcc;
    }

    footer {
        text-align: center;
        padding: 15px 0;
        background: #fff;
        color: #555;
        font-size: 14px;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
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
    <a id="back-btn" href="/ajay/owner/index.php">&larr; Back</a><br>
    <h2 style="margin-bottom:10px; color: black;">Predict: <?php echo htmlspecialchars($ycol); ?></h2>

    <form method="post">
        <?php foreach ($xcols as $i => $col): ?>
            <label style="color: black;"><?php echo htmlspecialchars($col); ?></label>
            <input name="x<?php echo $i+1; ?>" step="any" required>
        <?php endforeach; ?>

        <button class="btn" type="submit">Predict</button>
    </form>

    <?php if ($pred !== null): ?>
        <h3 style="margin-top:20px; color: black;">Prediction: <?php echo number_format($pred, 4); ?></h3>
    <?php endif; ?>

    <p style="margin-top:20px;">
        <a href="/ajay/python3/result.php?f=<?php echo urlencode($f); ?>">⬅ Back to Results</a>
    </p>
</div>

<footer>
    Ajay Sah 2025 | all rights reserved
</footer>

</body>
</html>