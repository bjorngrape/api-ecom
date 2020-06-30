
<?php 

include("../../objects/users.php"); 
include("../../objects/products.php"); 
include("../../config/database_handler.php");

$user_handler = new User($dbh);
$product_handler = new Product($dbh);

$title = (isset($_POST['title']) ? $_POST['title'] : "");
$description = (isset($_POST['description']) ? $_POST['description'] : "");
$price = (isset($_POST['price']) ? $_POST['price'] : "");
$quantity = (isset($_POST['quantity']) ? $_POST['quantity'] : "");

echo $product_handler->addProduct($title, $description, $price, $quantity);

echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';