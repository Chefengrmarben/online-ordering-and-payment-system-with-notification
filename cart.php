<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = :delete_id");
   $delete_cart_item->execute(['delete_id'=>$delete_id]);
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
   if (isset($fetch_cart) && is_array($fetch_cart)) {
       $cart_id = $_POST['cart_id'];
       $p_qty_key = 'p_qty' . $fetch_cart['id'];

       if (isset($_POST[$p_qty_key])) {
           $p_qty = filter_var($_POST[$p_qty_key], FILTER_VALIDATE_INT);

           if ($p_qty !== false && $p_qty >= 0) {
               $update_qty = $conn->prepare("UPDATE `cart` SET quantity=? WHERE id =?");
               if ($update_qty->execute([$p_qty, $cart_id])) {
                   $message[] = 'Cart quantity updated';
               } else {
                   $message[] = 'Error updating cart quantity';
               }
           } else {
               $message[] = 'Invalid quantity value';
           }
       } else {
           $message[] = 'Quantity key not found in $_POST';
       }
   } else {
       $message[] = '$fetch_cart is not set or is null';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">


   

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="headingorders">
   <h3>Shopping Cart</h3>
   <p> <a href="home.php">Home</a> / Shopping Cart </p>
</div>

<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">

      

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
      <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="images/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="price">&#8369;<?= $fetch_cart['price']; ?>/-</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty<?=$fetch_cart['id'];?>">
         <input type="submit" value="update" name="update_qty" class="readbtnen">
      </div>
      <div class="sub-total"> sub total : <span>&#8369;<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>&#8369;<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="btn">Continue Shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':''; ?>">Delete All</a>
      <a href="checkout.php" class="option-btn <?= ($grand_total > 1)?'':''; ?>">Proceed To Checkout</a>
   </div>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/cart.js"></script>

</body>
</html>