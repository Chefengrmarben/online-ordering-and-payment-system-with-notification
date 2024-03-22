<?php
// Include the Paymongo configuration
include 'paymongo/initialize.php';
$config = include 'paymongo_config.php';

// Function to process payment via GCash
function processGCashPayment($amount, $gcashNumber, $secretKey) {
    $url = 'https://api.paymongo.com/v1/sources';

    $data = [
        'data' => [
            'attributes' => [
                'type' => 'gcash',
                'amount' => $amount,
                'currency' => $config['currency'],
                'redirect' => [
                    'success' => 'https://your-website.com/success.php',
                    'failed' => 'https://your-website.com/failed.php',
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

    if ($responseData === null || !isset($responseData['data']['attributes']['redirect']['checkout_url'])) {
        // Handle invalid response
        throw new Exception('Invalid response from Paymongo API.');
    }

    $checkoutUrl = $responseData['data']['attributes']['redirect']['checkout_url'];
    // Redirect user to the GCash checkout URL
    header("Location: $checkoutUrl");
    exit;
}

// Example usage
try {
    $amount = 1000; // Example amount in cents (PHP 10.00)
    $gcashNumber = '1234567890'; // Replace with user's GCash number
    processGCashPayment($amount, $gcashNumber, $config['secret_key']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
