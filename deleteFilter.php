<?php
    require("db.inc.php");
    $pdo = pdo_connect();
    $fid = $_GET["fid"];
    $query = sprintf("delete from filter where fid = %d", $fid);
    $pdo->query($query);


    header("location:init.php");
?>