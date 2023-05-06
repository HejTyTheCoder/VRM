<?php

if(isset($_POST["submit"])) {
    
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(emptyInputLogin($username, $pwd)) {
        header("location: ../login.php?error=empty");
        exit();
    }
    loginUser($connection, $username, $pwd);
}
else {
    header("location: ../login.php");
    exit();
}