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
        echo "<div class='group'>";
        foreach($this->chatgroups as $chatgroup){
            echo("<a class='chaty' href='index.php?id=".$chatgroup->getId()."'><div class='chaty'>".$chatgroup->getName()."</div></a>");
        }
        echo "</div>";
    }

    public function loadInvitations(Database $database){
        $results = $database->getInvitations($this->idu);
        foreach($results as $invitation){
            array_push($this->invitations, new Invitation($invitation["idi"], $invitation["sender"], $this, new Chatgroup($invitation["ide"]), $invitation["text"]));
        }
    }

    public function displayInvitations(){
        if(sizeof($this->invitations) == 0){
            echo("You do not have any invitations");
        }
        else{
            foreach($this->invitations as $invitation){
                echo($invitation."<br>");
            }
        }
    }
}
?>