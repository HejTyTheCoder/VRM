<?php

if(isset($_GET["error"])) {
    switch($_GET["error"]) {
        case "login":
            echo "<div class='error'>Incorrect login informations.</div>";
            break;
        case "chatu":
            echo "<div class='error'>This user doesn't exist.</div>";
            break;
        case "chate":
            echo "<div class='error'>You already have a chat with this user.</div>";
            break;
        case "pwd":
            echo "<div class='error'>The passwords are not the same.</div>";
            break;
        case "empty":
            echo "<div class='error'>You forgot to fill something..</div>";
            break;
        case "emaili":
            echo "<div class='error'>Email adress does not exist.</div>";
            break;
        case "uidi":
            echo "<div class='error'>You can use only letters or numbers for your username.</div>";
            break;  
        case "emaile":
            echo "<div class='error'>This email adress is already used.</div>";
            break;
        case "uide":
            echo "<div class='error'>This username is already used.</div>";
            break;     
    }
}