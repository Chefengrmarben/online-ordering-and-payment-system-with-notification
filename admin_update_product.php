<?php
@include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
};

if(isset($_POST['update_product'])){
   try{
      $pid = $_POST['pid'];
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $category = $_POST['category'];
      $category = filter_var($category, FILTER_SANITIZE_STRING);
      $details = $_POST['details'];
      $details = filter_var($details, FILTER_SANITIZE_STRING);
      $quantity = $_POST['quantity'];//this line will retrieve quantity from database
      $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = './images/'.$image;
      $old_image = $_POST['old_image'];

      $conn->beginTransaction();
   
      $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
      $update_product->execute([$name, $category, $details, $price, $pid]);
   
      $message[] = 'product updated successfully!';
   
      if(!empty($image)){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
   
            $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $pid]);
   
            if($update_image){
               move_uploaded_file($image_tmp_name, $image_folder);
               unlink('./images/'.$old_image);
               $message[] = 'product updated successfully!';
            }
         }
      }
      $conn->commit();
      $select_updated_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_updated_product->execute([$pid]);
        $updated_product = $select_updated_product->fetch(PDO::FETCH_ASSOC);

        $message[] = 'Product updated successfully!';
   }catch(PDOException $e){
      $conn->rollBack();
      error_log("Error updating product: " .$e->getMessage());
      echo "An error occurred while updating the product.Please try again later.";
   }  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-product">

   <h1 class="title">update product</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
     /*  try{
         $pid = $_POST['pid'];
         $name = $_POST['name'];
         $name = filter_var($name, FILTER_SANITIZE_STRING);
         $price = $_POST['price'];
         $price = filter_var($price, FILTER_SANITIZE_STRING);
         $category = $_POST['category'];
         $category = filter_var($category, FILTER_SANITIZE_STRING);
         $details = $_POST['details'];
         $details = filter_var($details, FILTER_SANITIZE_STRING);
         
         $image = $_FILES['image']['name'];
         $image = filter_var($image, FILTER_SANITIZE_STRING);
         $image_size = $_FILES['image']['size'];
         $image_tmp_name = $_FILES['image']['tmp_name'];
         $image_folder = './images/'.$image;
         $old_image = $_POST['old_image'];
      }
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]); */
    
      // Set fetch style explicitly
      $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
      if ($fetch_products) {
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      /* if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ */ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <img src="images/<?= $fetch_products['image']; ?>" alt="">
      <input type="text" name="name" placeholder="enter product name" required class="box" value="<?= $fetch_products['name']; ?>">
      <input type="number" name="price" min="0" placeholder="enter product price" required class="box" value="<?= $fetch_products['price']; ?>">
      <select name="category" class="box" required>
         <option selected><?= $fetch_products['category']; ?></option>
         <option value="avomelt">AVOMELT</option>
         <option value="avosmooth">AVOSMOOTH</option>
         <option value="avopeel">AVOPEEL</option>
         <option value="avo ice cream scrub">AVO ICE CREAM SCRUB</option>
      </select>
      <textarea name="details" required placeholder="enter product details" class="box" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <div class="flex-btn">
         <input type="submit" class="btn" value="update product" name="update_product">
         <a href="admin_products.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   ?>

</section>













<script src="js/script.js"></script>

</body>
</html>