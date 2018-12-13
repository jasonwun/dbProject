<?php
require("db.inc.php");

$pdo = pdo_connect();
session_start();
$uid = $_SESSION["uid"];
$friendid = $_GET["id"];
$action = $_GET["action"];

if($action == 0){ //Approve request
    $query = sprintf("update friendship set status = 1 where user1 = %d and user2 = %d", $friendid, $uid);
    $pdo->query($query);
    $query = sprintf("insert into friendship values (%d, %d, 1)", $uid, $friendid);
    $pdo->query($query);
}
else{//Deny request, drop the current relationship
    $query = sprintf("delete from friendship where user1 = %d and user2 = %d", $friendid, $uid);
    $pdo->query($query);
}

header("location:init.php");

?>

