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
$paymentMethod = $_POST['payment_method']; // Get payment method from user selection
$orderData = array(
  "id" => 123, // Order ID
  "amount" => 1000, // Order amount
  "card_details" => array( // Credit card details (if applicable)
    "number" => "...",
    "expiry" => "...",
    "cvv" => "..."
  ),
  "gcash_number" => "...", // GCash number (if applicable)
  "paymaya_details" => array( // PayMaya details (if applicable)
    "wallet_number" => "...",
    "reference_id" => "..."
  ),
  "bank" => "BDO", // Bank code (if bank transfer)
  "account_number" => "..." // Account number (if bank transfer)
);

if (processPayment($paymentMethod, $orderData)) {
  echo "Payment successful!";
} else {
  echo "Payment failed. Please try again.";
}

?>