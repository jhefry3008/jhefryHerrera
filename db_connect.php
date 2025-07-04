<?php

$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "visor"; 
$port = 3307; 

$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "";
?>
