<?php

session_start();
require_once "require_classes.inc.php";
require_once "dbh.inc.php";
require_once "functions.inc.php";

$_SESSION["idu"] = $database->getUser($_SESSION["username"])["idu"];
if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";

    if(empty($_POST["message"])) {
        header("location: ../index.php?id=" . $_SESSION["idc"] . " error=nomess");
        exit();
    }
    try{
        $database->sendMessage($_SESSION['idu'], $_SESSION['idc'], $_POST['message']);
    }
    catch(Exception $e){
        header("location: ../index.php?error=stmt");
    }

    header("location: ../index.php?id=" . $_SESSION["idc"]);
    exit();
}
else {
    header("location: ../index.php?id=" . $_SESSION["idc"]);
    exit();
}