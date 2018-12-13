<?php 
require("db.inc.php");
$pdo = pdo_connect();
$uid = ($_POST["uid"]);
if(isset($_POST["ncontent"])){
    $ncontent = ($_POST["ncontent"]);
}
else{
    $ncontent = "";
}

$place_id = ($_POST["place_id"]);
$radius = ($_POST["nradius"]);
$ndate = ($_POST["ndate"]);
$nstarttime = ($_POST["nstarttime"]);
$nendtime = ($_POST["nendtime"]);
$visibility = ($_POST["nvisibility"]);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt1 = $pdo->prepare("INSERT INTO `schedule`(sdate, startime, endtime) 
                    VALUES (:sdate,:starttime,:endtime)");
$stmt1->bindParam(':sdate', $ndate);
$stmt1->bindParam(':starttime', $nstarttime);
$stmt1->bindParam(':endtime', $nendtime);
$stmt1->execute(); 
$last_schedule = $pdo->lastInsertId();


$stmt = $pdo->prepare("INSERT INTO `note` (nuid, place_id, nradius, schedule_id, nvisibility, ncontent) 
                     VALUES (:nuid, :place_id, :nradius, :schedule_id, :nvisibility, :ncontent)");   
$stmt->bindParam(':nuid', $uid);
$stmt->bindParam(':place_id', $place_id);
$stmt->bindParam(':nradius', $radius);
$stmt->bindParam(':schedule_id', $last_schedule);
$stmt->bindParam(':nvisibility', $visibility);
$stmt->bindParam(':ncontent', $ncontent);
$stmt->execute(); 
$last_note = $pdo->lastInsertId();


if(isset($_POST["tag1"])){
    $tid1 = 1;
    $stmt_tag1 = $pdo->prepare("INSERT INTO `notetag`(nid, tid)
                        VALUES (:nid,:tag)");
    $stmt_tag1->bindParam(':nid', $last_note);
    $stmt_tag1->bindParam(':tag', $tid1);
    $stmt_tag1->execute();
}
if(isset($_POST["tag2"])){
    $tid2 = 2;
    $stmt_tag2 = $pdo->prepare("INSERT INTO `notetag`(nid, tid)
                        VALUES (:nid,:tag)");
    $stmt_tag2->bindParam(':nid', $last_note);
    $stmt_tag2->bindParam(':tag', $tid2);
    $stmt_tag2->execute();
    
}
if(isset($_POST["tag3"])){
    $tid3 = 3;
    $stmt_tag3 = $pdo->prepare("INSERT INTO `notetag`(nid, tid)
                        VALUES (:nid,:tag)");
    $stmt_tag3->bindParam(':nid', $last_note);
    $stmt_tag3->bindParam(':tag', $tid3);
    $stmt_tag3->execute();
}
if(isset($_POST["tag4"])){
    $tid4 = 4;
    $stmt_tag4 = $pdo->prepare("INSERT INTO `notetag`(nid, tid)
                        VALUES (:nid,:tag)");
    $stmt_tag4->bindParam(':nid', $last_note);
    $stmt_tag4->bindParam(':tag', $tid4);
    $stmt_tag4->execute();
}
 echo "Note added";
      echo "<br>";
      echo "<a href='init.php'>Go Back</a>";

?>
