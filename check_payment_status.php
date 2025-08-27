<?php
/**
 * Payment Status Checker for MTN Mobile Money
 * Checks payment status using reference ID
 */

// Configuration
$apiUserId = 'b118d864-b932-41fa-bc53-17d2841772ee';
$apiKey = '127fb39cbddc47dc8220c3ebd4244cc2';
$subscriptionKey = '09d303b8c9e94eb1a530d68418848f6a';
$targetEnvironment = 'mtnuganda';

// Get reference ID from request
$referenceId = $_GET['reference_id'] ?? $_POST['reference_id'] ?? '';

if (!$referenceId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing reference ID']);
    exit;
}

// Function to get access token (reuse from momo_request.php)
function getAccessToken($apiUserId, $apiKey, $subscriptionKey) {
    $tokenFile = 'mtn_token.json';
    
    if (file_exists($tokenFile)) {
        $tokenData = json_decode(file_get_contents($tokenFile), true);
        if ($tokenData && isset($tokenData['token']) && isset($tokenData['expires_at'])) {
            if (time() < ($tokenData['expires_at'] - 300)) {
                return $tokenData['token'];
            }
        }
    }
    
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
            $tokenData = [
                'token' => $data['access_token'],
                'expires_at' => time() + 3300,
                'generated_at' => time()
            ];
            file_put_contents($tokenFile, json_encode($tokenData));
            return $data['access_token'];
        }
    }
    
    return null;
}

// Function to check payment status
function checkPaymentStatus($accessToken, $subscriptionKey, $targetEnvironment, $referenceId) {
    $url = "https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay/$referenceId";

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'X-Target-Environment: ' . $targetEnvironment,
        'Ocp-Apim-Subscription-Key: ' . $subscriptionKey
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'httpCode' => $httpCode,
        'response' => $response ? json_decode($response, true) : null
    ];
}

try {
    $accessToken = getAccessToken($apiUserId, $apiKey, $subscriptionKey);

    if (!$accessToken) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to authenticate with MTN API']);
        exit;
    }

    $result = checkPaymentStatus($accessToken, $subscriptionKey, $targetEnvironment, $referenceId);

    if ($result['httpCode'] === 200 && $result['response']) {
        $paymentData = $result['response'];
        
        // Log status check
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'reference_id' => $referenceId,
            'status' => $paymentData['status'] ?? 'unknown',
            'response' => $paymentData
        ];
        file_put_contents('payment_status_log.txt', json_encode($logData) . "\n", FILE_APPEND);

        echo json_encode([
            'success' => true,
            'reference_id' => $referenceId,
            'status' => $paymentData['status'] ?? 'PENDING',
            'amount' => $paymentData['amount'] ?? null,
            'currency' => $paymentData['currency'] ?? null,
            'financial_transaction_id' => $paymentData['financialTransactionId'] ?? null,
            'external_id' => $paymentData['externalId'] ?? null,
            'reason' => $paymentData['reason'] ?? null
        ]);
    } else {
        http_response_code($result['httpCode'] ?: 500);
        echo json_encode([
            'error' => 'Failed to check payment status',
            'http_code' => $result['httpCode'],
            'reference_id' => $referenceId
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>