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
        $stmt = $this->connection->prepare("SELECT idu, text from messages where idc = :idc");
        $stmt->execute(["idc" => $chatgroupid]);
        return $stmt->fetchAll();
    }

    public function getChatList(int $idu){
        $stmt = $this->connection->prepare("SELECT c.name from userchatgroups uc join chatgroups c on(uc.idc = c.idc) where ud.idu = :idu");
        $stmt->execute(["idu" => $idu]);
        return $stmt->fetchAll();
    }

    public function createUser(string $nickname, string $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);//password_verify()
        $stmt = $this->connection->prepare("insert into users (nickname, password) values (:nickname, :password)");
        $stmt->execute(["nickname" => $nickname, "password" => $hash]);
    }

    public function createChatGroup(string $groupName){
        $stmt = $this->connection->prepare("insert into chatgroups (name) values (:groupName)");
        $stmt->execute(["name" => $groupName]);
    }

    public function createDirectChatGroup(string $groupName, int $idu1, int $idu2){
        $stmt = $this->connection->prepare("insert into chatgroups (name) values (:groupName)");
        $stmt->execute(["name" => $groupName]);
        //$stmt = $this->connection->prepare("insert into userchatgroups (idu, idc
    }
    
    function chatExists($applicant, $acceptor) {
        
    }

    function deleteChat($IDc) {
        
    }

    public function uidExists($username) {
        
    }

    function createChat($applicant, $acceptor) {
        
    }
    
    

    function getComplaints(){
        
    }

    function addMessage($idc, $idu, $message){
        
    }

    public function newComplaint($idc, $idu, $complaint){
        
    }

    public function acceptChat($idc){
        
    }

}

//testing functions
/*
$database = new Database();
//print_r($database->getChatList(1));
$database->createUser("TestUser", "heslo1234");
*/
?>