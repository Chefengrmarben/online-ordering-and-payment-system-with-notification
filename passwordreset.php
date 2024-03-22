<html>  
<head>  
    <title>Password Reset</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
</head>
<style>
 .box
 {
  width:100%;
  max-width:600px;
  background-color:#f9f9f9;
  border:1px solid #ccc;
  border-radius:5px;
  padding:16px;
  margin:0 auto;
 }
 input.parsley-success,
 select.parsley-success,
 textarea.parsley-success {
   color: #468847;
   background-color: #DFF0D8;
   border: 1px solid #D6E9C6;
 }

 input.parsley-error,
 select.parsley-error,
 textarea.parsley-error {
   color: #B94A48;
   background-color: #F2DEDE;
   border: 1px solid #EED3D7;
 }

 .parsley-errors-list {
   margin: 2px 0 3px;
   padding: 0;
   list-style-type: none;
   font-size: 0.9em;
   line-height: 0.9em;
   opacity: 0;

   transition: all .3s ease-in;
   -o-transition: all .3s ease-in;
   -moz-transition: all .3s ease-in;
   -webkit-transition: all .3s ease-in;
 }

 .parsley-errors-list.filled {
   opacity: 1;
 }
 
 .parsley-type, .parsley-required, .parsley-equalto{
  color:#ff0000;
 }
.error
{
  color: red;
  font-weight: 700;
} 
</style>
<?php
include_once('config.php');
include_once("SMTP/class.phpmailer.php");
include_once("SMTP/class.smtp.php")

if (isset($_REQUEST['pwdrst'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['pwd'];
    $confirmPassword = $_REQUEST['cpwd'];

    if ($password == $confirmPassword) {
        // Hash the password using password_hash
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password using PDO
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $result = $stmt->execute();

        if ($result) {
            $msg = 'Your password updated successfully <a href="index.php">Click here</a> to login';
        } else {
            $msg = "Error while updating password.";
        }
    } else {
        $msg = "Password and Confirm Password do not match";
    }
}

if (isset($_GET['secret'])) {
    $email = base64_decode($_GET['secret']);

    // Check if the email exists using PDO
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $res = $stmt->rowCount();

    if ($res > 0) {
        // Email exists in the database
       
        
    } else {
        // Email does not exist
    
    }
}
?>

<body>
<div class="container">  
    <div class="table-responsive">  
    <h3 align="center">Reset Password</h3><br/>
    <div class="box">
     <form id="validate_form" method="post" >  
      <input type="hidden" name="email" value="<?php echo $email; ?>"/>
      <div class="form-group">
       <label for="pwd">Password</label>
       <input type="password" name="pwd" id="pwd" placeholder="Enter Password" required 
       data-parsley-type="pwd" data-parsley-trigg
       er="keyup" class="form-control"/>
      </div>
      <div class="form-group">
       <label for="cpwd">Confirm Password</label>
       <input type="password" name="cpwd" id="cpwd" placeholder="Enter Confirm Password" required data-parsley-type="cpwd" data-parsley-trigg
       er="keyup" class="form-control"/>
      </div>
      <div class="form-group">
       <input type="submit" id="login" name="pwdrst" value="Reset Password" class="btn btn-success" />
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
     </form>
     </div>
   </div>  
  </div>
<?php } } ?>
</body>
</html>