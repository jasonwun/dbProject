<!DOCTYPE html >
<?php
require("db.inc.php");
$pdo = pdo_connect();
session_start();
$uid = $_SESSION["uid"];
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Filter Detail</title>
  </head>
  <body>
    <h1>Add New Note</h1>  
    <br>
    <form action="AddNotePost.php" method="post" >
      Tag: <br>
          <?php
                $tagQuery = "select * from tag";
                $result = $pdo->query($tagQuery);
                while($row = $result->fetch()){
                    echo "<input type='checkbox' name=tag" . $row["tid"] . ">" ;
                    echo $row["tagname"]."<br>";
                    //echo "<option value=" . $row["tid"] . ">" . $row["tagname"] . "</option>";
                }
          ?>
      <br>
         Content:<br> 
        <input type="text" style=" width: 342px;"  name="ncontent">
      <br>
         Place: 
      <select id="placeSelect" style="width: 150px; margin-top: 10px" name="place_id">
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
      Radius: <input type="text" style="margin-top: 10px"  name="nradius"/>
      <br>
      Date: <input type="date" style="margin-top: 10px" name="ndate"/>
      <br>
      Starttime: <input type="time" style="margin-top: 10px"  name="nstarttime"/>
      <br>
      EndTime: <input type="time" style="margin-top:10px"  name="nendtime"/>
      <br>
      <input type="checkbox" name="mon"> Mon <input type="checkbox" name="tue">Tue
        <input type="checkbox" name="wed">Wed<input type="checkbox" name="thu">Thu
        <input type="checkbox" name="fri">Tue<input type="checkbox" name="sat">Sat
        <input type="checkbox" name="sun">Sun<br>
      Visibility: <select Style="width:100px; margin-top:10px" name="nvisibility">
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
        
      <input type="hidden"  name="uid" value="<?php echo $uid; ?>"/>
      <br>
      <input type="submit" name="add_note" value="Add New Note"/>
        
      </form>    
  
  </body>
</html>