<?php
require("db.inc.php");

$pdo = pdo_connect();
session_start();
$uid = $_SESSION["uid"];

$dom = new DOMDocument("1.0");
$node = $dom->createElement("friend");
$parnode = $dom->appendChild($node);

$query = sprintf("
select * from friendship join users on friendship.user2 = uid where user1 = %d
union 
select * from friendship join users on friendship.user2 = uid where user2 = %d and status = 0
", $uid, $uid);
$result = $pdo->query($query);

if($result->rowcount() == 0){
    exit;
}
header("Content-type: text/xml");
while($row = $result->fetch()){
    $node = $dom->createElement("friend");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("id", $row["uid"]);
    $newnode->setAttribute("name", $row["username"]);
    $newnode->setAttribute("status", $row["status"]);
}

echo $dom->saveXML();
?>