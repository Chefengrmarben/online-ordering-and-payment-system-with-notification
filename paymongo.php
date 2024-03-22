<?php
// paymongo-payment.php

// Include the Paymongo configuration
$config = include 'paymongo_config.php';

// Function to create a payment intent
function createPaymentIntent($amount, $currency, $paymentMethodId, $secretKey) {
    $url = 'https://api.paymongo.com/v1/payment_intents';

    $data = [
        'data' => [
            'attributes' => [
                'amount' => $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethodId,
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

    // Debugging code - Output API response for debugging
    var_dump($response);

    if ($response === false) {
        // Handle request failure
        throw new Exception('Failed to make API request to Paymongo.');
    }

    $responseData = json_decode($response, true);

    if ($responseData === null || !isset($responseData['data']['attributes']['client_secret'])) {
        // Handle invalid response
        throw new Exception('Invalid response from Paymongo API.');
    }

    return $responseData['data']['attributes']['client_secret'];
}

// Example usage
try {
    $amount = 1000; // Example amount in cents (PHP 10.00)
    $currency = $config['currency'];
    $paymentMethodId = 'your_payment_method_id';
    $clientSecret = createPaymentIntent($amount, $currency, $paymentMethodId, $config['secret_key']);
    echo "Payment Intent created successfully. Client Secret: $clientSecret";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
