<?php
@include 'config.php';
session_start();
$rows = [];
$message = [];

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $hashedPassword = md5($pass);  

    $sql = "SELECT * FROM `users` WHERE email=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $hashedPassword]);
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        if ($rows[0]['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $rows[0]['id'];
            header('Location: admin_page.php');
            exit();
        } elseif ($rows[0]['user_type'] == 'user') {
            $_SESSION['user_id'] = $rows[0]['id'];
            header('Location: home.php');
            exit();
        } else {
            $message[] = 'No user found!';
        }
    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/components.css">
</head>
<body>
    <?php
    if(isset($message)){
        foreach($message as $message){
            echo'
            <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>

    <section class="log-container">
        <form action="" method="POST">
            <h3>Login Now</h3>
            <input type="email" name="email" class="box" placeholder="Enter Your Email" required>
            <input type="password" name="pass" class="box" placeholder="Enter Your Password" required>
            <input type="submit" value="login now" class="btn" name="submit">
            
            <p>Did you forget password? <a href="forgot.php">Forgot Password</a></p>
            <p>Don't have an Account? <a href="register.php">Register Now</a></p>
        </form>
    </section>

    <script script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.ripples/0.5.3/jquery.ripples.min.js"></script>

    <script>
		$('section').ripples({
			resolution: 512,
			dropRadius: 20, //px
			perturbance: 0.04,
		});
    </script>
</body>
</html>