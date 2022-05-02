<?php
//Login controller is used to detect if user is logged in
require_once('Models/UserDataSet.php');
require("logincontroller.php");

//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Home';

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotificaitons($_SESSION["user"]);
    }
}

//Used to accept or reject user's friend notifications
if (isset($_POST['acceptButton'])) {
    //var_dump($_POST['friend']);
    $friendDataSet = new FriendDataSet();
    if($friendDataSet->friendCheck($_POST['friend']) == false)
    {
        $friendDataSet->confirmFriend($_POST['friend']);
    } else{
        echo "already friends";
    }
    header("Refresh:0");
}
if (isset($_POST['rejectButton'])) {
    $friendDataSet = new FriendDataSet();
    if($friendDataSet->friendCheck($_POST['friend']) == false)
    {
        $friendDataSet->rejectFriend($_POST['friend']);
    } else{
        echo "already friends";
    }
    header("Refresh:0");
}


//Used to fetch all users and pass them onto the home page's view
$usersDataSet = new UserDataSet();
$view->usersDataSet = $usersDataSet->fetchAllUsers();

require_once('Views/home.phtml');