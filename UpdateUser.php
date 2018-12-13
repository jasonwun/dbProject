<?php
require("db.inc.php");

$pdo = pdo_connect();
session_start();
$uid = $_SESSION["uid"];
$lat = $_GET["lat"];
$lng = $_GET["lng"];


$_SESSION["ulat"] = $lat;
$_SESSION["ulng"] = $lng;


$query = sprintf("update users set ulatt = %.7lf, ulong = %.7lf where uid = %d", $lat, $lng, $uid);
$pdo->query($query);

