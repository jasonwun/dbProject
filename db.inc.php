<?php

function pdo_connect(){
  try{
    $dbhost = "mysql:host=localhost;dbname=dbProject";
    $user = "root";
    $password = "";
    return new PDO($dbhost, $user, $password);
  }
  catch(PDOException $e){
    die("Unable to select database");
  }
}

?>
