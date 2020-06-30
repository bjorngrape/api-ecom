<h1>Log in</h1>

<?php

include("../../objects/users.php"); 
include("../../config/database_handler.php");

$users_object = new User($dbh);

$username = (!empty($_POST["username"])) ? $_POST["username"] : "";
$password = (!empty($_POST["password"])) ? md5($_POST['password']) : "";

print_r($users_object->LogIn($username, $password));

$token = $users_object->getToken($users_object->user_id);

echo '<br><a href="http://localhost:8080/api-ecom/index.php">Back</a>';

?>