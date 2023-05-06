<?php

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

function displayChats($connection, $accepted) {
    $sql = "SELECT users.username, chats.IDc, chats.acceptor FROM users, chats, usersxchats WHERE users.IDu = usersxchats.IDu AND usersxchats.IDc = chats.IDc 
    AND users.IDu != ? AND (chats.applicant = ? OR chats.acceptor = ?) AND chats.accepted = ?;";
    $stmt = mysqli_stmt_init($connection);
    $printed = 0;

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sssi", $_SESSION["idu"], $_SESSION["idu"], $_SESSION["idu"], $accepted);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

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
    
    mysqli_stmt_close($stmt);
}

function printMessages($connection) {
    $sql = "SELECT * FROM messages WHERE chat = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["idc"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

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

function loginUser($connection, $username, $pwd) {
    if(uidExists($connection, $username)) {
        $uid = uidExists($connection, $username);
    }
    else if(emailExists($connection, $username)) {
        $uid = emailExists($connection, $username);
    }
    else {
        header("location: ../login.php?error=login");
        exit();
    }

    if(password_verify($pwd, $uid["userPwd"])) {
        session_start();

        $_SESSION["idu"] = $uid["IDu"];
        $_SESSION["username"] = $uid["username"];
        $_SESSION["role"] = $uid["role"];

        header("location: ../index.php");
        exit();
    }
    else {
        header("location: ../login.php?error=login");
        exit();
    }
}

function createUser($connection, $username, $email, $pwd) {
    $sql = "INSERT INTO users (username, userEmail, userPwd, UserRole) VALUES (?, ?, ?, 'pleb');";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmt");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    loginUser($connection, $username, $pwd);
}

function createChat($connection, $applicant, $acceptor) {
    $sql = "INSERT INTO chats (applicant, acceptor, accepted) VALUES (?, ?, 'false');";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $applicant, $acceptor);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "SELECT IDc FROM chats WHERE applicant = ? AND acceptor = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $applicant, $acceptor);
    mysqli_stmt_execute($stmt);
    $IDc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO usersxchats (IDu, IDc) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $applicant, $IDc["IDc"]);
    mysqli_stmt_execute($stmt);
    
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $acceptor, $IDc["IDc"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../index.php");
    exit();
}

function deleteChat($connection, $IDc) {
    $sql = "DELETE FROM chats WHERE IDc = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $IDc);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM usersxchats WHERE IDc = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $IDc);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function chatExists($connection, $applicant, $acceptor) {
    $sql = "SELECT * FROM chats WHERE (applicant = ? AND acceptor = ?) OR (acceptor = ? AND applicant = ?);";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssss", $applicant, $acceptor, $applicant, $acceptor);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if(mysqli_fetch_assoc($resultData)) {
        return true;
    }
    else {
        return false;
    }
    mysqli_stmt_close($stmt);
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

function emailExists($connection, $email) {
    $sql = "SELECT * FROM users WHERE userEmail = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
    mysqli_stmt_close($stmt);
}

function uidExists($connection, $username) {
    $sql = "SELECT * FROM users WHERE username = ?;";
    $stmt = mysqli_stmt_init($connection);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmt");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
    mysqli_stmt_close($stmt);
}
