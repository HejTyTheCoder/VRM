<?php
class Message{
    private int $idm;
    private User $user;
    private Chatgroup $chatgroup;
    private string $text;
    private DateTime $time;

    public function __construct(int $idm, User $user, Chatgroup $chatgroup, string $text, DateTime $time){
        $this->idm = $idm;
        $this->user = $user;
        $this->chatgroup = $chatgroup;
        $this->text = $text;
        $this->time = $time;
    }
}