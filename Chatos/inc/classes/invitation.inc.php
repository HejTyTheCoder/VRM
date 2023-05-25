<?php

class Invitation{
    private int $idi;
    private User $sender;
    private User $invited;
    private Chatgroup $chatgroup;
    private string $text;

    public function __construct(int $idi, User $sender, User $invited, Chatgroup $chatgroup, string $text){
        $this->idi = $idi;
        $this->sender = $sender;
        $this->invited = $invited;
        $this->chatgroup = $chatgroup;
        $this->text = $text;
    }

    public function __toString(){
        echo($this->chatgroup->getName().": ".$this->text);
    }

}


?>