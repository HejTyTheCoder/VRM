<?php
class User{
    private int $idu;
    private string $nickname;
    private int $authority;
    private string $description;

    private array $invitations;
    private array $chatgroups;

    public function __construct(int $idu, string $nickname = "", int $authority = 0, string $description = "", array $invitations = array(), array $chatgroups = array()) {
        $this->idu = $idu;
        $this->nickname = $nickname;
        $this->authority = $authority;
        $this->description = $description;
        $this->invitations = $invitations;
        $this->chatgroups = $chatgroups;
    }

    public function loadChatGroups(Database $database){
        $results = $database->getChatList($this->idu);
        foreach($results as $chatgroup){
            array_push($this->chatgroups, new Chatgroup($chatgroup[0], $chatgroup[1]));
        }
    }

    public function displayChatGroups(){
        foreach($this->chatgroups as $chatgroup){
            echo($chatgroup->getName()."<br>");
        }
    }
}
?>