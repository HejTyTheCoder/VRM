<?php
class Database{
    private $serverName;
    private $dbUserName;
    private $dbPassword;
    private $dbName;
    private $connection;
    //Vygral
    //O1S254sLsvnY34/Uks*+

    public function __construct(string $serverName = "localhost", string $dbUserName = "id20887925_vygral", string $dbPassword = "O1S254sLsvnY34/Uks*+", string $dbName = "id20887925_chatos"){
    //public function __construct(string $serverName = "localhost", string $dbUserName = "root", string $dbPassword = "", string $dbName = "chatos"){

        //error is thrown if connection is not established
        $this->connection = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->serverName = $serverName;
        $this->dbUserName = $dbUserName;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    public function getMessages(int $chatgroupid, int $numberOfMessages){
        $stmt = $this->connection->prepare("SELECT idm, idu, text, time from messages where idc = :idc order by time DESC limit ".$numberOfMessages);
        $stmt->execute(["idc" => $chatgroupid]);
        return array_reverse($stmt->fetchAll());
    }

    public function getChatList(int $idu){
        $stmt = $this->connection->prepare("SELECT DISTINCT c.idc, c.name, c.directchat from userchatgroups uc join chatgroups c on(uc.idc = c.idc) where uc.idu = :idu");
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

    public function getUserById(int $idu){
        if(!$this->userIdExists($idu)){
            throw new Exception("User does not exist");
        }
        $stmt = $this->connection->prepare("SELECT idu, nickname, authority, description FROM users WHERE idu = :idu");
        $stmt->execute(["idu" => $idu]);
        return $stmt->fetch();
    }

    public function getUserChatAuthority(int $idu, int $idc){
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

    public function createDirectChatGroup(string $groupName, int $idu1, int $idu2 = null){
        if($idu2 != null){
            if(!$this->userIdExists($idu1) || !$this->userIdExists($idu2)){
                throw new Exception("User does not exist");
            }
            $stmt = $this->connection->prepare("INSERT into chatgroups (name) values (:groupName)");
            $stmt->execute(["groupName" => $groupName]);
            $idc = (int)$this->connection->lastInsertId("idc");
            $this->addUserToGroup($idc, $idu1, 5);
            $this->addUserToGroup($idc, $idu2, 5);
            return $idc;
        }
        else{
            if(!$this->userIdExists($idu1)){
                throw new Exception("User does not exist");
            }
            $stmt = $this->connection->prepare("INSERT into chatgroups (name) values (:groupName)");
            $stmt->execute(["groupName" => $groupName]);
            $idc = (int)$this->connection->lastInsertId("idc");
            $this->addUserToGroup($idc, $idu1, 5);
            return $idc;
        }
    }

    public function userIdExists(int $idu){
        $stmt = $this->connection->prepare("SELECT idu from users where idu = :idu");
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
        if($chatGroupId == null){
            $chatGroupId = $this->createDirectChatGroup("DirectChatgroup", $senderId); 
        }
        $stmt = $this->connection->prepare("INSERT INTO invitations (sender, idu, idc, text) VALUES (:senderId, :acceptorId, :chatGroupId, :text)");
        $stmt->execute(["senderId" => $senderId, "acceptorId" => $acceptorId, "chatGroupId" => $chatGroupId, "text" => $text]);
    }

    public function declineChat($idi){
        if(!$this->inviteExists($idi)){
            throw new Exception("Initation does not exist");
        }
        $stmt = $this->connection->prepare("DELETE FROM invitations WHERE idi = :idi");
        if(!$stmt->execute(["idi" => $idi])){
            throw new Exception("Could not delete invitation");
        }
    }

    public function acceptChat($idi){
        $stmt = $this->connection->prepare("SELECT idu, idc FROM invitations WHERE idi = :idi");
        $stmt->execute(["idi" => $idi]);
        $values = $stmt->fetch();
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
            $_SESSION["username"] = $nickname;
            $_SESSION["idu"] = $this->getUser($_SESSION["username"])["idu"];
            return true;
        }
        return false;
    }

    public function changePassword(string $nickname, string $currentPassword, string $newPassword){
        if(!password_verify($currentPassword, $this->getPasswordsHash($nickname))){
            throw new Exception("Invalid current password");
        }
        $stmt = $this->connection->prepare("UPDATE users SET password = :password WHERE nickname = :nickname");
        if(!$stmt->execute(["nickname" => $nickname, "password" => $newPassword])){
            throw new Exception("Password change unsuccessful");
        }
    }

    public function changeDescription(string $nickname, string $description){
        $stmt = $this->connection->prepare("UPDATE users SET description = :description WHERE nickname = :nickname");
        if(!$stmt->execute(["nickname" => $nickname, "description" => $description])){
            throw new Exception("Description change unsuccessful");
        }
    }

    public function admin_deleteUserDescription(string $adminNickname, string $adminPassword, int $nickname){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        if(!$this->userExists($nickname)){
            throw new Exception("User does not exist");
        }
        $stmt = $this->connection->prepare("UPDATE users SET description = null WHERE nickname = :nickname");
        if(!$stmt->execute(["nickname" => $nickname])){
            throw new Exception("Description change unsuccessful");
        }
    }

    public function admin_getUserMessages(string $adminNickname, string $adminPassword, string $nickname, int $numberOfMessages){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        if(!$this->userExists($nickname)){
            throw new Exception("User does not exist");
        }
        $stmt = $this->connection->prepare("SELECT m.idm as 'idm', m.idu as 'idu', m.idc as 'idc', m.text as 'text', m.time as 'time' FROM messages m join users u on(u.idu = m.idu) where u.nickname = :nickname order by m.time desc limit ".$numberOfMessages);
        if(!$stmt->execute([":nickname" => $nickname])){
            throw new Exception("Action unsuccessful");
        }
        return $stmt->fetchAll();
    }

    public function admin_getChatGroupMessages(string $adminNickname, string $adminPassword, int $idc, int $numberOfMessages){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        if(!$this->chatGroupExists($idc)){
            throw new Exception("ChatGroup does not exist");
        }
        $stmt = $this->connection->prepare("SELECT * FROM messages where idc = :idc order by time DESC LIMIT ".$numberOfMessages);
        if(!$stmt->execute(["idc" => $idc])){
            throw new Exception("Action unsuccessful");
        }
        return $stmt->fetchAll();
    }

    public function admin_deleteInvite(string $adminNickname, string $adminPassword, int $idi){
        $this->validateAdmin($adminNickname, $adminPassword, 2);
        if(!$this->inviteExists($idi)){
            throw new Exception("Invite does not exist");
        }
        $stmt = $this->connection->prepare("DELETE FROM invites WHERE idi = :idi");
        if(!$stmt->execute(["idi" => $idi])){
            throw new Exception("Action unsuccessful");
        }
    }

    public function admin_deleteMessage(string $adminNickname, string $adminPassword, int $idm){
        $this->validateAdmin($adminNickname, $adminPassword, 2);
        if(!$this->messageExists($idm)){
            throw new Exception("Message not found");
        }
        $stmt = $this->connection->prepare("DELETE FROM messages where idm = :idm");
        $stmt->execute(["idm" => $idm]);
    }

    public function admin_deleteUser(string $adminNickname, string $adminPassword, string $nickname){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        if(!$this->userExists($nickname)){
            throw new Exception("User is already deleted");
        }
        $stmt = $this->connection->prepare("DELETE FROM users WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
    }

    public function admin_getMessages(string $adminNickname, string $adminPassword, int $numberOfMessages){
        $this->validateAdmin($adminNickname, $adminPassword, 4);
        $stmt = $this->connection->prepare("SELECT * FROM messages order by time DESC LIMIT ".$numberOfMessages);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function admin_createUser($adminNickname, $adminPassword, $nickname, $password){
        $this->validateAdmin($adminNickname, $adminPassword, 2);
        $this->createUser($nickname, $password);
    }

    public function admin_changeAuthority($adminNickname, $adminPassword, $nickname, $newAuthority){
        $this->validateAdmin($adminNickname, $adminPassword, $newAuthority);
        $stmt = $this->connection->prepare("UPDATE users SET authority = :authority WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname, "authority" => $newAuthority]);
    }

    public function admin_getUser($adminNickname, $adminPassword, $nickname){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        return $stmt->fetchAll();
    }

    public function admin_getInvites($adminNickname, $adminPassword, $numberOfInvites){
        $this->validateAdmin($adminNickname, $adminPassword, 4);
        $stmt = $this->connection->prepare("SELECT * FROM invitations LIMIT ".$numberOfInvites);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function admin_resetUserPassword($adminNickname, $adminPassword, $nickname, $password){
        $this->validateAdmin($adminNickname, $adminPassword, 5);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("UPDATE users SET password = :password WHERE nickname = :nickname");
        $stmt->execute(['nickname' => $nickname, 'password' => $hash]);
    }

    public function admin_getChatGroup($adminNickname, $adminPassword, $idc){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        $stmt = $this->connection->prepare("SELECT * FROM chatgroups WHERE idc = :idc");
        $stmt->execute(['idc' => $idc]);
        return $stmt->fetchAll();
    }

    public function admin_getChatGroups($adminNickname, $adminPassword, $limit){
        $this->validateAdmin($adminNickname, $adminPassword, 4);
        $stmt = $this->connection->prepare("SELECT * FROM chatgroups LIMIT ".$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function admin_deleteChatGroup($adminNickname, $adminPassword, $idc){
        $this->validateAdmin($adminNickname, $adminPassword, 5);
        $stmt = $this->connection->prepare("DELETE FROM chatgroups WHERE idc = :idc");
        $stmt->execute(['idc' => $idc]);
    }

    public function admin_changeDescription($adminNickname, $adminPassword, $idc, $description){
        $this->validateAdmin($adminNickname, $adminPassword, 2);
        $stmt = $this->connection->prepare("UPDATE chatgroups SET description = :description WHERE idc = :idc");
        $stmt->execute(['description' => $description, 'idc' => $idc]);
    }

    public function admin_createChatGroup($adminNickname, $adminPassword, $name){
        $this->validateAdmin($adminNickname, $adminPassword, 3);
        $this->createChatGroup($name);
    }

    public function admin_addUserToChatGroup($adminNickname, $adminPassword, $nickname, $idc){
        $this->validateAdmin($adminNickname, $adminPassword, 4);
        $this->addUserToGroup($idc, $this->getUserId($nickname), 0);
    }

    public function admin_deleteUserFromChatGroup($adminNickname, $adminPassword, $nickname, $idc){
        $this->validateAdmin($adminNickname, $adminPassword, 4);
        $stmt = $this->connection->prepare("DELETE FROM userchatgroups WHERE idu = :idu and idc = :idc");
        $stmt->execute(['idu' => $this->getUserId($nickname), 'idc' => $idc]);
    }

    public function inviteExists(int $idi){
        $stmt = $this->connection->prepare("SELECT * FROM invitations WHERE idi = :idi");
        $stmt->execute(["idi" => $idi]);
        if(sizeof($stmt->fetchAll()) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    private function getUserId($nickname){
        $stmt = $this->connection->prepare("SELECT idu FROM users WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        return $stmt->fetch()["idu"];
    }

    private function validateAdmin(string $adminNickname, string $adminPassword, int $neededAuthority){
        if(!$this->userExists($adminNickname)){
            throw new Exception("Admin user does not exist");
        }
        if($this->getUserAuthority($adminNickname) < $neededAuthority){
            throw new Exception("Insufficient permissions");
        }
        if(!password_verify($adminPassword, $this->getPasswordsHash($adminNickname))){
            throw new Exception("Invalid password for admin");
        }
    }

    private function messageExists(int $idm){
        $stmt = $this->connection->prepare("SELECT * FROM messages WHERE idm = :idm");
        $stmt->execute(["idm" => $idm]);
        if(sizeof($stmt->fetchAll()) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    private function getPasswordsHash(string $nickname){
        $stmt = $this->connection->prepare("SELECT password FROM users WHERE nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        return $stmt->fetch()["password"];
    }

    private function getUserAuthority(string $nickname){
        $stmt = $this->connection->prepare("SELECT authority from users where nickname = :nickname");
        $stmt->execute(["nickname" => $nickname]);
        return $stmt->fetch()["authority"];
    }

}

//testing functions
/*
$database = new Database();
//print_r($database->getChatList(1));
$database->createUser("TestUser", "heslo1234");
*/
?>