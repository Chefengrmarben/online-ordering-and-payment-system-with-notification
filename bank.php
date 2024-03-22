<?php

// Include configuration file with sensitive data (replace with placeholders)
require_once('payment_config.php');

// Payment method constants
define('PAYMENT_METHOD_CREDIT_CARD', 'credit_card');
define('PAYMENT_METHOD_GCASH', 'gcash');
define('PAYMENT_METHOD_PAYMAYA', 'paymaya');
define('PAYMENT_METHOD_BANK_TRANSFER', 'bank_transfer');
define('PAYMENT_METHOD_COD', 'cod');

// Supported bank codes (add more as needed)
$supportedBanks = array(
    'BDO' => 'BDOXYZ1234',
    'BPI' => 'BPIXYZ5678',
    'METROBANK' => 'METROXYZ9012',
    'UNIONBANK' => 'UNIONXYZ3456'
);

// Function to process payment based on selected method
function processPayment($paymentMethod, $orderData) {
    switch ($paymentMethod) {
        case PAYMENT_METHOD_CREDIT_CARD:
            $processor = new CreditCardProcessor(CARD_GATEWAY_URL, CARD_GATEWAY_KEY);
            $success = $processor->charge($orderData['amount'], $orderData['card_details']);
            break;
        case PAYMENT_METHOD_GCASH:
            $processor = new GCashProcessor(GCASH_API_KEY, GCASH_SECRET_KEY);
            $success = $processor->pay($orderData['amount'], $orderData['gcash_number']);
            break;
        case PAYMENT_METHOD_PAYMAYA:
            $processor = new PayMayaProcessor(PAYMAYA_PUBLIC_KEY, PAYMAYA_SECRET_KEY);
            $success = $processor->checkout($orderData['amount'], $orderData['paymaya_details']);
            break;
        case PAYMENT_METHOD_BANK_TRANSFER:
            $success = validateBankTransfer($orderData['bank'], $orderData['account_number']);
            break;
        case PAYMENT_METHOD_COD:
            $processor = new CODProcessor();
            $success = $processor->markOrderCOD($orderData['id']);
            break;
        default:
            $success = false;
            break;
    }

    return $success;
}

// Function to validate bank transfer details (replace with actual validation logic)
function validateBankTransfer($bank, $accountNumber) {
    global $supportedBanks;

    if (!isset($supportedBanks[$bank])) {
        return false; // Bank not supported
    }

    // Implement additional validation for account number format and existence (e.g., API calls)

    return true;
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    $orderData = array(
        "id" => filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT),
        "amount" => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        "card_details" => array(
            "number" => filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_STRING),
            "expiry" => filter_input(INPUT_POST, 'card_expiry', FILTER_SANITIZE_STRING),
            "cvv" => filter_input(INPUT_POST, 'card_cvv', FILTER_SANITIZE_STRING)
        ),
        "gcash_number" => filter_input(INPUT_POST, 'gcash_number', FILTER_SANITIZE_STRING),
        "paymaya_details" => array(
            "wallet_number" => filter_input(INPUT_POST, 'paymaya_wallet_number', FILTER_SANITIZE_STRING),
            "reference_id" => filter_input(INPUT_POST, 'paymaya_reference_id', FILTER_SANITIZE_STRING)
        ),
        "bank" => filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_STRING),
        "account_number" => filter_input(INPUT_POST, 'account_number', FILTER_SANITIZE_STRING)
    );

    if (processPayment($paymentMethod, $orderData)) {
        echo "Payment successful!";
    } else {
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>
