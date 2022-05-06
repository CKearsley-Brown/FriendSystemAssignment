<?php
//Login controller is used to detect if user is logged in
require_once('Models/UserDataSet.php');
require("logincontroller.php");
require_once('location.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Home';

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotifications($_SESSION["user"]); //gets friend's data for the people who have sent a notification
    }
}

//Used to accept user's friend notifications
if (isset($_POST['acceptButton'])) {
    //var_dump($_POST['friend']);
    $friendDataSet = new FriendDataSet();
    if($friendDataSet->friendCheck($_POST['friend']) == false) //checks if the users are already friends
    {
        $friendDataSet->confirmFriend($_POST['friend']); //updates database to show that users are now friends
    } else{
        echo "already friends";
    }
    header("Refresh:0"); //refreshes page
}

//Used to reject user's friend notifications
if (isset($_POST['rejectButton'])) {
    $friendDataSet = new FriendDataSet();
    $friendDataSet->rejectFriend($_POST['friend']);
    header("Refresh:0"); //refreshes page
}


//Used to fetch all users and pass them onto the home page's view
$usersDataSet = new UserDataSet();
$view->usersDataSet = $usersDataSet->fetchAllUsers();

require_once('Views/home.phtml');