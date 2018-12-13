<!DOCTYPE html >
<?php
require("db.inc.php");
$pdo = pdo_connect();
$uid = $_GET["uid"];
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Filter Detail</title>
  </head>
  <body>
    <label>Edit this Filter</label>
    <form action="AddFilterPost.php" method="post" >
      Tag: <select id="tagSelect" style="width: auto;" name="ftag">
          <?php
                $tagQuery = "select * from tag";
                $result = $pdo->query($tagQuery);
                while($row = $result->fetch()){
                  if($row["tid"] == $tid){
                    echo "<option value=" . $row["tid"] . " >" . $row["tagname"] . "</option>";
                  }else{
                    echo "<option value=" . $row["tid"] . ">" . $row["tagname"] . "</option>";
                  }
                }
          ?>
      </select>
      <br>
      State: <input type="text" id="FilterState" style="margin-top: 10px"  name="fstate">
      <br>
      Place: <select id="placeSelect" style="width: 150px; margin-top: 10px" name="place_id">
                <?php
                      $placesQuery = "select * from location";
                      $result = $pdo->query($placesQuery);
                      while($row = $result->fetch()){
                        if($row["place_id"] ==  $place_id){
                          echo "<option value=" . $row["place_id"] . " >" . $row["place_name"] . "</option>";
                        }else{
                          echo "<option value=" . $row["place_id"] . ">" .  $row["place_name"] . "</option>";
                        }
                      }
                ?>
      </select>
      <br>
      Radius: <input type="text" id="FilterRadius" style="margin-top: 10px"  name="fradius"/>
      <br>
      Date: <input type="date" id="FilterDate" style="margin-top: 10px" name="fdate"/>
      <br>
      Starttime: <input type="time" id="FilterStartTime" style="margin-top: 10px"  name="fstarttime"/>
      <br>
      EndTime: <input type="time" id="FilterEndTime" style="margin-top:10px"  name="fendtime"/>
      <br>
      Visibility: <select id="visibilitySelect" Style="width:100px; margin-top:10px" name="fvisibility">
                <?php
                    if($visibility == "everyone"){
                      echo "<option value=\"everyone\" >everyone</option>";
                      echo "<option value=\"friend\">friend</option>";
                      echo "<option value=\"private\">private</option>";
                    }
                    else if($visibility == "friend"){
                      echo "<option value=\"everyone\" >everyone</option>";
                      echo "<option value=\"friend\">friend</option>";
                      echo "<option value=\"private\">private</option>";
                    }
                    else{
                      echo "<option value=\"everyone\" >everyone</option>";
                      echo "<option value=\"friend\">friend</option>";
                      echo "<option value=\"private\" >private</option>";
                    }
                ?>
      </select>
      <br>
      Name: <input type="text" id="FilterName" style="margin-top:10px"  name="fname"/>
      <br>
      <input type="hidden"  name="uid" value="<?php echo $uid; ?>"/>
      <br>
      <input type="submit" name="update" value="Update"/>
      
    </form>


  </body>
</html>