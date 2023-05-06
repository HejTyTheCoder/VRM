<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(uidExists($connection, $_POST["uid"])) {

        $uid = uidExists($connection, $_POST["uid"]);
        $applicant = $_SESSION["idu"];
        $acceptor = $uid["IDu"];

        if(!chatExists($connection, $applicant, $acceptor)) {
            createChat($connection, $applicant, $acceptor);
        }
        else {
            header("location: ../index.php?error=chate");
            exit();
        }
    }
    else {
        header("location: ../index.php?error=chatu");
        exit();
    }
}
else {
    header("location: ../index.php");
    exit();
}