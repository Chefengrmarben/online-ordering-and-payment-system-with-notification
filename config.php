<?php
$db_name = "mysql:host=localhost;dbname=grocery_db";
$username = "root";
$password ="";

try{
    $conn = new PDO($db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set('Asia/Manila');
}catch (PDOException $e) {
    die("Connection failed:" .$e->getMessage());
}
?>