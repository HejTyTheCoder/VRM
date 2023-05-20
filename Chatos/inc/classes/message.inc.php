<?php
class Message{
    private int $idm;
    private int $idu;
    private User $user;
    private Chatgroup $chatgroup;
    private string $text;
    private DateTime $time;

    public function __construct(int $idm, int $idu, Chatgroup $chatgroup, string $text, DateTime $time){
        $this->idm = $idm;
        $this->idu = $idu;
        $this->chatgroup = $chatgroup;
        $this->text = $text;
        $this->time = $time;
    }

    public function loadUser(User $user){
        $this->user = $user;
    }

    public function getText(){
        return $this->text;
    }
}