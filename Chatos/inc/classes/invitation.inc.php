<?php
class Invitation{
    private int $idi;
    private User $sender;
    private User $invited;
    private Chatgroup $chatgroup;

    public function __construct(int $idi, User $sender, User $invited, Chatgroup $chatgroup){
        $this->idi = $idi;
        $this->sender = $sender;
        $this->invited = $invited;
        $this->chatgroup = $chatgroup;
    }

}


?>