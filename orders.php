<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
    exit();
}
/* for order cancelation backend code hindi pa nagana ng ayos */
$uid = 1; // user ID
// SQL query with a prepared statement
$sql = "SELECT * FROM orders WHERE id = :id";
try {
    // Check if $pdo is set before proceeding
    if (isset($pdo)) {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Fetch the result as an associative array
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if the query returned a result
        if ($r) {
            // Process the result as needed
            print_r($r);
        } else {
            // Handle no result
            echo "No result";
        }
    } else {
        // Handle case where $pdo is not set
        echo "PDO connection not established";
    }
} catch (PDOException $e) {
    // Handle PDO exception
    echo "Error: " . $e->getMessage();
}
// Close the PDO connection if necessary
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <!-- for cancel order button -->
    <style>
    button.cancel-order {
        background: linear-gradient(to right, #ff5050, #ff3333); /* Gradient from light red to dark red */
        color: #fff; /* White text color */
        padding: 10px 15px; /* Adjust padding as needed */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button.cancel-order:hover {
        background: linear-gradient(to right, #ff3333, #ff1a1a); /* Darken the gradient on hover */
    }
</style>

</head>
<body>
    <?php include 'header.php';?>
    <div class="headingcatr">
        <h3>ORDERS</h3>
        <p><a href="home.php">Home</a>/ORDERS</p>
    </div>

    <section class="placed-orders">
        <h1 class="title">placed orders</h1>
        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id=?");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
                while ($order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="box">
                        <p>placed on: <span><?= $order['placed_on']; ?></span></p>
                        <p>name: <span><?= $order['name']; ?></span></p>
                        <p>number: <span><?= $order['number']; ?></span></p>
                        <p>email: <span><?= $order['email']; ?></span></p>
                        <p>address: <span><?= $order['address']; ?></span></p>
                        <p>payment method: <span><?= $order['method']; ?></span></p>
                        <p>your orders: <span><?= $order['total_products']; ?></span></p>
                        <p>total price: <span><?= $order['total_price']; ?></span></p>
                        <p>payment status: <span style="color:<?php if ($order['payment_status'] == 'pending') {
                                echo 'red';
                            } else {
                                echo 'green';
                            } ?>"><?=$order['payment_status'];?></span></p>
                        <!-- for order cancelation button -->
                        <?php if ($order['payment_status'] == 'pending' && isset($order['id'])): ?>
                            <button class="cancel-order" onclick="cancelOrder(<?= $order['id']; ?>)">Cancel Order</button>
                        <?php endif; ?>

                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php';?>
    <script src="js/script.js"></script>
    <script>
        function cancelOrder(orderId) {
            console.log('Cancel Order clicked for Order ID:', orderId);
            
            if (confirm("Are you sure you want to cancel this order?")) {
                console.log('Confirmation received. Initiating fetch request...');
                
                fetch('/cancel-order-endpoint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        orderId: orderId,
                    }),
                })
                .then(response => {
                    console.log('Inside fetch .then block');
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    
                    if (data.success) {
                        alert("Order with ID " + orderId + " has been canceled!");
                        // update the UI here to reflect the canceled status
                    } else {
                        alert("Failed to cancel the order. Please try again.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred. Please try again later.");
                });
            } else {
                alert("Order cancellation canceled!");
            }
        }

    </script>
</body>
</html>
