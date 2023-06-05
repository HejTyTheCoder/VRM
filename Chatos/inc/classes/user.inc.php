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

    public function getId(){
        return $this->idu;
    }

    public function getNickname(){
        return $this->nickname;
    }

    public function loadInformations(Database $database){
        $results = $database->getUserById($this->idu);
        $this->nickname = $results['nickname'];
        $this->authority = $results['authority'];
        $this->description = $results['description'];
    }

    public function loadChatGroups(Database $database){
        $results = $database->getChatList($this->idu);
        foreach($results as $chatgroup){
            array_push($this->chatgroups, new Chatgroup($chatgroup[0], $chatgroup[1]));
        }
    }

    public function displayChatGroups(Database $database){
        echo "<div class='group'>";
        foreach($this->chatgroups as $chatgroup){
            if($chatgroup->isDirectChat()){
                echo("<a class='chaty' href='index.php?id=".$chatgroup->getId()."'><div class='chaty'>".$chatgroup->getSecondUserName($this->idu, $database)."</div></a>");
            }
            else{
                echo("<a class='chaty' href='index.php?id=".$chatgroup->getId()."'><div class='chaty'>".$chatgroup->getName()."</div></a>");
            }
        }
        echo "</div>";
    }

    public function loadInvitations(Database $database){
        $results = $database->getInvitations($this->idu);
        foreach($results as $invitation){
            array_push($this->invitations, new Invitation($invitation["idi"], new User($invitation["sender"]), $this, new Chatgroup($invitation["idc"]), $invitation["text"]));
        }
    }

    public function displayInvitations(){
        if(sizeof($this->invitations) == 0){
            echo("You do not have any invitations");
        }
        else{
            foreach($this->invitations as $invitation){
                ?>
                <form action="#" method="get">
                    <label for="idi" . <?=$invitation->getId()?>><?=$invitation?></label>
                    <input type="hidden" name="idi" id="idi" . <?=$invitation->getId()?> value=<?=$invitation->getId()?>>
                    <input type="submit" name="inviteSubmit" value="Decline">
                    <input type="submit" name="inviteSubmit" value="Accept">
                </form>
                <?php
            }
        }
    }
}
?>