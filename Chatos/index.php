<?php
    session_start();

    require_once "inc/dbh.inc.php";
    require_once "inc/functions.inc.php";
    require_once "inc/require_classes.inc.php";
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
   
    <nav>
         <?php if(isset($_SESSION["username"])) echo "<a href='complaints.php'>!</a>"; ?>
        <h1 id="logo" >CHATOS</h1>
        
        <div class="right">
            <ul>
                <?php
                    if(isset($_SESSION["username"])) {
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
       
             <div class="chat">
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
        
  
        <div class="chat">
        <p>
            <?php
                if(!isset($_SESSION["username"])) {
                    echo "You are not logged in.";
                }
                else {
                    echo "Hi " . $_SESSION["username"] . ".";
                    $result = $database->getUser($_SESSION["username"]);
                    print_r($result);
                    $user = new User($result["idu"], $result["nickname"], $result["authority"]);
                    displayChats($database, $user);
                }
            ?>
        </p>
        </div>
    </main>
</body>
</html>