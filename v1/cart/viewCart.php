<?php

include("../../objects/users.php"); 
include("../../objects/products.php");
include("../../objects/cart.php"); 
include("../../config/database_handler.php");

$user_handler = new User($dbh);
$product_handler = new Product($dbh);
$cart_handler = new Cart($dbh);

$token = $_GET['token'];

$return = $user_handler->viewToken($token);
$token_id = $return['id'];

if($user_handler->validateToken($token) === false) {
    echo "Invalid token!";
    die;
} 

if(isset($_GET['action']) && $_GET['action'] == "delete") {

    $cartid = $_GET['cartid'];
    echo $cart_handler->removeFromCart($cartid);
    
    echo "<a href='http://localhost:8080/api-ecom/v1/cart/viewCart.php?token=". $token ."'> View cart </a>";
} else {

    echo "<h1> Cart </h1>";

    $cart_handler->viewCart($token_id);

    foreach ($cart_handler->fetchCart() as $item) {
    
        echo "<hr />";
        echo "<b> Title: </b>" . $item['title'];
        echo "<br />";
        echo "<b> Price: </b>" . $item['price'] ."<br />";
        echo "<br />";
        echo "<a href='http://localhost:8080/api-ecom/v1/cart/viewCart.php?token=". $token ."&action=delete&cartid=" . $item['id'] . "'>Delete</a><br>";
        
    };

    if (!empty ($cart_handler->fetchCart() )) {

        echo "<a href='http://llocalhost:8080/api-ecom/v1/cart/checkout.php?token=". $token ."'> Checkout </a>";
    
    } else {
        echo "Empty cart";
        echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';
    }

}

?>