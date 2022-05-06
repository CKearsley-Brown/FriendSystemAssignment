<?php
//Starts session tracker
session_start();
require_once('Models/UserDataSet.php');
require_once('Models/User.php');

//Used to log in the user and prepare code needed for later pages
if (isset($_POST["loginButton"])) {
    $username = $_POST["username"]; //Sets posted username to variable
    $password = $_POST["password"]; //Sets posted password to variable
    $check = false;
    //echo $username;
    //echo $password;

    $userDataSet = new UserDataSet();
    $check = $userDataSet->loginCheck($username, $password); //Gets returned a boolean to determine if the login information matches with the database
    //var_dump($check);

    //logs user in if it is true
    if($check == true)
    {
        //echo "You are logged in";
        $_SESSION["login"] = $username; //Sets login session to username
        $_SESSION["loggedIn"] = true; //Sets the login status

        //Creation of the user to personalise the website
        $userInfo = $userDataSet->makeUser($_POST["username"]);
        $username = $userInfo[0];
        $name = $userInfo[1];
        $password = $userInfo[2];
        $emailAddress = $userInfo[3];
        $profilePicture = $userInfo[4];
        $activityTime = $userInfo[5];
        $latitude = $userInfo[6];
        $longitude = $userInfo[7];

        $thisUser = array($username, $name, $password, $emailAddress, $profilePicture, $activityTime, $latitude, $longitude);
        //var_dump($thisUser);
        $_SESSION["user"] = $thisUser; //Sets state of user information

        //Writing  a cookie
        setcookie('$_SESSION["login"]', true, time() + 3600);
        echo '<script type="text/javascript">window.location.href = "home.php";</script>'; //redirects user to the login page
    }
    else
    {
        echo '<script type="text/javascript">window.location.href = "index.php";</script>'; //resets the login page
    }
}

//Used to log out the user
if (isset($_POST["logoutButton"]))
{
    //unset($_SESSION["login"]);
    session_destroy(); //destroys all sessions
    echo '<script type="text/javascript">window.location.href = "index.php";</script>'; //redirects user to the login page
}
