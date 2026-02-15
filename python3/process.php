<?php
// ============================================================
// CONFIG
// ============================================================
$uploadDir = 'C:/xampp/htdocs/ajay/python3/python/uploaded_csv/';

// Windows path with spaces handled safely
$pythonBin = 'C:/Users/RAMAN KUMAR/AppData/Local/Programs/Python/Python310/python.exe';
$modelScript = 'C:/xampp/htdocs/ajay/python3/python/model.py';


// ============================================================
// FUNCTION: parse CSV properly handling last column/newline
// ============================================================
function parse_csv($filepath, $max_preview = 20) {
    $content = file_get_contents($filepath);
    $content = str_replace(["\r\n", "\r"], "\n", $content);
    $lines = explode("\n", $content);

    $header = [];
    $rows = [];
    foreach ($lines as $i => $line) {
        $line = trim($line);
        if ($line === '') continue;

        $row = str_getcsv($line);
        foreach ($row as &$c) $c = trim($c, " \t\n\r\0\x0B\"'");
        unset($c);

        if ($i === 0) {
            $header = $row;
        } else {
            while (count($row) < count($header)) $row[] = '';
            while (count($row) > count($header)) {
                $row[count($header)-1] .= ' ' . array_pop($row);
            }
            if (count($rows) < $max_preview) $rows[] = $row;
        }
    }
    return [$header, $rows];
}


