<?php
$servername = "localhost";      //server
$username = "root";             //username
$password = "";                 //password
$dbname = "epitaproject";               //database

#create db connection
$conn = new mysqli($servername, $username, $password, $dbname);  //connection to MySQL
#check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>