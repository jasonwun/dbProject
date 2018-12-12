<?php

require("db.inc.php");

$pdo = pdo_connect();

$userName = $_POST['user'];
$email = $_POST['email'];
$password =  $_POST['pass'];

$query = sprintf("INSERT INTO Users(username,email,password) VALUES ('%s','%s','%s')",$userName, $email, $password);
Function NewUser(){
  $pdo = pdo_connect();

  $userName = $_POST['user'];
  $email = $_POST['email'];
  $password =  $_POST['pass'];
  $query = sprintf("INSERT INTO Users(username,email,password) VALUES ('%s','%s','%s')",$userName, $email, $password);
  $result = $pdo->query($query);
  if($result){
    	echo "YOUR REGISTRATION IS COMPLETED...";
      echo "<br>";
      echo "<a href='init.php'>Go Back</a>";
  }
}
Function SignUp(){
    $pdo = pdo_connect();

    $userName = $_POST['user'];
    $email = $_POST['email'];

    $query1 = sprintf("SELECT * From Users Where username= '%s'", $userName);
    $query2 = sprintf("SELECT * From Users Where email= '%s'", $email);
    $result1 = $pdo->query($query1);
    $result2 =  $pdo->query($query2);
    if($result1->rowCount() == 0 and $result2->rowCount() == 0){
      NewUser();
    }
    else{
      echo "Already registered";
      echo "<br>";
      echo "<a href='init.php'>Go Back</a>";
    }

}

if(isset($_POST['submit']))
{
	   SignUp();
}


?>
