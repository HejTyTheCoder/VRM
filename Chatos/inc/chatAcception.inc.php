<?php

session_start();

if(isset($_POST["accept"]) || isset($_POST["decline"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(isset($_POST["accept"])) {
        $sql = "UPDATE chats set accepted = 1 WHERE IDc = ?;";
        $stmt = mysqli_stmt_init($connection);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../profile.php?error=stmt");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $_POST["IDc"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    else if(isset($_POST["decline"])) {
        deleteChat($connection, $_POST["IDc"]);
    }

    header("location: ../profile.php");
    exit();
}
else {
    header("location: ../profile.php");
    exit();
}