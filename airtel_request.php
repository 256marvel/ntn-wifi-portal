<?php
/**
 * Airtel Money Payment Request Handler
 * Professional integration with dynamic token management
 */

// Prevent direct access
if (!defined('ABSPATH') && !isset($_POST['phone'])) {
    exit('Direct access not allowed');
}

// Get configuration from WordPress options or direct call
if (defined('ABSPATH')) {
    $apiKey = get_option('airtel_api_key', '');
    $clientId = get_option('airtel_client_id', '');
    $clientSecret = get_option('airtel_client_secret', '');
    $environment = get_option('airtel_environment', 'sandbox');
    $callbackUrl = get_option('airtel_callback_url', home_url('/airtel-callback/'));
} else {
    // Direct configuration for standalone usage
    $apiKey = 'your_airtel_api_key';
    $clientId = 'your_client_id';
    $clientSecret = 'your_client_secret';
    $environment = 'sandbox'; // or 'production'
    $callbackUrl = 'https://ntenjeruwifi.infinityfreeapp.com/airtel-callback.php';
}

// Input validation and sanitization
$payerPhone = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';
$planName = $_POST['plan'] ?? 'WiFi Package';

// Validate input
if (!$payerPhone || !$amount) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing phone number or amount']);
    exit;
}

// Validate phone number format for Airtel Uganda
$phone = preg_replace('/[^0-9]/', '', $payerPhone);
if (!preg_match('/^(256|0)(7[0-9]{8}|2[0-9]{8})$/', $phone)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid Airtel phone number. Use 256xxxxxxxxx or 07xxxxxxxx']);
    exit;
}

// Normalize phone number
if (substr($phone, 0, 1) === '0') {
    $phone = '256' . substr($phone, 1);
}

$currency = 'UGX';
$transactionId = uniqid('AIRTEL_');
$referenceId = generateUUID();

// Base URLs for different environments
$baseUrls = [
    'sandbox' => 'https://openapiuat.airtel.africa',
    'production' => 'https://openapi.airtel.africa'
];
$baseUrl = $baseUrls[$environment];

/**
 * Get Access Token with Auto-Renewal
 */
function getAirtelAccessToken($clientId, $clientSecret, $baseUrl) {
    // Check if token exists and is valid
    $tokenFile = 'airtel_token.json';
    
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
    $url = $baseUrl . '/auth/oauth2/token';
    
    $headers = [
        'Content-Type: application/json',
        'Accept: */*'
    ];
    
    $body = json_encode([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'client_credentials'
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['access_token'])) {
            // Save token with expiry time
            $tokenData = [
                'token' => $data['access_token'],
                'expires_at' => time() + ($data['expires_in'] - 300), // 5 minutes buffer
                'generated_at' => time()
            ];
            file_put_contents($tokenFile, json_encode($tokenData));
            return $data['access_token'];
        }
    }
    
    return null;
}

/**
 * Send Airtel Money Payment Request
 */
function sendAirtelPaymentRequest($accessToken, $baseUrl, $payerPhone, $amount, $currency, $transactionId, $referenceId, $planName) {
    $url = $baseUrl . '/merchant/v1/payments/';
    
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
        'Accept: */*',
        'X-Country: UG',
        'X-Currency: ' . $currency
    ];
    
    $body = json_encode([
        'reference' => $referenceId,
        'subscriber' => [
            'country' => 'UG',
            'currency' => $currency,
            'msisdn' => $payerPhone
        ],
        'transaction' => [
            'amount' => $amount,
            'country' => 'UG',
            'currency' => $currency,
            'id' => $transactionId
        ]
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'httpCode' => $httpCode,
        'referenceId' => $referenceId,
        'transactionId' => $transactionId,
        'response' => $response ? json_decode($response, true) : null
    ];
}

// Execute Payment Process
try {
    if (empty($clientId) || empty($clientSecret)) {
        http_response_code(500);
        echo json_encode(['error' => 'Airtel Money API not configured. Please check settings.']);
        exit;
    }
    
    $accessToken = getAirtelAccessToken($clientId, $clientSecret, $baseUrl);
    
    if (!$accessToken) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to authenticate with Airtel API']);
        exit;
    }
    
    $result = sendAirtelPaymentRequest(
        $accessToken,
        $baseUrl,
        $phone,
        $amount,
        $currency,
        $transactionId,
        $referenceId,
        $planName
    );
    
    // Log transaction
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'phone' => $phone,
        'amount' => $amount,
        'plan' => $planName,
        'reference_id' => $referenceId,
        'transaction_id' => $transactionId,
        'http_code' => $result['httpCode'],
        'response' => $result['response']
    ];
    file_put_contents('airtel_payment_log.txt', json_encode($logData) . "\n", FILE_APPEND);
    
    if ($result['httpCode'] === 200 || $result['httpCode'] === 201) {
        echo json_encode([
            'success' => true,
            'message' => 'Payment request sent successfully! Check your phone for Airtel Money prompt.',
            'reference_id' => $referenceId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'phone' => $phone
        ]);
    } else {
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

/**
 * Generate UUID
 */
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