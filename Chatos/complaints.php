<?php
    require_once "phtml/header.phtml";

    if(isset($_POST["submit"])) {
        if(empty($_POST["complaint"])) {
            $errorMessage = "You did not write any message.";
        }
        else {
            //$database->newComplaint($_SESSION['idc'], $_SESSION['idu'], $_POST['complaint']);
        }
    }

    require_once "phtml/complaints.phtml";
    require_once "phtml/footer.phtml";
?>