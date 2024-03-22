<?php
// Include the Paymongo configuration
$config = include 'paymongo_config.php';

// Function to process payment via Credit Card
function processCreditCardPayment($amount, $cardDetails, $secretKey) {
    $url = 'https://api.paymongo.com/v1/payments';

    $data = [
        'data' => [
            'attributes' => [
                'amount' => $amount,
                'currency' => $config['currency'],
                'payment_method_details' => [
                    'card' => $cardDetails,
                ],
            ],
        ],
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($secretKey . ':'),
    ];

    $options = [
        'http' => [
            'header' => $headers,
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        // Handle request failure
        throw new Exception('Failed to make API request to Paymongo.');
    }

    $responseData = json_decode($response, true);

    if ($responseData === null || !isset($responseData['data']['id'])) {
        // Handle invalid response
        throw new Exception('Invalid response from Paymongo API.');
    }

    // Payment successful, handle further processing if needed
    echo "Payment successful!";
}

// Example usage
try {
    $amount = 1000; // Example amount in cents (PHP 10.00)
    $cardDetails = [
        'number' => '4111111111111111', // Example card number
        'expiry' => '12/24', // Example expiry date
        'cvv' => '123', // Example CVV
    ];
    processCreditCardPayment($amount, $cardDetails, $config['secret_key']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
