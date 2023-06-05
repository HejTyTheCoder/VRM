<?php
class Chatgroup{
    private int $idc;
    private string $name;
    private bool $directchat;

    private array $users;
    private array $messages;

    public function __construct(int $idc, string $name = "chatgroup", bool $directchat = true, array $users = array(), array $messages = array()){
        $this->idc = $idc;
        $this->name = $name;
        $this->directchat = $directchat;
        $this->users = $users;
        $this->messages = $messages;
    }

    public function isDirectChat(){
        return $this->directchat;
    }

    public function loadMessages(Database $database){
        $results = $database->getMessages($this->idc, 100);
        foreach ($results as $message) {
            array_push($this->messages, new Message($message[0], $message[1], $this, $message[2], new DateTime($message[3])));
        }
    }

    public function loadUsers(Database $database){
        $results = $database->getUserList($this->idc);
        foreach($results as $user){
            array_push($this->users, new User($user[0]));
        }
        foreach($this->users as $user){
            $user->loadInformations($database);
        }
    }

    public function getSecondUserName(int $firstIdu, Database $database){
        if(empty($this->users)){
            $this->loadUsers($database);
        }
        if($this->directchat == false){
            throw new Exception("This is not a direct chat");
        }
        if($this->users[0]->getId() == $firstIdu){
            return $this->users[1]->getNickname();
        }
        else{
            return $this->users[0]->getNickname();
        }
    }

    public function printMessages(){
        //Print messages here
        foreach($this->messages as $message){
            //print individual messages here
            echo "<div class='bunka'>";
            echo "<p class=vbunce>";
            echo $message->getText();
            echo "</p>";
            echo "</div>";
        }
    }

    public function getId(){
        return $this->idc;
    }

    public function getName(){
        return $this->name;
    }
}