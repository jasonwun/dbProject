<?php
require("db.inc.php");

$pdo = pdo_connect();
session_start();
date_default_timezone_set("America/New_York");
$nid = ($_POST["nid"]);
$uid = $_SESSION["uid"];
$content = ($_POST["commentcontent"]);
$createtime = date("Y-m-d H:i:s");


$query = sprintf("insert into comment (uid, nid, content, createtime) values (%d, %d, '%s', '%s')", $uid, $nid, $content, $createtime);

$pdo->query($query);


header("location:init.php");

?>