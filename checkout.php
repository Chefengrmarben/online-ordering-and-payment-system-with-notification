<?php
// Include Paymongo configuration and payment processing logic
require_once 'paymongo_config.php';
require_once 'paymongo/initialize.php';
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['order'])) {

    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // Perform additional validation for address fields if needed

    // Construct address
    $address = 'cavite' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    $placed_on = date('d-M-Y');

    // Fetch cart items and calculate total
    $cart_total = 0;
    $cart_products = array();
    $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $cart_query->execute([$user_id]);

    if ($cart_query->rowCount() > 0) {
        while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
            $cart_products[] = $cart_item['name'] . ' ( ' . $cart_item['quantity'] . ' )';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    // Check if the cart is empty
    if ($cart_total == 0) {
        $message[] = 'Your cart is empty';
    } else {
        // Check if the order has already been placed
        $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
        $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

        if ($order_query->rowCount() > 0) {
            $message[] = 'Order already placed';
        } else {
            // Insert order into database
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);

            // Clear cart
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'Order placed successfully';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include meta tags, title, and external CSS/JS files -->
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <!-- Include header -->
    <?php include 'header.php'; ?>

    <!-- Display cart items -->
    <section class="display-orders">
        <!-- Display cart items and total -->
        <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= '&#8369;'.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">Your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>&#8369;<?= $cart_grand_total; ?>/-</span></div>
    </section>

    <!-- Checkout form -->
    <section class="checkout-orders">
        <!-- Include form to place order -->
        <form action="" method="POST">

      <h3>place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Enter your name :</span>
            <input type="text" name="name" placeholder="Enter Your Name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Enter your number :</span>
            <input type="number" name="number" placeholder="Enter Your Number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Enter your email :</span>
            <input type="email" name="email" placeholder="Enter Your Email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash On Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="gcash">Gcash</option>
               <option value="paymaya">Paymaya</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address line 01 :</span>
            <input type="text" name="" placeholder="e.g. Flat Number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. Street Name" class="box" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="e.g. Manila" class="box" required>
         </div>
         <div class="inputBox">
            <span>State :</span>
            <input type="text" name="state" placeholder="e.g. Calabarzon" class="box" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="e.g. Philippines" class="box" required>
         </div>
         <div class="inputBox">
            <span>Pin Code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <button type="submit" id="order-btn" name="order" class="btn <?= ($cart_grand_total > 1)?'':''; ?>">place order</button>
   </form>
    </section>
    <!-- JavaScript code here -->
    <script>
function redirectOnPaymentChange(paymentMethodSelect) {
    // Get the selected value
    var selectedValue = paymentMethodSelect.value;

    // Define the redirection URLs based on the selected payment method
    var redirectionURLs = {
        "cash on delivery": "#", // Modify the URL as needed
        "credit card": "credit_card.php", // Modify the URL as needed
        "gcash": "gcash.php", // Modify the URL as needed
        "paymaya": "paymaya.php" // Modify the URL as needed
    };

    // Check if a payment method is selected and has a corresponding redirection URL
    if (selectedValue && redirectionURLs[selectedValue]) {
        // Redirect to the specified URL
        window.location.href = redirectionURLs[selectedValue];
    } else {
        // Handle the case where no payment method is selected or no URL is defined
        console.error("Invalid payment method or URL not defined.");
        // Optionally, you can display an error message to the user or handle the error gracefully.
    }
}
</script>


    <!-- Include footer -->
    <?php include 'footer.php'; ?>

    <!-- Include custom scripts -->
    <script src="js/script.js"></script>
</body>

</html>
