<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";

    if(empty($_POST["message"])) {
        header("location: ../chat.php?id=" . $_SESSION["idc"] . " error=nomess");
        exit();
    }
    try{
        $database->addMessage($_SESSION['idc'], $_SESSION['idu'], $_POST['message']);
    }
    catch(Exception $e){
        header("location: ../chat.php?error=stmt");
    }

    header("location: ../chat.php?id=" . $_SESSION["idc"]);
    exit();
}
else {
    header("location: ../chat.php?id=" . $_SESSION["idc"]);
    exit();
}