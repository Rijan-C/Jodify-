<?php
$host = 'localhost';
$db   = 'JODIFY';
$user = 'jodify';
$pass = 'jodify123';

$conn = new mysqli($host, $user, $pass, $db);
if(!$conn){
    die("Connection failed: " . $conn->connect_error);
}
?>
