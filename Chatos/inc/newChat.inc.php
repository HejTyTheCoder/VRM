<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";

    if($database->uidExists($_POST["uid"])) {

        $uid = $database->uidExists($_POST["uid"]);
        $applicant = $_SESSION["idu"];
        $acceptor = $uid["IDu"];

        if(!$database->chatExists($applicant, $acceptor)) {
            $database->createChat($applicant, $acceptor);
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