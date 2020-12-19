<!-- <head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>jQuery UI Accordion - Default Functionality</title>
<link rel="stylesheet" href ="//code.jquery.com/ui/1." -->


<?php

include "db_connect.php";

//TELLS DATABASE TO SELECT INFO FROM SPECIFIC TABLE
//THIS GRABS JOKEID, JOKE_QUESTION, JOKE_ANSWER FROM THE JOKES_TABLE
$sql = "SELECT JokeID, Joke_question, Joke_answer, users_ID, username, google_name FROM 
Jokes_table JOIN users on jokes_table.users_id = users.id";


$result = $mysqli->query($sql);

//THIS OUTPUTS ANY DATA 
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<h3>" . $row['Joke_question'] . "</h3>";
    echo "<div><p>" . $row["Joke_answer"] . " -- Submitted by user " . $row["users_id"] . "whose name is " .$row['username'] .$row['google_name'] . "</p></div>";
  }
} else {
    //PRINTS IF THERE IS NO DATA
  echo "0 results";
}

?>