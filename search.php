<?php
//Login controller is used to detect if user is logged in
require("logincontroller.php");
require_once('Models/FriendDataSet.php');
require_once('Models/User.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = "Search";

//Results message
global $resultsMessage;
$resultsMessage = "Here are your results.";

//Creates user
$array = $_SESSION["user"];
$user = new User($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7]);

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotificaitons($_SESSION["user"]);
    }
}

//This is used to search users on the website
if (isset($_POST["searchButton"])) {
    $searchDataSet = new UserDataSet();
    $view->usersDataSet = $searchDataSet->searchUsers($_POST["search"]);
}

//This is used for the user to add a friend
if (isset($_POST["addButton"])) {
    //var_dump($_POST["friend"]);
    $friendDataSet = new FriendDataSet();
    try {
        if($friendDataSet->friendCheck($_POST['friend']) == false)
        {
            $friendDataSet->addFriend($_POST["friend"]);
        }
        else{
            $resultsMessage = "Already friends with this user.";
        }
    } catch (Exception $e)
    {
        $resultsMessage = "An application already exists.";
    }
    //var_dump($_POST[$friendDataSet]);
}

//Updates the search page view
require_once('Views/search.phtml');