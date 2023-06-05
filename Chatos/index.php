<?php
    require_once "phtml/header.phtml";

    if (isset($_POST["sendInvitation"])) {
        $senderId = $database->getUser($_SESSION["username"])["idu"];
        $acceptorId = $database->getUser($_POST["uid"])["idu"];
        $database->sendInvite($senderId, $acceptorId);
    }
    if (isset($_POST["sendMessage"])) {
        if(empty($_POST["message"])) {
            $errorMessage = "You did not write any message.";
        }
        try{
            $database->sendMessage($_SESSION['idu'], $_SESSION['idc'], $_POST['message']);
        }
        catch(Exception $e){
            $errorMessage = "Unexpected error.";
        }
    }
    if (isset($_GET["inviteSubmit"])) {
        if ($_GET["inviteSubmit"] == "Accept") {
            $idi = $_GET["idi"];
            $database->acceptChat($idi);
        }
        else {
            $idi = $_GET["idi"];
            $database->declineChat($idi);
            // $database->declineChat($idi);    no function for this yet //
        }
    }

    require_once "phtml/index.phtml";
    require_once "phtml/footer.phtml";
?>