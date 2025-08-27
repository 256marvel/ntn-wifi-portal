 <?php
// Get user input
$payerPhone = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';

// Validate input
if (!$payerPhone || !$amount) {
    http_response_code(400);
        exit("Missing phone or amount");
        }

        // CONFIG
        $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSMjU2In0...'; // Your token
        $subscriptionKey = '09d303b8c9e94eb1a530d68418848f6a';
        $targetEnvironment = 'sandbox';
        $externalId = 'TXN' . rand(100000, 999999);
        $referenceId = generateUUID();

        // Request body
        $body = [
            "amount" => $amount,
                "currency" => "UGX",
                    "externalId" => $externalId,
                        "payer" => [
                                "partyIdType" => "MSISDN",
                                        "partyId" => $payerPhone
                                            ],
                                                "payerMessage" => "Payment for WiFi",
                                                    "payeeNote" => "Bentech WiFi"
                                                    ];

                                                    // Send request
                                                    $ch = curl_init("https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay");
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                                        "Authorization: Bearer $accessToken",
                                                            "X-Reference-Id: $referenceId",
                                                                "X-Target-Environment: $targetEnvironment",
                                                                    "Ocp-Apim-Subscription-Key: $subscriptionKey",
                                                                        "Content-Type: application/json"
                                                                        ]);

                                                                        $response = curl_exec($ch);
                                                                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                                        curl_close($ch);

                                                                        // Output
                                                                        echo "Status Code: $httpCode\n";
                                                                        echo "Response: $response\n";

                                                                        // UUID generator
                                                                        function generateUUID() {
                                                                            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                                                                                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                                                                                            mt_rand(0, 0xffff),
                                                                                                    mt_rand(0, 0x0fff) | 0x4000,
                                                                                                            mt_rand(0, 0x3fff) | 0x8000,
                                                                                                                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                                                                                                                        );
                                                                                                                        }
                                                                                                                        ?