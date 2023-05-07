<?php
include_once("dbh.inc.php");

if(isset($_POST["submit"])) {
    $username = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwd2 = $_POST["pwd2"];

    require_once "dbh.inc.php";
    require_once "functions.inc.php";
   
    if(emptyInputSingup($username, $pwd, $pwd2)) {
        header("location: ../signup.php?error=empty");
        exit();
    }
    if(invalidUid($username)) {
        header("location: ../signup.php?error=uidi");
        exit();
    }
    if($database->uidExists($username)) {
        header("location: ../signup.php?error=uide");
        exit();
    }
    if(invalidEmail($email)) {
        header("location: ../signup.php?error=emaili");
        exit();
    }
    if($database->emailExists($email)) {
        header("location: ../signup.php?error=emaile");
        exit();
    }
    if(pwdMatch($pwd, $pwd2)) {
        header("location: ../signup.php?error=pwd");
        exit();
    }

    $database->createUser($connection, $username, $email, $pwd);
}
else {
    header("location: ../signup.php");
    exit();
}