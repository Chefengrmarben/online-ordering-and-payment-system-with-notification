<!-- backend code (server side) -->
<?php
@include 'config.php';  // include (config.php) file for database connection
session_start();  // start a new or resume an existing session
$user_id = $_SESSION['user_id']; // get user id from the session variable no spacing!
if(!isset($user_id)){
    header('location:login.php'); //if user is not logged in redirect to login page
    exit();
}
?>
<!-- frontend code (client side) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>
    <!-- external stylesheet and font awesome library links for additional styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- html content for about page -->
    <div class="heading">
        <h3>About Us</h3>
        <p><a href="home.php">Home</a>/About</p>
    </div>
    <section class="about">
        <div class="row">
            <div class="box">
                <img src="images/avo_about_us_1.jpg" alt="">
                <h3>Why choose us?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eligendi in nobis at, labore ducimus reprehenderit odit excepturi, aliquam quia ipsam, voluptatum deleniti? Recusandae veritatis porro culpa velit dignissimos nulla dolores.</p>
                <a href="contact.php" class="readbtn">contact us</a>
            </div>
            <div class="box">
                <img src="images/avo_about_us_2.jpg" alt="">
                <h3>What we provide?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam voluptatem blanditiis in, dicta, cupiditate beatae quae voluptatibus voluptas facilis deleniti quam dignissimos minima? Quidem, laboriosam facere ipsum odio doloribus quia.</p>
                <a href="shop.php" class="proceed-btn">Our Shop</a>
            </div>
        </div>
    </section>
    <!-- section client reviews -->
    <section class="reviews">
        <h1 class="title">clients reviews</h1>
        <div class="box-container">
            <div class="box">
                <img src="images/avo_rating_1.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avocado ice cream</h3>
            </div>
            <div class="box">
                <img src="images/avo_rating_2.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avocado marble soap</h3>
            </div>
            <div class="box">
                <img src="images/avo_rating_3.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avocado chocolate marble soap</h3>
            </div>
            <div class="box">
                <img src="images/avo_rating_4.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avosmooth glow toner</h3>
            </div>
            <div class="box">
                <img src="images/avo_rating_5.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avopeel</h3>
            </div>
            <div class="box">
                <img src="images/avo_rating_6.jpg" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse reiciendis numquam iure labore, veritatis aut, illo provident deleniti quia hic dolores eveniet unde. Quaerat cum libero, consectetur iste esse nam!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Avomelt</h3>
            </div>
        </div>

        <section class="executives">
            <h1 class="title">Executives</h1>
            <div class="box-container">
                <div class="box">
                    <img src="images/executive.jpg" alt="" />
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa ratione praesentium beatae eveniet minima perspiciatis est mollitia doloremque quidem excepturi, ducimus vel reprehenderit omnis recusandae minus expedita?.</p>
                    <h3>The Executives</h3>
                </div>
                <div class="box">
                    <img src="images/ceo.jpg" alt="" />
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Expedita, placeat nobis quisquam facere sint ipsam at iusto. Consequatur, adipisci ullam? Dolores illum officia dolorum animi et a enim fugit veniam.</p>
                    <h3>CEO-Ms.Raquel Manimtim</h3>
                </div>
                <div class="box">
                    <img src="images/coo.jpg" alt="" />
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, voluptatem quis quam, odit molestiae consequuntur illo iure repudiandae expedita atque, obcaecati libero quibusdam nam eaque ducimus? Blanditiis totam ab modi!</p>
                    <h3>COO-Ms.Czarina Sevilla</h3>
                </div>
                <div class="box">
                    <img src="images/finance.jpg" alt="" />
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet ea facilis eius, odio, nesciunt, adipisci perspiciatis sequi consectetur optio quidem enim veniam sunt quasi cumque? Dicta assumenda corporis eos dolore!</p>
                    <h3>CFO-Ms.Jennifer M. Espiritu</h3>
                </div>
                <div class="box">
                    <img src="images/spotted.jpg" alt="" />
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet ea facilis eius, odio, nesciunt, adipisci perspiciatis sequi consectetur optio quidem enim veniam sunt quasi cumque? Dicta assumenda corporis eos dolore!</p>
                    <h3>AVO Skin Co- Celebrity Endorser</h3>
                </div>
                <div class="box">
                    <img src="images/executive_1.jpg" alt="" />
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet ea facilis eius, odio, nesciunt, adipisci perspiciatis sequi consectetur optio quidem enim veniam sunt quasi cumque? Dicta assumenda corporis eos dolore!</p>
                    <h3>Women Empowerment</h3>
                </div>
            </div>
    </section>

    </section>

    

    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
    
</body>
</html>