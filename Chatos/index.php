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
        <title>Chatos</title>
    </head>

    <body>

        <nav>
            <?php if (isset($_SESSION["username"])) echo "<a href='complaints.php'>!</a>"; ?>

            <div class=" navbar navbar-expand-lg">
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
                    } else {
                        echo "<li><a href='signup.php' class='link' >Sign Up</a></li>";
                        echo "<li><a href='login.php' class='link'>Log In</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <main>


            <div class="chat">
                <!--I think it would look good if the invitations were shown here--->
                <?php
                if (isset($_SESSION["username"])) {
                    $result = $database->getUser($_SESSION["username"]);
                    $user = new User($result["idu"], $result["nickname"], $result["authority"]);
                    $user->loadInvitations($database);
                    $user->displayInvitations();
                } else {
                    echo "You are not logged in";
                }
                ?>

            </div>
            <?php
            if (isset($_SESSION["username"])) {
                ?>
                <section id="chat_groups">


                    <div class=" navbar navbar-expand-lg navigation">
                        <div class="container-fluid">



                            <div class="offcanvas offcanvas-start" tabindex="-1"  id="offcanvasNavbar"
                                 aria-labelledby="offcanvasNavbarLabel">
                                <div class="offcanvas-body offcanvasgroups justify-content-center">
                                    <ul class="navbar-nav justify-content-start ">
                                        <div class=" burger" >

                                            <?php
                                            echo "
                            <form action='inc/sendInvitation.inc.php' method='post'>
                                <input type='text' name='uid' class='text' placeholder='Send chat request...'>
                                <input type='submit' name='submit' class='submit' value='Send'>
                            </form>
                                       ";
                                            require "inc/errors.inc.php";
                                            ?>

                                            <p style="text-align: center;">
                                                <?php
                                                echo "Hi " . $_SESSION["username"] . ".<br>";
                                                //print_r($result);
                                                displayChats($database, $user);
                                                ?>
                                            </p>
                                        </div>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php
                    if (isset($_GET["id"])) {
                        ?>
                        <div class="chat" style="flex-grow:1;">
                            <main class="chat">
                                <?php printMessages($database, $chatgroup); ?>
                            </main>
                            <form action="inc/sendMessage.inc.php" method="post" style="padding-top: 20px;">
                                <input type="text" name="message" placeholder="Aa" class="message">
                                <input type="submit" name="submit" value="Send" class="sendMessage">
                            </form>
                        </div>
                        <?php
                    } else {
                        echo 'Please select chatgroup';
                    }
                    ?>

                </section>
                <?php
            }
            ?>
        </main>
    </body>

</html>