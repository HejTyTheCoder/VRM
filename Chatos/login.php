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
        <div class="log_sig">
            <h1 class="login-title">Log In</h1>
             
            <form action="inc/login.inc.php" method="post">
                <input type="text" class="login-input" name="uid" placeholder="Username...">
            <br>
            <input type="password" class="login-input" name="pwd" placeholder="Password...">
            <br>
            <?php require "inc/errors.inc.php"; ?>
            <br>
            <input type="submit" name="submit" class="login-button" value="Log In">
        </form>
             </div>
        
    </main>
</body>
</html>