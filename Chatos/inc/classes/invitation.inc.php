<?php

class Invitation{
    private int $idi;
    private User $sender;
    private User $invited;
    private Chatgroup $chatgroup;
    private string $text;

    public function __construct(int $idi, User $sender, User $invited, Chatgroup $chatgroup, $text = null){
        $this->idi = $idi;
        $this->sender = $sender;
        $this->invited = $invited;
        $this->chatgroup = $chatgroup;
        if($text == null){
            $this->text = '';
        }
        else{
            $this->text = $text;
        }
    }

    public function __toString(){
        if($this->text == ""){
            return($this->chatgroup->getName());
        }
        else{
            return($this->chatgroup->getName().": ".$this->text);
        }
    }

    public function getId(){
        return $this->idi;
    }

}


?>