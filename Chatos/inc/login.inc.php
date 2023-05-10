<?php
session_start();
if(isset($_POST["submit"])) {
    
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(emptyInputLogin($username, $pwd)) {
        header("location: ../login.php?error=empty");
        exit();
    }
    $uid = loginUser($database, $username, $pwd);

    if($uid == false){
        header("location: ../login.php?error=login");
    }

    $_SESSION["username"] = $username;
    header("location: ../");
}
else {
    header("location: ../login.php");
    exit();
}