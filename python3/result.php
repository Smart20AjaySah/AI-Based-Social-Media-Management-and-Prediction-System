<?php
$uploadDir = 'C:/xampp/htdocs/ajay/python3/python/uploaded_csv/';
if (!isset($_GET['f'])) die('Result file not specified');
$f = basename($_GET['f']);
$path = $uploadDir . $f;

if (!file_exists($path)) die('Result file not found.');

$json = file_get_contents($path);
$data = json_decode($json, true);

if (!$data) {
    echo "Invalid result JSON: <pre>" . htmlspecialchars($json) . "</pre>";
    exit;
}

if (isset($data['error'])) {
    echo "Error from model: " . htmlspecialchars($data['error']);
    exit;
}

$theta   = $data['theta'];
$x_cols  = $data['x_columns'];
$y_col   = $data['y_column'];
$divisor = $data['divisor'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Model Result</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    background-color: #1c1c1c; /* thoda light black */
    color: #fff;
    font-family:Arial, sans-serif;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}
header {
    background: #2c2c2c; /* thoda contrast ke liye */
    padding: 15px 20px;
    text-align: center; /* center header content */
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
header img{
    height:40px;
    margin-right:10px;
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
main{
    flex:1;
    max-width:900px;
    background:yellow;
    margin:20px auto;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}
h2,h3{
    color:#333;
}
a{
    display:inline-block;
    padding:10px 15px;
    margin-top:10px;
    background:#007bff;
    color:white;
    border-radius:6px;
    text-decoration:none;
}
a:hover{
    background:#0056c7;
}
footer{
    text-align:center;
    padding:15px;
    background:#fff;
    border-top:1px solid #ddd;
    margin-top:auto;
}
@media(max-width:600px){
    main{
        margin:10px;
        padding:15px;
    }
}
#back-btn {
    text-decoration: none;
    color: #0af729ff;
    font-weight: bold;
    margin-right: 10px;
    font-size: 1rem;
    transition: color 0.2s;
}

#back-btn:hover {
    color: #05f45dff;
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

<main>
<a id="back-btn" href="/ajay/owner/index.php">&larr; Back</a>
<h2>Model Trained Successfully</h2>

<p style="color: black">Target Column: <strong><?php echo htmlspecialchars($y_col); ?></strong></p>

<h3>Regression Formula</h3>
<p style="line-height:1.8; font-size:17px; color: black;"><b>
<?php
echo "(" . htmlspecialchars($theta[0][0]) . ")";
for ($i = 1; $i < count($theta); $i++) {
    $col = htmlspecialchars($x_cols[$i - 1]);
    echo " + (" . $col . " × " . htmlspecialchars($theta[$i][0]) . ")";
}
?>
</b>
</p>

<p style="color: black;"><strong>Divisor (Scaling Factor):</strong> <?php echo htmlspecialchars($divisor); ?></p>

<br>

<a href="/ajay/python3/predict.php?r=<?php echo urlencode($f); ?>">Test with Manual Input</a>
<br><br>
<a href="/ajay/python3/index.php" style="background:#28a745;">Train Another Model</a>

</main>

<footer>
    Ajay Sah 2025 | All rights reserved
</footer>

</body>
</html>