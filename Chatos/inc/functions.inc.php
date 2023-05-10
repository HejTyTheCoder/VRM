<?php

//unused function with error
/*
function querySimple($sql, $paramKeys, $params, $error) {
    $sql = "";
    $stmt = mysqli_stmt_init($connection);
    
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "", $_SESSION["idu"], $_SESSION["idu"], $_SESSION["idu"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        do {
            echo "<br> <a href='chat.php?id=" . $row["IDc"] . "'>" . $row["username"] . "</a>";
        }while($row = mysqli_fetch_assoc($resultData));
    }
    else {
        echo "";
    }
    mysqli_stmt_close($stmt);
}
*/



function displayChats($accepted, Database $database) {
    $resultData = $database->getChatList($_SESSION("idu"), $accepted);
    $printed = 0;
    if($row = mysqli_fetch_assoc($resultData)) {
        if($accepted == 1) {
            echo "<br><br>Your chats:";
            
            do {
                echo "<br> <a href='chat.php?id=" . $row["IDc"] . "'>" . $row["username"] . "</a>";
            }while($row = mysqli_fetch_assoc($resultData));
        }
        else { 
            do {
                if($row["acceptor"] == $_SESSION["idu"]) {
                    if($printed == 0) {
                        echo "<br><br>Your requests:";
                        $printed = 1;
                    }
                    echo "
                        <br>" . $row['username'] . "<form action='inc/chatAcception.inc.php' method='post'>
                        <input type='hidden' name='IDc' value='" . $row["IDc"] . "'>
                        <input type='submit' name='accept' value='Accept'>
                        <input type='submit' name='decline' value='Decline'></form>
                    ";
                } 
            }while($row = mysqli_fetch_assoc($resultData));
        }
    }
    else {
        if($accepted == 1) {
            echo "<br><br>You have no chats.";
        }
    }
}

function printMessages(Database $database) {
    $resultData = $database->getMessages($_SESSION["idc"]);

    if($row = mysqli_fetch_assoc($resultData)) {
        do {
            if($row["user"] == $_SESSION["idu"]) {
                echo "<div class='bubbler1'><div class='bubble1 bubble'>" . $row["message"] . "</div></div>";
            }
            else {
                echo "<div class='bubbler2'><div class='bubble2 bubble'>" . $row["message"] . "</div></div>";
            }   
        }while($row = mysqli_fetch_assoc($resultData));
    }
    else {
        echo "<div class='center'>There are no messages.</div>";
    }
}

function loginUser(Database $database, $username, $pwd) {
    if($database->loginUser($username, $pwd)){
        return true;
    }
    return false;
}


function pwdMatch($pwd, $pwd2) {
    if($pwd != $pwd2) {
        return true;
    }
    return false;
}

function emptyInputLogin($username, $pwd) {
    if(empty($username) || empty($pwd)) {
        return true;
    }
    else {
        return false;
    }
}

function emptyInputSingup($username, $pwd, $pwd2) {
    if(empty($username) || empty($pwd) || empty($pwd2)) {
        return true;
    }
    else {
        return false;
    }
}

function invalidEmail($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    else {
        return false;
    }
}

function invalidUid($username) {
    if(!preg_match("/^[a-zA-Z0-9]*/", $username)) {
        return true;
    }
    else {
        return false;
    }
}


