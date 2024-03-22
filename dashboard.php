<!-- backend (server side) -->
<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:dashboard.php');
   exit();
};

?>
<!-- frontend(client side) -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<html>
<body>
   
<?php include 'header.php'; ?>

<section class="dashboard">

   <h1 class="title"> User dashboard</h1>

   <div class="box-container">
        <!-- total pendings -->
        <div class="box">
        <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                $total_pendings += $fetch_pendings['total_price'];
            };
        ?>
        <h3>&#8369;<?= $total_pendings; ?>/-</h3>
        <p>total pendings</p>
        <a href="orders.php" class="btn">see orders</a>
        </div>
        
        <!-- order placed -->
        <div class="box">
        <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
        ?>
        <h3><?= $number_of_orders; ?></h3>
        <p>orders placed</p>
        <a href="orders.php" class="btn">see orders</a>
        </div>
        <!-- wishlist -->
        <div class="box">
            <?php
                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $select_wishlist->execute([$user_id]);
                $number_of_wishlist = $select_wishlist->rowCount();
            ?>
            <h3><?= $number_of_wishlist; ?></h3>
            <p>Total Wishlist</p>
            <a href="wishlist.php" class="btn">See Wishlist</a>
        </div>

        <!-- cart -->
        <div class="box">
            <?php
                $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart_items->execute([$user_id]);
                $number_of_cart_items = $select_cart_items->rowCount();
            ?>
            <h3><?= $number_of_cart_items; ?></h3>
            <p>Total Items in Cart</p>
            <a href="cart.php" class="btn">See Cart</a>
        </div>
       
        <!-- track order -->
        <div class="box">
            <?php
            
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
                $select_orders->execute([$user_id]);
                $number_of_orders = $select_orders->rowCount();
            ?>
            <br>
            <br>
            <br>
            <p>Delivery/Rider</p>
            <a href="parcel/index.php" class="btn">Go To Orders</a>
        </div>
         <!-- user track orders -->
        <div class="box">
            <?php
            
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
                $select_orders->execute([$user_id]);
                $number_of_orders = $select_orders->rowCount();
            ?>
            <br>
            <br>
            <br>
            <p>User</p>
            <a href="user_notification/index.php" class="btn">Track Orders</a>
        </div>
       
    



   </div>

</section>













<script src="js/script.js"></script>

</body>
</html>