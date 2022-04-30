<?php
//Starts session tracker
session_start();
require_once('Models/UserDataSet.php');
require_once('Models/User.php');

//Used to log in the user and prepare code needed for later pages
if (isset($_POST["loginButton"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $check = false;
    //echo $username;
    //echo $password;

    $userDataSet = new UserDataSet();
    $check = $userDataSet->loginCheck($username, $password);
    //var_dump($check);

    if($check == true)
    {
        echo "You are logged in";
        $_SESSION["login"] = $username;
        $_SESSION["loggedIn"] = true;

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
        $_SESSION["user"] = $thisUser;

        //Writing  a cookie
        setcookie('$_SESSION["login"]', true, time() + 3600);
        echo '<script type="text/javascript">window.location.href = "home.php";</script>';
    }
    else
    {
        echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
}

//Used to log out the user
if (isset($_POST["logoutButton"]))
{
    unset($_SESSION["login"]);
    session_destroy();
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
}
