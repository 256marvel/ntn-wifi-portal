 <?php
$uuid = 'b118d864-b932-41fa-bc53-17d2841772ee';
$primaryKey = '09d303b8c9e94eb1a530d68418848f6a';

$url = 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/' . $uuid . '/apikey';

$headers = [
    'Ocp-Apim-Subscription-Key: ' . $primaryKey
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output result
echo "<h3>Response Code: $httpCode</h3>";
echo "<pre>$response</pre>";
?