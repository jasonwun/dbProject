<?php

require("db.inc.php");

$pdo = pdo_connect();

$userName = $_POST['user'];
$email = $_POST['email'];
$password =  $_POST['pass'];

$query = sprintf("INSERT INTO Users(username,email,password) VALUES ('%s','%s','%s')",$userName, $email, $password);


Function NewUser(){
  $pdo = pdo_connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $userName = $_POST['user'];
  $email = $_POST['email'];
  $password =  $_POST['pass'];
  //$query = sprintf("INSERT INTO Users(username,email,password) VALUES ('%s','%s','%s')",$userName, $email, $password);
  //$result = $pdo->query($query);
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password) 
                     VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $userName);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);
  $stmt->execute();    
  //if($result){
    	echo "YOUR REGISTRATION IS COMPLETED...";
      echo "<br>";
      echo "<a href='index.php'>Go Back</a>";
  //}
}
Function SignUp(){
    $pdo = pdo_connect();

    $userName = $_POST['user'];
    $email = $_POST['email'];

    $query1 = sprintf("SELECT * From users Where username= '%s'", $userName);
    $query2 = sprintf("SELECT * From users Where email= '%s'", $email);
    $result1 = $pdo->query($query1);
    $result2 =  $pdo->query($query2);
    if($result1->rowCount() == 0 and $result2->rowCount() == 0){
      NewUser();
    }
    else{
      echo "Already registered";
      echo "<br>";
      echo "<a href='index.php'>Go Back</a>";
    }

}

if(isset($_POST['submit']))
{
	   SignUp();
}


?>
