<?php

include("../../objects/users.php"); 
include("../../objects/products.php"); 
include("../../config/database_handler.php");

$user_handler = new User($dbh);
$product_handler = new Product($dbh);
$token = $_GET['token'];

if($user_handler->validateToken($token) === false) {
    echo "Invalid token!";
    die;
} 

if(isset($_GET['action']) && $_GET['action'] == "delete") {

    $productId = $_GET['productId'];
    echo $product_handler->deleteProduct($bookId);
} 

if ($user_handler->viewToken($token) ['user_id'] && $user_handler->viewToken($token) ['user_id'] == 1) {

    include("../includes/addProduct.php");
}

if(isset($_GET['action']) && $_GET['action'] == "search") {

    $searchQ = $_POST['searchQ'];

    if ($searchQ=="") {
        echo "<h1>All products</h1>";

        $fetchResult = $product_handler->fetchAll();

        if ($fetchResult->state=="success") {

            foreach ($fetchResult->result as $product) {
            
                echo "<hr />";
                echo "<b> Title: </b>" . $product['title'];
                echo "<br />";
                echo "<b> Description: </b>" . $product['description'] ."<br />";
                echo "<br />";
                echo "<b> Price: </b>" . $product['price'] ."<br />";
                echo "<br />"; 
                
        
                if ($user_handler->viewToken($token) ['user_id'] && $user_handler->viewToken($token) ['user_id'] == 1) {
                echo "<b> Quantity: </b>" . $product['quantity'] ."<br />";
                echo "<a href='http://localhost:8080/api-ecom/v1/products/editProduct.php?token=". $token ."&action=edit&productId=" . $product['id'] . "'> Edit </a>";
                echo "<a href='http://localhost:8080/api-ecom/v1/products/viewProducts.php?token=". $token ."&action=delete&productId=" . $product['id'] . "'> Delete </a>";
                } else {
        
                    echo "<a href='http://localhost:8080/api-ecom/v1/cart/addToCart.php?token=". $token ."&action=add&productId=" . $product['id'] . "'>Add to Cart </a>";
                }
            }
        }
    }

    else {
        $product_handler->searchProduct($searchQ);

        echo "<h2>Search result</h2>";
    
        foreach ($product_handler->fetchProduct() as $product) {
        
            echo "<hr />";
            echo "<b> Title: </b>" . $product['title'];
            echo "<br />";
            echo "<b> Description: </b>" . $product['description'] ."<br />";
            echo "<br />";
            echo "<b> Price: </b>" . $product['price'] ."<br />";
            echo "<br />";
            echo "<a href='http://localhost:8080/api-ecom/v1/cart/addToCart.php?token=". $token ."&action=add&productId=" . $product['id'] . "'>Add to Cart </a>";
        }
    
    }

} 

include("../includes/search.php");

?> 