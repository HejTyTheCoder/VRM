<?php
    require_once "header.php";
?>
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
                            <form action='inc/sendInvitation.inc.php' method='post'>
                                <input type='text' name='uid' class='text' placeholder='Send chat request...'>
                                <input type='submit' name='submit' class='submit' value='Send'>
                            </form>
                            <?php
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
        <div class="chat">
            <?php 
                printMessages($database, $chatgroup); 
            ?>
        </div>
        <form action="inc/sendMessage.inc.php" method="post" style="padding-top: 20px;">
            <input type="text" name="message" placeholder="Aa" class="message">
            <input type="submit" name="submit" value="Send" class="sendMessage">
        </form>
    </div>
    <?php
        } 
        else {
            echo 'Please select chatgroup';
        }
    ?>
</section>
<?php
    }
    require_once "footer.php";
?>