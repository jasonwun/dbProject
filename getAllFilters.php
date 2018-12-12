<?php
require("db.inc.php");

$uid = $_GET["uid"];

$pdo = pdo_connect();

$dom = new DOMDocument("1.0");

$node = $dom->createElement("filter");
$parnode = $dom->appendChild($node);

$query = sprintf("select * from filter where fuid = '%s'",
$uid);

$result = $pdo->query($query);


header("Content-type: text/xml");
// Iterate through the rows, adding XML nodes for each
while ($row = $result->fetch()){
  $node = $dom->createElement("filter");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("filterid", $row['fid']);
  $newnode->setAttribute("filterdesc", $row["fname"]);
}
echo $dom->saveXML();

?>