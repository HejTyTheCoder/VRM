<?php
    require_once "phtml/header.phtml";

    if(isset($_POST["submit"])) {
        $username = $_POST["uid"];
        $pwd = $_POST["pwd"];
    
        if(empty($username) || empty($pwd)) {
            $_SESSION["errorMessage"] = "You did not fill in all the fields.";
        }
        else if(!$database->userExists($username)) {
            $_SESSION["errorMessage"] = "Username " . $username . " does not exist.";
        }
        else if(!$database->loginUser($username, $pwd)){
            $_SESSION["errorMessage"] = "Incorrect password";
        }
        else {
            header("location: index.php");
        }
    }

    require_once "phtml/header_html.phtml";
    require_once "phtml/login.phtml";
    require_once "phtml/footer.phtml";
?>