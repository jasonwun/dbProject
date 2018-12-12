<?php
include("db.inc.php");


$pdo = pdo_connect();
session_start();
$username=($_POST['username']);
$password=($_POST['password']);

$query = sprintf("SELECT username FROM Users WHERE username='$username' and password='$password'");
$result=$pdo->query($query);



if($result->rowCount() != 0){
  $_SESSION['username']= $username;
  $_SESSION['password']= $password;
  header("location:init.php");
} else {
  echo 'Wrong Username or Password! Return to <a href="index.php">login</a>';
  }
?>
