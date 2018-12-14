<?php
require("db.inc.php");

$pdo = pdo_connect();
session_start();
$uid = $_SESSION["uid"];
$time = $_POST["usercurrenttime"];
$query = sprintf("update users set utime = '%s' where uid = '%d'", $time, $uid);
$pdo->query($query);

//header("location:init.php");
?>
