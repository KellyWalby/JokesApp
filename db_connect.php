<?php

//FOUR VARIABLES TO CONNECT TO THE DATABASE
$host = "localhost";
$username = "root";
$user_password = "root";
$database_in_use = "test";

//CREATE A DATABASE CONNECTION INSTANCE WITH DEFINED VARAIBLES ABOVE
$mysqli = new mysqli($host, $username, $user_password, $database_in_use);

//IF VARIABLES TO CONNECT THE DATABASE ARE WRONG, THROWS ERROR, IF RIGHT, PRINTS ITS CONNECTED
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "<br>";

?>