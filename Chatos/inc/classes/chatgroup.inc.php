<?php
class Chatgroup{
    private int $idc;
    private string $name;
    private bool $directchat;

    private array $users;
    private array $messages;

    public function __construct(int $idc, string $name, bool $directchat = false, array $users = array(), array $messages = array()){
        $this->idc = $idc;
        $this->name = $name;
        $this->directchat = $directchat;
        $this->users = $users;
        $this->messages = $messages;
    }

    public function loadMessages(Database $database){
        $results = $database->getMessages($this->idc);
        foreach ($results as $message) {
            array_push($this->messages, new Message($message[0], $message[1], $this, $message[2], $message[3]));
        }
    }

    public function loadUsers(Database $database){
        $results = $database->getUserList($this->idc);
        foreach($results as $user){
            array_push($this->users, new User($user[0]));
        }
    }

    public function getName(){
        return $this->name;
    }
}