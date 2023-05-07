<?php
    session_start();

    require_once "inc/dbh.inc.php";
    require_once "inc/functions.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Profile</title>
</head>
<body>
    <?php require_once "micro/navClassic.php"; ?>
    <main>
        <h2><?php echo $_SESSION["username"] ?></h2>
        <?php
            displayChats(0, $database);
        ?>
    </main>
</body>
</html>