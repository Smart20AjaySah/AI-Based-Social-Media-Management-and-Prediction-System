<?php
$memoryFile = 'python/memory.json';  // relative path from clear_memory.php
if(file_exists($memoryFile)){
    file_put_contents($memoryFile, json_encode(["history"=>[], "user_info"=>[]], JSON_PRETTY_PRINT));
}
echo json_encode(["status"=>"ok"]);
?>
