<?php
    require_once "phtml/header.phtml";

    if (isset($_POST["sendMessage"])) {
        try{
            $database->sendMessage($_SESSION['idu'], $_SESSION['idc'], $_POST['message']);
        }
        catch(Exception $e){
            $_SESSION["errorMessage"] = "Unexpected error.";
        }
        $_SESSION["newMessage"] = 1;
        reload();
    }
    else if (isset($_GET["inviteSubmit"])) {
        if ($_GET["inviteSubmit"] == "Accept") {
            $idi = $_GET["idi"];
            if($user->hasInvitation($database, $idi)){ 
                $database->acceptChat($idi);
            }
            else{
                throw new Exception("You are not allowed to accept this invitation");
            }
        }
        else {
            $idi = $_GET["idi"];
            $database->declineChat($idi);
        }
        reload();
    }
    else if (isset($_POST["sendChatInvitation"])) {
        if($acceptorId = $database->getUser($_POST["uid"])["idu"]) {
            $senderId = $database->getUser($_SESSION["username"])["idu"];
            $database->sendInvite($senderId, $acceptorId, null, $_POST["message"]);
        }
        else {
            $_SESSION["errorMessage"] = "This user does not exist.";
        }
        reload();
    }
    else if (isset($_POST["sendGroupInvitation"])) {
        if($acceptorId = $database->getUser($_POST["uid"])["idu"]) {
            if(!$database->isUserInGroup($acceptorId, $_GET["id"])) {
                $senderId = $database->getUser($_SESSION["username"])["idu"];
                $database->sendInvite($senderId, $acceptorId, $_GET["id"], $_POST["message"]);
            }
            else {
                $_SESSION["errorMessage"] = "This user is already in.";
            }
        }
        else {
            $_SESSION["errorMessage"] = "This user does not exist.";
        }
        reload();
    }
    else if (isset($_POST["createGroup"])) {
        $database->createChatGroup($_POST["name"], $_SESSION["idu"]);
        reload();
    }
    function reload() {
        if(isset($_SESSION['idc'])) {
            header("location: index.php?id=" . $_SESSION['idc']);
        }
        else {
            header("location: .");
        }
    }

    require_once "phtml/header.php";
    require_once "phtml/index.phtml";
    require_once "phtml/footer.phtml";
?>