// ============================================================
// 1) CSV UPLOAD → SHOW COLUMN SELECTOR + PREVIEW
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfile'])) {
    $file = $_FILES['csvfile'];
    if ($file['error'] !== UPLOAD_ERR_OK) die('Upload failed. Error code: ' . $file['error']);
    if (!is_dir($uploadDir)) die("Upload folder missing: $uploadDir");

    $fname = preg_replace("/[^A-Za-z0-9_.-]/", "_", basename($file['name']));
    $target = $uploadDir . $fname;
    if (!move_uploaded_file($file['tmp_name'], $target)) die('Cannot move uploaded file.');

    list($header, $rows) = parse_csv($target, 20);
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Select Columns</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            html, body { height:100%; }
            body { font-family: Arial, sans-serif; background-color: #1c1c1c; color: #fff; display:flex; flex-direction:column; }
            header { background: #2c2c2c; padding: 15px 20px; text-align:center; box-shadow: 0 2px 4px rgba(0,0,0,0.3); }
            header h1 { font-size:24px; margin:0; font-weight:bold; color:#fff; text-shadow:0 0 2px #fff; }
            header h1 .star { color:#f7f753ff; text-shadow: 0 0 5px #ffeb3b, 0 0 10px #ffeb3b; animation: star-glow 2s ease-in-out infinite alternate; }
            @keyframes star-glow { 0% {text-shadow:0 0 3px #ffeb3b,0 0 6px #ffeb3b;} 50% {text-shadow:0 0 6px #ffeb3b,0 0 12px #ffeb3b;} 100% {text-shadow:0 0 3px #ffeb3b,0 0 6px #ffeb3b;} }
            .container { max-width:1000px; width:100%; background:#ffeb3b; padding:20px; margin:20px auto; border-radius:10px; box-shadow:0 0 8px rgba(0,0,0,0.1); flex:1; color:#000; }
            .table-box { overflow:auto; max-height:300px; border:1px solid #ddd; margin-top:10px; }
            table { width:100%; border-collapse:collapse; min-width:700px; }
            th, td { padding:8px 10px; border:1px solid #000000ff; font-size:14px; white-space:nowrap; }
            th { background:#007bff; color:white; position:sticky; top:0; z-index:10; }
            select, input[type=number] { width:100%; padding:10px; border:1px solid #090606ff; border-radius:6px; margin-bottom:15px; }
            button { padding:10px 15px; border:none; border-radius:6px; background:#007bff; color:white; font-size:16px; cursor:pointer; }
            button:hover { background:#005bcc; }
            footer { text-align:center; padding:15px 0; background:#fff; color:#555; font-size:14px; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
            #back-btn { text-decoration:none; color:#007bff; font-weight:bold; margin-right:10px; font-size:1rem; transition:color 0.2s; }
            #back-btn:hover { color:#0056b3; text-decoration:underline; }
        </style>
    </head>
    <body>
    <header><h1>Smart <span class="star">🌟</span></h1></header>

    <div class="container">
        <a id="back-btn" href="/ajay/owner/index.php">&larr; Back</a>
        <h2>Column Selection</h2>
        <p><strong>File:</strong> <?php echo htmlspecialchars($fname); ?></p>
        <p><b>Preview (first 20 rows)</b></p>

        <div class="table-box">
            <table>
                <thead><tr>
                    <?php foreach ($header as $col): ?>
                        <th><?php echo htmlspecialchars($col); ?></th>
                    <?php endforeach; ?>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <?php foreach ($r as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <br>
        <form method="post" action="process.php">
            <input type="hidden" name="uploaded_name" value="<?php echo htmlspecialchars($fname); ?>">

            <p><b>Select Input (X) Columns</b></p>
            <select name="xcols[]" multiple size="5" style="background-color:lightcoral;">
                <?php foreach ($header as $col): ?>
                    <option value="<?php echo htmlspecialchars($col); ?>"><?php echo htmlspecialchars($col); ?></option>
                <?php endforeach; ?>
            </select>

            <p><b>Select Target (Y) Column</b></p>
            <select name="ycol" required style="background-color:lightcoral;">
                <?php foreach ($header as $col): ?>
                    <option value="<?php echo htmlspecialchars($col); ?>"><?php echo htmlspecialchars($col); ?></option>
                <?php endforeach; ?>
            </select>

            <p><b>Epochs</b> (default 200)</p>
            <input type="number" name="epochs" min="1" value="200">

            <button type="submit">Train Model</button>
        </form>
    </div>

    <footer>Ajay Sah 2025 | all rights reserved</footer>
    </body>
    </html>
    <?php
    exit;
}


// ============================================================
// 2) RUN PYTHON MODEL
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uploaded_name'], $_POST['ycol'])) {
    $fname = basename($_POST['uploaded_name']);
    $csvPath = $uploadDir . $fname;
    if (!file_exists($csvPath)) die("CSV not found: $csvPath");

    $xcols = $_POST['xcols'] ?? [];
    $xcols = array_map(function($c){ return trim($c, " \t\n\r\0\x0B\"'"); }, $xcols);
    if (empty($xcols)) die("Select input columns.");

    $ycol = trim($_POST['ycol'], " \t\n\r\0\x0B\"'");
    $epochs = intval($_POST['epochs'] ?? 200);
    $xcols_str = implode(",", $xcols);

    // ==========================
    // Build Python command properly with double quotes around paths with spaces
    // ==========================
    $cmd = '"' . $pythonBin . '" ' .
           escapeshellarg($modelScript) . ' ' .
           escapeshellarg($csvPath) . ' ' .
           '--xcols ' . escapeshellarg($xcols_str) . ' ' .
           '--ycol ' . escapeshellarg($ycol) . ' ' .
           '--epochs ' . escapeshellarg($epochs);

    $output = [];
    $code = 0;
    exec($cmd . ' 2>&1', $output, $code);

    if ($code !== 0) {
        echo "<h2>Python Execution Failed</h2>";
        echo "<b>Command:</b><br><pre>$cmd</pre><br>";
        echo "<b>Error Output:</b><br><pre>" . htmlspecialchars(implode("\n", $output)) . "</pre>";
        exit;
    }

    $resultFile = "result_" . pathinfo($fname, PATHINFO_FILENAME) . ".json";
    $resultPath = $uploadDir . $resultFile;
    file_put_contents($resultPath, implode("\n", $output));

    header("Location: /ajay/python3/result.php?f=" . urlencode($resultFile));
    exit;
}


// ============================================================
// 3) FALLBACK
// ============================================================
header("Location: /ajay/python3/index.php");
exit;
?>
