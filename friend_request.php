<?php

include("db.inc.php");

$pdo = pdo_connect();
session_start();
$uid = $_SESSION['uid'];
$friendname=($_POST['friendName']);
$query = sprintf("SELECT uid From Users Where  username='%s'", $friendname);
$result1 = $pdo->query($query);
if($result1->rowCount() == 0){
  echo "User Does not Exist";
  echo "<br>";
  echo "<a href='init.php'>Go Back</a>";
}
else{
  $row = $result1->fetch();
  $friendid = $row['uid'];
  $query2 = sprintf("INSERT INTO `Friendship`(`user1`, `user2`, `Status`) VALUES ('$uid','$friendid', 'pending')");
  $query3 = sprintf("INSERT INTO `Friendship`(`user1`, `user2`, `Status`) VALUES ('$friendid','$uid', 'pending')");
  $result2 = $pdo->query($query2);
  $result3 = $pdo->query($query3);
    echo "Request Sended";
    echo "<br>";
    echo "<a href='init.php'>Go Back</a>";
}




/*
header("location:init.php");
*/
?>
