<?php

//TURN ON ERROR REPORTING FOR TESTING PURPOSES (WILL BE DELETED)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); //SESSION START

//THIS LINK IS REQUIRED TO ACCESS THE GOOGLE LOGIN FUNCTION
//PUT THERE WHEN 'google require google/apiclient' WAS RAN
require_once('vendor/autoload.php');

//THIS DATA IS CUSTOMIZED ACCORDING TO GOOGLE PROJECT
$client_id = '174275068560-jru21i9kaqk4j0ptfnt5e9upjnp9klqa.apps.googleusercontent.com';
$client_secret = 'zkDX5iJGiqa2C_V9BRrTnuxq';
$redirect_url = 'http://localhost:8888/Jokes_App/google_login.php';

//MYSQL DETAILS
$db_username = "danbjy6mmpf678om"; //DATABASE USERNAME
$db_password = "w4ifio4523qkdws4"; //DATABASE PASSWORD
$host_name = "localhost"; //MySql HOSTNAME
$db_name = "p94d31c1oceqibru"; //DATABASE NAME
$port = 3306;

//CREATE A NEW CONNECTION TO THE GOOGLE LOGIN SERVICE
$client = new Google_Client();
$client ->setClientID($client_id);
$client ->setClientSecret($client_secret);
$client ->setRedirectUri($redirect_url);
$client ->addScope("email");
$client ->addScope("profile");
$service = new Google_Service_Oauth2($client);

//THERE ARE MULTIPLE CASES THAT THIS PAGE HANDLES DEPENDING ON WHAT 'GET' VALUES & 'SESSION' VARIABLES ARE SET
//CASE 1 - LOGOUT
if(isset($_GET['logout'])){
    $client->revokeToken($_SESSION['access_token']);
    session_destroy();
    header('Location: index.php');
}

//CASE 2 - THE URL CONTAINS A CODE FROM THE GOOGLE LOGIN SERVICE
if(isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    $go_here = filter_var($redirect_url,FILTER_SANITIZE_URL);
    header('Location: ' . $go_here);
    exit;
}

//CASE 3 - 'THE ACCESS_TOKEN' SESSION VARIABLE IS SET & THE USER HAS BEEN LOGGED IN
//IF THE USER HASNT BEEN LOGGING, SET THE VARIABLE '$authUrl' TO THE LOGIN PAGE
if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
}

//CASE 4 - THE USER IS NOT LOGGED IN SO DISPLAYS LOGIN PAGE
if(isset($authUrl)){
    //SHOW LOGIN URL
    echo '<div align="center">';
    echo '<h3>Login</h3>';
    echo '<div>You will need a Google account to sign in.</div>';
    echo '<a class="login" href="' . $authUrl . '">Login here</a>';
    echo '</div>';
    exit;
}

//CASE 5 - USER HAS BEEN LOGGED IN. DISPLAY DATA ABOUT THEM AND ADD THEM TO DATABASE
$user = $service->userinfo->get(); //GET USER INFO


//CONNECT TO DB
$mysqli = new mysqli($host_name, $db_username, $db_password, $db_name, $port);
if ($mysqli->connect_error){
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

//CHECK IF USER EXISTS IN USERS TABLE
$stmt = $mysqli->prepare("SELECT ID, username, password, google_id, google_name,
google_email, google_picture_link FROM users WHERE google_id=?");
$stmt->bind_param("s", $user->id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userid, $username, $password,$google_id,$google_name,$google_email,
$google_picture_link);

if ($stmt->num_rows > 0){
    //WE FOUND A RECORD IN THE USERS TABLE. THIS IS A RETURNING USER.
    echo "<h2>Welcome back " .$user->name. "!</h2>";
    echo "<p><a href ='" .$redirect_uri. "?logout=1'>Log Out</a></p>";
    echo "<p><a href ='index.php'>Go to main page</a></p>";

    while ($stmt->fetch()){
        echo "According to the database record you are:<br>";
        echo "<br>userid: " .$userid;
        echo "<br>username: " .$username;
        echo "<br>password: " .$password;
        echo "<br>google_id: " .$google_id;
        echo "<br>google_name: " .$google_name;
        echo "<br>google_email: " .$google_email;
        echo "<br>google_picture_link: " .$google_picture_link;
    }

    //SAVE LOGIN FOR OTHER PAGES IN APP TO USE
    $_SESSION['username']=$user->name;
    $_SESSION['googleuserid']=$user->id;
    $_SESSION['useremail']=$user->email;
    $_SESSION['userid']=$userid;
} else {
    //USER IS NOT FOUND IN THE USER TABLE ITS A NEW USER
    echo "<h2>Welcome" . $username . " Thanks for Registering!</h2>";
    //PUT USER INTO USERS TABLE
    $statement = $mysqli->prepare("INSERT INTO users(google_id,google_name,google_email,google_picture_link) VALUES (?,?,?,?)");
    $statement->bind_param('ssss',$user->id,$user->name,$user->email,$user->picture);

    $statement->execute();
    $newuserid = $statement->insert_id;
    echo $mysqli->error;
    echo "<p>Created new user:</p>";

    echo "New user id = " . $newuserid . "<br>";
    echo "<br>username: "; //LEFT BLANK FOR GOOGLE USERS
    echo "<br>password: "; //left BLANK FOR GOOGLE USERS
    echo "<br>google_id: " .$user->id;
    echo "<br>google_name: " .$user->name;
    echo "<br>google_email: " .$user->email;
    echo "<br>google_picture_link: " .$user->picture;

     $_SESSION['userid']=$newuserid;
     $_SESSION['username']=$user->name;
     $_SESSION['googleuserid']=$user->id;
     $_SESSION['useremail']=$user->email;
}


//PRINT USER DETAILS
echo "<p>About this user</p>";
echo "<ul>";
echo "<img src='" .$user->picture."'/>";
echo "<li>Username: " .$user->name . "</li>";
echo "<li>User ID: " .$_SESSION['userid'] . "</li>";
echo "<li>User email: " .$user->email . "</li>";
echo "</ul>";
echo "<p>Now go check the database to see if the new user has been inserted into the table.</p>";
echo "<a href = 'index.php'>Return to the main page</a>";

echo "<br>Session values = <br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>

<!-- Add some simple syles according to preference -->

<style>
body{
    font-family:"helvetica";
}
img{
    height:100px;
}

</style>

