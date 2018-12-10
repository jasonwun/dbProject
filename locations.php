<?php
require("db.inc.php");
// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);
// Opens a connection to a mySQL server
$pdo = pdo_connect();
// Search the rows in the markers table
$query = sprintf("SELECT place_id, place_name, address, lat, lng, 
( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM location HAVING 
distance < '%s' ORDER BY distance",
$center_lat,
$center_lng,
$center_lat,
$radius);

$queryresult = $pdo->query($query);
if($queryresult->rowCount() == 0){
  header("HTTP/1.0 404 Not Found");
  exit();
}
header("Content-type: text/xml");
// Iterate through the rows, adding XML nodes for each
while ($row = $queryresult->fetch()){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row['place_id']);
  $newnode->setAttribute("name", $row['place_name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
}
echo $dom->saveXML();
?>