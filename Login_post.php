<?php
include("db.inc.php");


$pdo = pdo_connect();
session_start();
$username=($_POST['username']);
$password=($_POST['password']);

$query = sprintf("SELECT * FROM Users WHERE username='$username' and password='$password'");
$result=$pdo->query($query);




if($result->rowCount() != 0){
  $row = $result->fetch();
  $_SESSION['uid']= $row['uid'];
  $_SESSION['username']= $row['username'];
  $_SESSION['email']= $row['email'];
  $_SESSION['password']= $row['password'];
  $_SESSION['ulat'] =  $row['ulatt'];
  $_SESSION['ulng']= $row['ulong'];
  $_SESSION['utime']= $row['utime'];
  $_SESSION['ustate']= $row['ustate'];




  header("location:init.php");
} else {
  echo 'Wrong Username or Password! Return to <a href="index.php">login</a>';
  }
?>
