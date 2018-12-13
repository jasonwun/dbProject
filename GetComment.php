<?php
require("db.inc.php");

$pdo = pdo_connect();

$nid = $_GET["nid"];

$dom = new DOMDocument("1.0");
$node = $dom->createElement("comment");
$parnode = $dom->appendChild($node);

$query = sprintf("select username, content, createtime from comment natural join users where nid = %d", $nid);

$result = $pdo->query($query);

header("Content-type: text/xml");
while ($row = $result->fetch()){
    $node = $dom->createElement("comment");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("username", $row['username']);
    $newnode->setAttribute("content", $row["content"]);
    $newnode->setAttribute("createtime", $row["createtime"]);
  }
  echo $dom->saveXML();