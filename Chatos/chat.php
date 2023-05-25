<?php
session_start();

require_once "inc/dbh.inc.php";
require_once "inc/functions.inc.php";
require_once "inc/require_classes.inc.php";

$_SESSION["idc"] = $_GET["id"];
$chatgroup = new Chatgroup($_SESSION["idc"], "chatgroup");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Test User</title>
    </head>
    <body>
        <?php require_once "micro/navClassic.php"; ?>

        <div class="chat">
            <main class="chat">
                <?php printMessages($database, $chatgroup); ?>
            </main>
            <form action="inc/sendMessage.inc.php" method="post" style="padding-top: 20px;">
                <input type="text" name="message" placeholder="Aa" class="message">
                <input type="submit" name="submit" value="Send" class="sendMessage">
            </form>
        </div>
    </body>
</html>