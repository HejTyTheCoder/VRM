<?php

session_start();

if(isset($_POST["accept"]) || isset($_POST["decline"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(isset($_POST["accept"])) {
        $database->acceptChat($_POST["IDc"]);
    }
    else if(isset($_POST["decline"])) {
        $database->deleteChat($_POST["IDc"]);
    }

    header("location: ../profile.php");
    exit();
}
else {
    header("location: ../profile.php");
    exit();
}