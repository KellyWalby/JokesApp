<head>
<?php

session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";
$username=addslashes($_POST['username']);
$password=addslashes($_POST['password']);


//TELLS DATABASE TO SELECT INFO FROM SPECIFIC TABLE
//SEARCH THE DATABASE FOR THE WORD CHICKEN
echo "You've attempted to login with " . $username . "and " . $password . "<br>";

$stmt = $mysqli ->prepare("SELECT id, username, password FROM users where username = ?");
$stmt -> bind_param("s",$username);

$stmt->execute();
$stmt->store_result();

$stmt ->bind_result($userid, $uname, $pw);

if ($stmt->num_rows == 1){
  echo "I found one person with that username.<br>";
  $stmt->fetch();
  if (password_verify($password, $pw)){
    echo "The password matches<br>";
    echo "Login successful<br>";
    $_SESSION['username']=$uname;
    $_SESSION['userid']=$userid;
    exit;
  }
  else{
    $_SESSION = [];
    session_destroy();
  }
}
else{
  $_SESSION = [];
  session_destroy();
}
echo "Login failed<br>";

echo "SESSION = <br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>