<?php
session_start();
require_once "inc/classes/chatgroup.inc.php";
require_once "inc/classes/database.inc.php";
require_once "inc/classes/invitation.inc.php";
require_once "inc/classes/message.inc.php";
require_once "inc/classes/user.inc.php";
require_once "inc/classes/database.inc.php";
$database = new Database();

if (isset($_SESSION["username"])) {
    $result = $database->getUser($_SESSION["username"]);
    $user = new User($result["idu"], $result["nickname"], $result["authority"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <form method="post" action="#">
        <label for="nickname">Username: </label>
        <input type="text" name="nickname" id="nickname">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="command">Command: </label>
        <input type="text" name="command" id="command">
        <br>
        <label for="val1">Variable: </label>
        <input type="text" name="val1" id="val1">
        <br>
        <label for="val2">Variable: </label>
        <input type="number" name="val2" id="val2">
        <button type="submit">Odeslat</button>
    </form>
    <?php
        if(isset($_POST['command'])){
            $adminN = $_POST['nickname'];
            $adminP = $_POST['password'];
            $command = strtolower($_POST['command']);
            $val1 = $_POST['val1'];
            $val2 = $_POST['val2'];
            $results = array();
            switch ($command) {
                case 'getusermessages':
                    $results = $database->admin_getUserMessages($adminN, $adminP, $val1, $val2);
                    break;
                case 'deleteuserdescription':
                    try{
                        $database->admin_deleteUserDescription($adminN, $adminP, $val1);
                        $results = "Deleted successfully";
                    }
                    catch(Exception $e){
                        $results = "Delete Error: " . $e->getMessage();
                    }
                    break;
                case 'getchatgroupmessages':
                    $results = $database->admin_getChatGroupMessages($adminN, $adminP, $val1, $val2);
                    break;
                case 'deleteinvite':
                    try{
                        $database->admin_deleteInvite($adminN, $adminP, $val1);
                        $results = "Deleted successfully";
                    }
                    catch(Exception $e){
                        $results = "Delete Error: " . $e->getMessage();
                    }
                    break;
                case 'deletemessage':
                    try{
                        $database->admin_deleteUser($adminN, $adminP, $val1);
                        $results = "Deleted successfully";
                    }
                    catch(Exception $e){
                        $results = "Delete Error: " . $e->getMessage();
                    }
                case 'deleteuser':
                    try{
                        $database->admin_deleteUser($adminN, $adminP, $val1);
                        $results = "Deleted successfully";
                    }
                    catch(Exception $e){
                        $results = "Delete Error: " . $e->getMessage();
                    }
                    break;
                default:
                    $results = "Not a valid command";
                    break;
            }
            print_r($results);
        }
    ?>
</body>
</html>