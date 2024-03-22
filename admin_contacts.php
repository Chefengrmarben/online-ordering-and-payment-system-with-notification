<!-- backend (server side) -->
<?php
@include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
// if admin id is not set,redirect to the login page and exit script execution
if(!isset($admin_id)) {
   header('location:login.php');
   exit(); // this line of code will make sure to stop script execution after redirecting
};
// if delete parameter is set in the URL
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete']; // get the message id to be deleted
   try{
      $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
      $delete_message->execute([$delete_id]);
      //this line will check if any rows were affected
      if($delete_message->rowCount() > 0){
         header('location:admin_contacts.php');
         exit();
      }else{
         // this line will handle the case when rows were deleted
         echo "No message found with ID $delete_id.";
      }
   }catch (PDOException $e){
      // this line will handle database errors
      echo "error:" .$e->getMessage();
   }
}
?>
<!-- frontend (client side) -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">messages</h1>
<!-- container for displaying messages -->
   <div class="box-container">

   <?php
      // select all messages from database
      $select_message = $conn->prepare("SELECT * FROM `message`");
      $select_message->execute();
      // if there are messages this line will display them  
      if($select_message->rowCount() > 0){
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
   ?>
   <!-- message box displaying user information and user information -->
   <div class="box">
      <p> user id : <span><?= $fetch_message['user_id']; ?></span> </p>
      <p> name : <span><?= $fetch_message['name']; ?></span> </p>
      <p> number : <span><?= $fetch_message['number']; ?></span> </p>
      <p> email : <span><?= $fetch_message['email']; ?></span> </p>
      <p> message : <span><?= $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">you have no messages!</p>';
      }
   ?>

   </div>

</section>













<script src="js/script.js"></script>

</body>
</html>