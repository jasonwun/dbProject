<?php
require("db.inc.php");


$uid = $_GET["uid"];

$dom = new DOMDocument("1.0");

$node = $dom->createElement("Note");
$parnode = $dom->appendChild($node);

$pdo->pdo_connect();

$query = sprintf("create temporary table ");

?>