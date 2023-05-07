<?php
class Database{
    private $serverName;
    private $dbUserName;
    private $dbPassword;
    private $dbName;
    private $connection;


    public function __construct(string $serverName = "localhost", string $dbUserName = "root", string $dbPassword = "", string $dbName = "Chatos"){
        $this->connection = mysqli_connect($serverName, $dbUserName, $dbPassword, $dbName);
        if(!$this->connection){
            throw new Exception("Could not connect to database");
        }
        $this->serverName = $serverName;
        $this->dbUserName = $dbUserName;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    public function getMessages(int $idc){
        $sql = "SELECT * FROM messages WHERE chat = ?;";
        $stmt = mysqli_stmt_init($this->connection);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            throw new Exception("Failed to prepare the statement");
        }
        
        mysqli_stmt_bind_param($stmt, "i", $idc);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);

    }

    public function getChatList(string $idu, int $accepted){
        $sql = "SELECT users.username, chats.IDc, chats.acceptor FROM users, chats, usersxchats WHERE users.IDu = usersxchats.IDu AND usersxchats.IDc = chats.IDc 
        AND users.IDu != ? AND (chats.applicant = ? OR chats.acceptor = ?) AND chats.accepted = ?;";
        $stmt = mysqli_stmt_init($this->connection);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "sssi", $idu, $idu, $idu, $accepted);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    function emailExists($email) {
        $sql = "SELECT * FROM users WHERE userEmail = ?;";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare statement");
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
    }
    
    function chatExists($applicant, $acceptor) {
        $sql = "SELECT * FROM chats WHERE (applicant = ? AND acceptor = ?) OR (acceptor = ? AND applicant = ?);";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare statement");
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
    }

    function deleteChat($IDc) {
        $sql = "DELETE FROM chats WHERE IDc = ?;";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare statement");
        }
        mysqli_stmt_bind_param($stmt, "i", $IDc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        $sql = "DELETE FROM usersxchats WHERE IDc = ?;";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare statement");
        }
        mysqli_stmt_bind_param($stmt, "i", $IDc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function uidExists($username) {
        $sql = "SELECT * FROM users WHERE username = ?;";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
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
    }

    function createChat($applicant, $acceptor) {
        $sql = "INSERT INTO chats (applicant, acceptor, accepted) VALUES (?, ?, 'false');";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "ii", $applicant, $acceptor);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        $sql = "SELECT IDc FROM chats WHERE applicant = ? AND acceptor = ?;";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "ii", $applicant, $acceptor);
        mysqli_stmt_execute($stmt);
        $IDc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
    
        $sql = "INSERT INTO usersxchats (IDu, IDc) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "ii", $applicant, $IDc["IDc"]);
        mysqli_stmt_execute($stmt);
        
        $stmt = mysqli_stmt_init($this->connection);
    
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "ii", $acceptor, $IDc["IDc"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        //should not be here
        /*
        header("location: ../index.php");
        exit();
        */
    }
    
    
    function createUser($username, $email, $pwd) {
        $sql = "INSERT INTO users (username, userEmail, userPwd, UserRole) VALUES (?, ?, ?, 'pleb');";
        $stmt = mysqli_stmt_init($this->connection);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        //should not be here
        /*
        loginUser($this->connection, $username, $pwd);
        */
    }

    function getComplaints(){
        $sql = "SELECT * FROM messages WHERE chat = -1 ORDER BY IDm DESC";
        $stmt = mysqli_stmt_init($this->connection);
        
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    function addMessage($idc, $idu, $message){
        $sql = "INSERT INTO messages (chat, user, message, edited) VALUES (?, ?, ?, 0);";
        $stmt = mysqli_stmt_init($this->connection);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "iis", $idc, $idu, $message);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function newComplaint($idc, $idu, $complaint){
        $sql = "INSERT INTO messages (chat, user, message, edited) VALUES (?, ?, ?, 0);";
        $stmt = mysqli_stmt_init($this->connection);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "iis", $_SESSION["idc"], $_SESSION["idu"], $_POST["complaint"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function acceptChat($idc){
        $sql = "UPDATE chats set accepted = 1 WHERE IDc = ?;";
        $stmt = mysqli_stmt_init($this->connection);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Failed to prepare the statement");
        }
        mysqli_stmt_bind_param($stmt, "i", $idc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

}

?>