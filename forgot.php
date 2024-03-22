<html>  
<head>  
    <title>Forgot Password</title>  
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
require_once('vendor\phpmailer\phpmailer\src\PHPMailer.php');
require_once('vendor\phpmailer\phpmailer\src\SMTP.php');
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
// database credentials
$host = 'localhost';
$dbname = 'grocery_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Now $pdo is a valid PDO connection object


if (isset($_REQUEST['pwdrst'])) {
    $email = $_REQUEST['email'];

    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $res = $stmt->rowCount();

    if ($res > 0) {
        // Email exists in the database
        echo "Email exists in the database.";
        
        $message = '<div>
            <p><b>Hello!</b></p>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <br>
            <p><button class="btn btn-primary"><a href="http://localhost/user-login/passwordreset.php?secret='.base64_encode($email).'">Reset Password</a></button></p>
            <br>
            <p>If you did not request a password reset, no further action is required.</p>
        </div>';
        
        $email = $email;
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->Username = "marbenalonzo@trimexcolleges.edu.ph";   // Enter your username/emailid
        $mail->Password = "9012";   // Enter your password
        $mail->FromName = "Tech Area";
        $mail->AddAddress($email);
        $mail->Subject = "Reset Password";
        $mail->isHTML(TRUE);
        $mail->Body = $message;
        
        if ($mail->send()) {
            $msg = "We have e-mailed your password reset link!";
        } else {
            $msg = "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        // Email does not exist
        echo "Email does not exist in the database.";
        $msg = "We can't find a user with that email address";
    }
}
?>

<body>
<div class="container">  
    <div class="table-responsive">  
    <h3 align="center">Forgot Password</h3><br/>
    <div class="box">
     <form id="validate_form" method="post" >  
       <div class="form-group">
       <label for="email">Email Address</label>
       <input type="text" name="email" id="email" placeholder="Enter Email" required 
       data-parsley-type="email" data-parsley-trigg
       er="keyup" class="form-control" />
      </div>
      <div class="form-group">
       <input type="submit" id="login" name="pwdrst" value="Send Password Reset Link" class="btn btn-success" />
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
     </form>
     </div>
   </div>  
  </div>
</body>
</html>