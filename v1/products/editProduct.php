<?php

include("../../objects/users.php"); 
include("../../objects/products.php"); 
include("../../config/database_handler.php");

$user_handler = new User($dbh);
$product_handler = new Product($dbh);

$token = $_GET['token'];
$productid = $_GET['productId'];

if($user_handler->validateToken($token) === false) {
    echo "Invalid token!";
    die;
} 

if (isset($_GET['action']) && $_GET['action'] == "update") {

    $title = (!empty($_POST['product_title'])) ? $_POST['product_title'] : "";
    $description = (!empty($_POST['product_description'])) ? $_POST['product_description'] : "";
    $price = (!empty($_POST['price'])) ? $_POST['price'] : "";
    $quantity = (!empty($_POST['quantity'])) ? $_POST['quantity'] : "";
        
    echo $product_handler->editProduct($title, $description, $price, $quantity, $productid);

    echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';    
} 

if (isset($_GET['action']) && $_GET['action'] == "edit") {

    echo $product_handler->getEditProduct($productid);

    foreach ($product_handler->fetchEditProduct() as $product) {

        include("../includes/editProduct.php");
    }
}