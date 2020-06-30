<h1>Register</h1>

<?php

include("../../objects/users.php"); 
include("../../config/database_handler.php");

$username = $_POST["username"];
$password = md5($_POST["password"]);

$users_object = new User($dbh);

if (!empty ($_POST["username"])) {

    if (!empty ($_POST["password"])) {
            echo $users_object->signUp($username, $password);
    } 
} 
    else {
        echo "Fill in username and password pls";
        echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';
    };