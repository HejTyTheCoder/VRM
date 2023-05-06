<?php

session_start();

if(isset($_POST["submit"])) {

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if(empty($_POST["complaint"])) {
        header("location: ../complaints.php?error=nomess");
        exit();
    }
    
    $sql = "INSERT INTO messages (chat, user, message, edited) VALUES (?, ?, ?, 0);";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../complaints.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "iis", $_SESSION["idc"], $_SESSION["idu"], $_POST["complaint"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../complaints.php");
    exit();
}
else {
    header("location: ../complaints.php");
    exit();
}