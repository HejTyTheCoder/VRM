<?php
session_start();
require_once "require_classes.inc.php";
require_once "dbh.inc.php";

$idi = $_GET["idi"];

$database->acceptChat($idi);

header("Location: ../index.php");

?>