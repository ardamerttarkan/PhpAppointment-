<?php


$host = "localhost";
$user = "root";
$password = "root";
$dbname = "login_db";




$mysqli = new mysqli($host, $user, $password, $dbname);

if($mysqli->connect_errno){
    die("Bağlantı hatası: " . $mysqli->connect_error);
}

return $mysqli;