<?php
class Database{
    private $serverName;
    private $dbUserName;
    private $dbPassword;
    private $dbName;
    private $connection;


    public function __construct(string $serverName = "localhost", string $dbUserName = "root", string $dbPassword = "", string $dbName = "Chatos"){
        //error is thrown if connection is not established
        $this->connection = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->serverName = $serverName;
        $this->dbUserName = $dbUserName;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    public function getMessages(int $chatgroupid){
        $stmt = $this->connection->prepare("SELECT idm, idu, text, time from messages where idc = :idc");
        $stmt->execute(["idc" => $chatgroupid]);
        return $stmt->fetchAll();
    }

    public function getChatList(int $idu){
        $stmt = $this->connection->prepare("SELECT c.idc, c.name from userchatgroups uc join chatgroups c on(uc.idc = c.idc) where uc.idu = :idu");
        $stmt->execute(["idu" => $idu]);
        return $stmt->fetchAll();
    }

    public function getUserList(int $idc){
        $stmt = $this->connection->prepare("SELECT idu FROM userchatgroups WHERE idc = :idc");
        $stmt->execute(["idc" => $idc]);
        return $stmt->fetchAll();
    }

    public function getInvitations(int $invitedId){
        $stmt = $this->connection->prepare("SELECT * FROM invitations WHERE idu = :idu");
        $stmt->execute(["idu" => $invitedId]);
        return $stmt->fetchAll();
    }

    public function getUser(string $nickname){
        $stmt = $this->connection->prepare("SELECT idu, nickname, authority, description FROM users where nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        return $stmt->fetch();
    }

    public function getUserAuthority(int $idu, int $idc){
        $stmt = $this->connection->prepare("SELECT authority FROM userchatgroups WHERE idu = :idu and idc = :idc");
        $stmt->execute(["idu" => $idu, "idc" => $idc]);
        return $stmt->fetch();
    }

    public function createUser(string $nickname, string $password) {
        if($this->userExists($nickname)){
            throw new Exception("User $nickname already exists");
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);//password_verify()
        $stmt = $this->connection->prepare("INSERT into users (nickname, password) values (:nickname, :password)");
        $stmt->execute(["nickname" => $nickname, "password" => $hash]);
    }

    public function createChatGroup(string $groupName, int $ownerId = null){
        $stmt = $this->connection->prepare("INSERT into chatgroups (name, directchat) values (:groupName, false)");
        $stmt->execute(["groupName" => $groupName]);
        $idc = $this->connection->lastInsertId();
        if($ownerId != null){
            if(!$this->userIdExists($ownerId)){
                throw new Exception("User does not exist");
            }
            $this->addUserToGroup($idc, $ownerId, 5);
        }
        return $idc;
    }

    public function createDirectChatGroup(string $groupName, int $idu1, int $idu2){
        if(!$this->userIdExists($idu1) || !$this->userIdExists($idu2)){
            throw new Exception("User does not exist");
        }
        $stmt = $this->connection->prepare("INSERT into chatgroups (name) values (:groupName)");
        $stmt->execute(["groupName" => $groupName]);
        $idc = $this->connection->lastInsertId();
        $this->addUserToGroup($idc, $idu1, 5);
        $this->addUserToGroup($idc, $idu2, 5);
        return $idc;
    }

    public function userIdExists(int $idu){
        $stmt = $this->connection->prepare("SELECT idu from users where idu = $idu");
        $stmt->execute(["idu" => $idu]);
        if(sizeof($stmt->fetchAll()) == 0){
            return false;
        }
        return true;
    }

    public function userExists(string $nickname){
        $stmt = $this->connection->prepare("SELECT idu from users where nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        if(sizeof($stmt->fetchAll()) == 0){
            return false;
        }
        else{
            return true;
        }
    }
    
    public function chatGroupExists(int $idc) {
        $stmt = $this->connection->prepare("SELECT idc from chatgroups where idc = :idc");
        $stmt->execute(["idc" => $idc]);
        if(sizeof($stmt->fetchAll()) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function deleteChat(int $idc) {
        $stmt = $this->connection->prepare("DELETE FROM chatgroups WHERE idc = :idc");
        $stmt->execute(["idc" => $idc]);
    }

    public function sendMessage(int $senderId, int $chatGroupId, string $message){
        $stmt = $this->connection->prepare("INSERT INTO messages (idu, idc, text) VALUES (:senderId, :chatGroupId, :message)");
        $stmt->execute(["senderId" => $senderId, "chatGroupId" => $chatGroupId, "message" => $message]);
    }
    
    public function sendInvite(int $senderId, int $acceptorId, int $chatGroupId = null, string $text = null){
        $this->createDirectChatGroup("", $chatGroupId, $acceptorId);
        $stmt = $this->connection->prepare("INSERT INTO invitations (sender, idu, idc, text) VALUES (:senderId, :acceptorId, :chatGroupId, :text)");
        $stmt->execute(["senderId" => $senderId, "acceptorId" => $acceptorId, "chatGroupId" => $chatGroupId, "text" => $text]);
    }

    public function acceptChat($idi){
        $stmt = $this->connection->prepare("SELECT idu, idc FROM invitations WHERE idi = :idi");
        $stmt->execute(["idi" => $idi]);
        $values = $stmt->fetchAll();
        if(sizeof($values) == 0){
            throw new Exception("Invitation not found");
        }
        $this->addUserToGroup($values["idc"], $values["idu"]);
        $stmt = $this->connection->prepare("DELETE FROM invitations WHERE idi = :idi");
        $stmt->execute(["idi" => $idi]);
    }

    public function addUserToGroup(int $chatGroupId, int $userId, int $authority = 0){
        $stmt = $this->connection->prepare("INSERT INTO userchatgroups (idu, idc, authority) VALUES (:userId, :chatGroupId, :authority)");
        $stmt->execute(["userId" => $userId, "chatGroupId" => $chatGroupId, "authority" => $authority]);
    }

    public function loginUser(string $nickname, string $password){
        if(!$this->userExists($nickname)){
            return false;
        }
        $stmt = $this->connection->prepare("SELECT password FROM users WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        if(password_verify($password, $stmt->fetch()[0])){
            return true;
        }
        return false;
    }


}

//testing functions
/*
$database = new Database();
//print_r($database->getChatList(1));
$database->createUser("TestUser", "heslo1234");
*/
?>