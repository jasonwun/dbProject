<?php
require("db.inc.php");

$username = $_GET["username"];
$password = $_GET["password"];


$pdo = pdo_connect();

$query = sprintf("select * from Users where username = '%s' and password = '%s'",
$username, $password);

$result = $pdo->query($query);

if($result->rowCount() == 0){
    header("HTTP/1.0 404 Not Found");
    exit();
}

$dom = new DOMDocument("1.0");
$node = $dom->createElement("users");
$parnode = $dom->appendChild($node);

header("Content-type: text/xml");
while ($row = $result->fetch()){
    $node = $dom->createElement("users");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("id", $row['uid']);
    $newnode->setAttribute("username", $row['username']);
    $newnode->setAttribute("lat", $row['ulatt']);
    $newnode->setAttribute("lng", $row['ulong']);
    $newnode->setAttribute("time", $row['utime']);
    $newnode->setAttribute("state", $row['ustate']);
  }
echo $dom->saveXML();

?>