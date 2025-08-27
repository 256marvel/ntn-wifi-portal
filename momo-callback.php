 <?php
// momo-callback.php

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode JSON payload
$data = json_decode($rawData, true);

// Log or process the data
file_put_contents("momo_log.txt", print_r($data, true), FILE_APPEND);

// Respond with 200 OK
http_response_code(200);
echo "Callback received";
?