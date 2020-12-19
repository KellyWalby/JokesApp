<?php

include "db_connect.php";

$new_username = addslashes($_POST['username']);
$new_password1 = addslashes($_POST['password1']);
$new_password2 = addslashes($_POST['password2']);

$hashed_password = password_hash($new_password1, PASSWORD_DEFAULT);

echo "<h2>Trying to add a new user " . $new_username . " pw = " . $new_password1 . " and " . $new_password2 ."</h2>";

if ($new_password1 != $new_password2){
    echo "Passwords do not match. Please try again.";
    exit;
}

preg_match('/[0-9]+/',$new_password1, $matches);
if (sizeof ($matches ) == 0){
    echo "The password must have at least one number<br>";
    exit;
}

preg_match('/[!@#$%^&*()]+/',$new_password1, $matches);
if (sizeof ($matches ) == 0){
    echo "The password must have at least one special character<br>";
    exit;
}

if (strlen($new_password1)<= 8) {
    echo "The password must be at least 8 characters long<br>";
    exit;
}

//CHECK TO SEE IF THE USER HAS ALREADY REGISTERED

$sql = "SELECT * FROM users where username = '$new_username'";

$result = $mysqli->query($sql) or die(mysqli_error($mysqli));

if ($result -> num_rows > 0){
    //oops. already someone with that username
    echo "The username" . $new_username . "has already been taken. Can't register twice!";
    exit;
}


//INSERT NEW USER
//$sql = "INSERT INTO users (id, username, password) VALUES (NULL,'$new_username','$hashed_password')";

$stmt = $mysqli->prepare("INSERT INTO users (id, username, password) VALUES (NULL,?,?)");
$stmt ->bind_param("ss", $new_username,$hashed_password);
$result = $stmt -> execute();


if ($result){
    echo "Registration success!";
}
else {
    echo "Something went wrong. Not registered";
}

echo "<a href = 'index.php'>Return to main page.</a>";

?>