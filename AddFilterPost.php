<?php
    require("db.inc.php");
    $pdo = pdo_connect();
    $uid = ($_POST["uid"]);
    $tid = ($_POST["ftag"]);
    $fstate = ($_POST["fstate"]);
    $place_id = ($_POST["place_id"]);
    $radius = ($_POST["fradius"]);
    $fdate = ($_POST["fdate"]);
    $fstarttime = ($_POST["fstarttime"]);
    $fendtime = ($_POST["fendtime"]);
    $visibility = ($_POST["fvisibility"]);
    $name = ($_POST["fname"]);


    $query = sprintf("INSERT INTO filter (fuid, ftag, fstate, place_id, fradius, fdate, fstarttime, fendtime, fvisibility, fname) VALUES
            (%d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s', '%s')", 
            $uid, $tid, $fstate, $place_id, $radius, $fdate, $fstarttime, $fendtime, $visibility, $name);

    $pdo->query($query);

    header("location:init.php");