<?php
error_reporting(E_ALL);
ini_set('dislay_errors', 1);
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if(! $_SESSION['userid']){
    echo "Only logged in users may access this page. Click <a href='login_form.php'here</a> to login<br>";
    exit;
}

include "db_connect.php";

$new_joke_question=addslashes($_GET["newjoke"]);
$new_joke_answer=addslashes($_GET["newanswer"]);
$new_joke_user_id=$_SESSION['userid'];



//TELLS DATABASE TO SELECT INFO FROM SPECIFIC TABLE
//SEARCH THE DATABASE FOR THE WORD CHICKEN
echo "<h2> Trying to add a new joke: $new_joke_question and $new_joke_answer for id $new_joke_user_id</h2>";


$stmt = $mysqli->prepare("INSERT INTO Jokes_table(JokeID,Joke_question,Joke_answer,users_id) VALUES (null,?,?,?)");
$stmt->bind_param("ssi",$new_joke_question,$new_joke_answer,$userid);

echo "<pre>";
print_r($stmt);
echo "</pre>";

$result = $stmt->execute();
echo "<pre>";
print_r($result);
echo "</pre>";

$stmt->close();

include "search_all_jokes.php";

?>

<a href="index.php">Return to main page</a>