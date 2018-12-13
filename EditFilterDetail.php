<!DOCTYPE html >
<?php
require("db.inc.php");
$pdo = pdo_connect();

$query = "select * from ";
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Filter Detail</title>
  </head>
  <body>
    <label>Edit this Filter</label>
    <form action="EditFilterPost" method="post" >
      Tag: <select id="tagSelect" style="width: auto;">
          <?php
                $tagQuery = "select * from tag";
                $result = $pdo->query($tagQuery);
                while($row = $result->fetch()){
                  echo "<option value=" . $row["tid"] . ">" . $row["tagname"] . "</option>";
                }
          ?>
      </select>
      <br>
      State: <input type="text" id="FilterState" style="margin-top: 10px"/>
      <br>
      Place: <select id="placeSelect" style="width: 150px; margin-top: 10px"></select>
      <br>
      Radius: <input type="text" id="FilterRadius" style="margin-top: 10px"/>
      <br>
      Date: <input type="date" id="FilterDate" style="margin-top: 10px"/>
      <br>
      Starttime: <input type="range" id="FilterStartTime" style="margin-top: 10px"/>
      <br>
      EndTime: <input type="range" id="FilterEndTime" style="margin-top:10px"/>
      <br>
      Visibility: <select id="visibilitySelect" Style="width:100px; margin-top:10px">
          <option value="everyone">everyone</option>
          <option value="friend">friend</option>
          <option value="private">private</option>
      </select>
      <br>
      Name: <input type="text" id="FilterName" style="margin-top:10px"/>
      <br>
      <input type="submit" name="update" value="Update"/>
      <br>
    </form>


  </body>
</html>
