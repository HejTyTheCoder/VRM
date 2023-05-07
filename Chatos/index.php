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
    <title>Chatos</title>
</head>
<body>
    <div id="navDir">
        <?php if(isset($_SESSION["username"])) echo "<a href='complaints.php'>!</a>"; ?>
        <h1>CHATOS</h1>
    </div>
    <nav>
        <div class="left">
            <?php
                if(isset($_SESSION["username"])) {
                    echo "
                        <form action='inc/newChat.inc.php' method='post'>
                            <input type='text' name='uid' class='text' placeholder='Send chat request...'>
                            <input type='submit' name='submit' class='submit' value='Send'>
                        </form>
                    ";
                    require "inc/errors.inc.php";
                }  
            ?>           
        </div>
        <div class="right">
            <ul>
                <?php
                    if(isset($_SESSION["username"])) {
                        echo "<li><a href='profile.php'>Profile</a></li>";
                        echo "<li><a href='inc/logout.inc.php'>Log Out</a></li>";  
                    }
                    else {
                        echo "<li><a href='signup.php'>Sign Up</a></li>"; 
                        echo "<li><a href='login.php'>Log In</a></li>";                        
                    }
                ?>
            </ul>
        </div>
    </nav>
    <main>
        <p>
            <?php
                if(!isset($_SESSION["username"])) {
                    echo "You are not logged in.";
                }
                else {
                    echo "Hi " . $_SESSION["username"] . ".";
                    displayChats(1, $database);
                }
            ?>
        </p>
    </main>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
</html>