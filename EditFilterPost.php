<?php
    require("db.inc.php");
    $pdo = pdo_connect();
    $fid = ($_POST['fid']);
    $tid = ($_POST["ftag"]);
    $fstate = ($_POST["fstate"]);
    $place_id = ($_POST["place_id"]);
    $radius = ($_POST["fradius"]);
    $fdate = ($_POST["fdate"]);
    $fstarttime = ($_POST["fstarttime"]);
    $fendtime = ($_POST["fendtime"]);
    $visibility = ($_POST["fvisibility"]);
    $name = ($_POST["fname"]);


    $query = sprintf("UPDATE filter 
            SET 
            ftag=%d,
            fstate='%s',
            place_id='%s',
            fradius=%d,
            fdate='%s',
            fstarttime='%s',
            fendtime='%s',
            fvisibility='%s',
            fname='%s' WHERE fid = %d", $tid, $fstate, $place_id, $radius, $fdate, $fstarttime, $fendtime, $visibility, $name, $fid);

    $result - $pdo->query($query);

    header("location:init.php");














?>