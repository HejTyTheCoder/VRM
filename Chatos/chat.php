<?php
    session_start();

    require_once "inc/dbh.inc.php";
    require_once "inc/functions.inc.php";

    $_SESSION["idc"] = $_GET["id"];
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
    <main class="chat">
        <?php printMessages($connection); ?>
    </main>
    <div class="chat">
        <form action="inc/sendMessage.inc.php" method="post">
            <input type="text" name="message" placeholder="Aa" class="message">
            <input type="submit" name="submit" value=">" class="sendMessage">
        </form>
    </div>
</body>
</html>