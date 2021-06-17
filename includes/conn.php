<?php

$serverName = 'localhost';
$username = 'root';
$password = '';
$db_Name = 'elearning';


$mysqli = new mysqli($serverName, $username, $password,$db_Name);

if(!$mysqli){
  die('Connection Failed'. mysqli_connect_error());
}
