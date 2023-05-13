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
}
?>