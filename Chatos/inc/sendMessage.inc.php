<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(empty($_POST["message"])) {
        header("location: ../chat.php?id=" . $_SESSION["idc"] . " error=nomess");
        exit();
    }
    
    $sql = "INSERT INTO messages (chat, user, message, edited) VALUES (?, ?, ?, 0);";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../chat.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "iis", $_SESSION["idc"], $_SESSION["idu"], $_POST["message"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../chat.php?id=" . $_SESSION["idc"]);
    exit();
}
else {
    header("location: ../chat.php?id=" . $_SESSION["idc"]);
    exit();
}