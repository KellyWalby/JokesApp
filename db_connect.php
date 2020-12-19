<?php

//FOUR VARIABLES TO CONNECT TO THE DATABASE
$host = "z3iruaadbwo0iyfp.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$username = "danbjy6mmpf678om";
$user_password = "w4ifio4523qkdws4";
$database_in_use = "test";

//CREATE A DATABASE CONNECTION INSTANCE WITH DEFINED VARAIBLES ABOVE
$mysqli = new mysqli($host, $username, $user_password, $database_in_use);

//IF VARIABLES TO CONNECT THE DATABASE ARE WRONG, THROWS ERROR, IF RIGHT, PRINTS ITS CONNECTED
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "<br>";

?>
