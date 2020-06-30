<?php

include("../../objects/users.php"); 
include("../../objects/cart.php"); 
include("../../config/database_handler.php");

$user_handler = new User($dbh);
$cart_handler = new Cart($dbh);

$token = $_GET['token'];
$productid = $_GET['productId'];


if($user_handler->validateToken($token) === false) {
    echo "Invalid token!";
    die;
    } 
    
    else {

    $return = $user_handler->viewToken($token);
    $token_id = $return['id'];
    }

if (isset($_GET['action']) && $_GET['action'] == "add") {

    echo $cart_handler->addProductToCart($token_id, $productid);
    echo "<br><a href='http://localhost:8080/api-ecom/v1/products/viewProducts.php?token=". $token ."'>Continue shopping</a>";
        
}   else {
    
    echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';
};