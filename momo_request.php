<?php
/**
 * MTN Mobile Money Payment Request Handler
 * Production-ready with dynamic token generation and auto-renewal
 */

// === CONFIGURATION ===
$apiUserId = 'b118d864-b932-41fa-bc53-17d2841772ee';
$apiKey = '127fb39cbddc47dc8220c3ebd4244cc2';
$subscriptionKey = '09d303b8c9e94eb1a530d68418848f6a';
$targetEnvironment = 'mtnuganda';
$callbackUrl = 'https://ntenjeruwifi.infinityfreeapp.com/momo-callback.php';

// === DYNAMIC INPUT (from frontend or form) ===
$payerPhone = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';
$planName = $_POST['plan'] ?? 'WiFi Package';

// Validate input
if (!$payerPhone || !$amount) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing phone number or amount']);
    exit;
}

// Validate phone number format
$phone = preg_replace('/[^0-9]/', '', $payerPhone);
if (!preg_match('/^(256|0)(7[0-9]{8}|3[0-9]{8})$/', $phone)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid phone number format. Use 256xxxxxxxxx or 07xxxxxxxx']);
    exit;
}

// Normalize phone number
if (substr($phone, 0, 1) === '0') {
    $phone = '256' . substr($phone, 1);
}

$currency = 'UGX';
$externalId = uniqid('NTENJERU_');
$referenceId = generateUUID();

// === STEP 1: Generate Access Token with Auto-Renewal ===
function getAccessToken($apiUserId, $apiKey, $subscriptionKey) {
    // Check if token exists and is valid
    $tokenFile = 'mtn_token.json';
    $token = null;
    
    if (file_exists($tokenFile)) {
        $tokenData = json_decode(file_get_contents($tokenFile), true);
        if ($tokenData && isset($tokenData['token']) && isset($tokenData['expires_at'])) {
            // Check if token is still valid (refresh 5 minutes before expiry)
            if (time() < ($tokenData['expires_at'] - 300)) {
                return $tokenData['token'];
            }
        }
    }
    
    // Generate new token
    $credentials = base64_encode($apiUserId . ':' . $apiKey);
    $url = 'https://proxy.momoapi.mtn.com/collection/token/';

    $headers = [
        'Authorization: Basic ' . $credentials,
        'Ocp-Apim-Subscription-Key: ' . $subscriptionKey
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['access_token'])) {
            // Save token with expiry time (tokens typically expire in 1 hour)
            $tokenData = [
                'token' => $data['access_token'],
                'expires_at' => time() + 3300, // 55 minutes from now
                'generated_at' => time()
            ];
            file_put_contents($tokenFile, json_encode($tokenData));
            return $data['access_token'];
        }
    }
    
    return null;
}

// === STEP 2: Send Payment Request ===
function sendPaymentRequest($accessToken, $subscriptionKey, $targetEnvironment, $callbackUrl, $payerPhone, $amount, $currency, $externalId, $referenceId, $planName) {
    $url = 'https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay';

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'X-Reference-Id: ' . $referenceId,
        'X-Target-Environment: ' . $targetEnvironment,
        'Ocp-Apim-Subscription-Key: ' . $subscriptionKey,
        'Content-Type: application/json'
    ];

    $body = json_encode([
        'amount' => $amount,
        'currency' => $currency,
        'externalId' => $externalId,
        'payer' => [
            'partyIdType' => 'MSISDN',
            'partyId' => $payerPhone
        ],
        'payerMessage' => 'Payment for ' . $planName,
        'payeeNote' => 'NTENJERU WIFI - ' . $planName,
        'callbackUrl' => $callbackUrl
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'httpCode' => $httpCode,
        'referenceId' => $referenceId,
        'externalId' => $externalId,
        'response' => $response ? json_decode($response, true) : null
    ];
}

// === STEP 3: Execute Payment Process ===
try {
    $accessToken = getAccessToken($apiUserId, $apiKey, $subscriptionKey);

    if (!$accessToken) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to authenticate with MTN API']);
        exit;
    }

    $result = sendPaymentRequest(
        $accessToken, 
        $subscriptionKey, 
        $targetEnvironment, 
        $callbackUrl, 
        $phone, 
        $amount, 
        $currency, 
        $externalId, 
        $referenceId, 
        $planName
    );

    // Log transaction for debugging
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'phone' => $phone,
        'amount' => $amount,
        'plan' => $planName,
        'reference_id' => $referenceId,
        'external_id' => $externalId,
        'http_code' => $result['httpCode'],
        'response' => $result['response']
    ];
    file_put_contents('payment_log.txt', json_encode($logData) . "\n", FILE_APPEND);

    if ($result['httpCode'] === 202) {
        // Success - payment request accepted
        echo json_encode([
            'success' => true,
            'message' => 'Payment request sent successfully! Check your phone for MTN Mobile Money prompt.',
            'reference_id' => $referenceId,
            'external_id' => $externalId,
            'amount' => $amount,
            'phone' => $phone
        ]);
    } else {
        // Error occurred
        $errorMessage = 'Payment request failed';
        if ($result['response'] && isset($result['response']['message'])) {
            $errorMessage = $result['response']['message'];
        }
        
        http_response_code($result['httpCode'] ?: 500);
        echo json_encode([
            'error' => $errorMessage,
            'http_code' => $result['httpCode'],
            'reference_id' => $referenceId
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}

// === UUID Generator ===
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>