<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Log In</title>
</head>
<body>
    <?php require_once "micro/navClassic.php"; ?>
    <main>
        <h2>Log In</h2>
        <form action="inc/login.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username/Email...">
            <br>
            <input type="password" name="pwd" placeholder="Password...">
            <br>
            <?php require "inc/errors.inc.php"; ?>
            <br>
            <input type="submit" name="submit" value="Log In">
        </form>
    </main>
</body>
</html>