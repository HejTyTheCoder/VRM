<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "Chatos";

$connection = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if(!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}