<?php
session_start();

require_once "inc/dbh.inc.php";
require_once "inc/functions.inc.php";
require_once "inc/require_classes.inc.php";

if (isset($_GET["id"])) {
    $_SESSION["idc"] = $_GET["id"];
    $chatgroup = new Chatgroup($_SESSION["idc"], "chatgroup");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="css/general.css" rel="stylesheet" type="text/css"/>
    <script src="bootstrap-5.3.0/dist/js/bootstrap.bundle.js"></script>
    <title>CHATOS</title>
</head>
<body>
    <nav>
        <?php 
            if (isset($_SESSION["username"])) {
                echo "<a href='complaints.php'>!</a>";
            }
        ?>
        <div class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <h1 id="logo">CHATOS</h1>
        <div class="right">
            <ul class="navbutton">
                <?php
                if (isset($_SESSION["username"])) {
                    echo "<li><a href='profile.php' class='link'>Profile</a></li>";
                    echo "<li><a href='inc/logout.inc.php' class='link'>Log Out</a></li>";
                } 
                else {
                    echo "<li><a href='signup.php' class='link' >Sign Up</a></li>";
                    echo "<li><a href='login.php' class='link'>Log In</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
<main>