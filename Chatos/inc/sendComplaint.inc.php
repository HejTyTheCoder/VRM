<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(empty($_POST["complaint"])) {
        header("location: ../complaints.php?error=nomess");
        exit();
    }
    
    $database->newComplaint($_SESSION['idc'], $_SESSION['idu'], $_POST['complaint']);

    //header("location: ../complaints.php?error=stmt");
    header("location: ../complaints.php");
}
else {
    header("location: ../complaints.php");
}