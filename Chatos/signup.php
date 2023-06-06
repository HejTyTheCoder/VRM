<?php
    require_once "phtml/header.phtml";

    if(isset($_POST["submit"])) {
        $username = $_POST["uid"];
        $pwd = $_POST["pwd"];
        $pwd2 = $_POST["pwd2"];
       
        if(empty($username) || empty($pwd) || empty($pwd2)) {
            $errorMessage = "You did not fill in all the fields.";
        }
        else if(!preg_match("/[a-zA-Z0-9]/", $username)) {
            $errorMessage = "The username is not valid.";
        }
        else if($database->userExists($username)) {
            $errorMessage = "The username " . $username . " is already used.";
        }
        else if($pwd != $pwd2) {
            $errorMessage = "Passwords do not match.";
        }
        else if(mb_strlen($pwd) < 8 || !preg_match("~\d~", $pwd) || !preg_match("~[A-Z]~", $pwd)) {
            $errorMessage = "Password must contail at least 1 capital letter, 1 number and the minimum lenght is 8 characters.";
        }
        else {
            $database->createUser($username, $pwd);
            $database->loginUser($username, $pwd);
            header("location: index.php");
        }
    }

    require_once "phtml/signup.phtml";
    require_once "phtml/footer.phtml";
?>