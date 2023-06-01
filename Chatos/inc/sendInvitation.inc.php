<?php
session_start();
require_once "require_classes.inc.php";
require_once "dbh.inc.php";

$senderId = $database->getUser($_SESSION["username"])["idu"];
$acceptorId = $database->getUser($_POST["uid"])["idu"];

$database->sendInvite($senderId, $acceptorId);

header("Location: ../index.php");
exit();
?>