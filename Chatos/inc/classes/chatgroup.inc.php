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
}