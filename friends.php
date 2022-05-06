<?php
//Login controller is used to detect if user is logged in
require("logincontroller.php");
require_once('Models/FriendDataSet.php');
require_once('location.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Friends';

//Used to fetch notifications and pass them to the friend's view
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotifications($_SESSION["user"]);
    }
}

//Used to fetch the users friends and pass them to the friend's view
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendsDataSet = new UserDataSet();
        $view->usersDataSet = $friendsDataSet->fetchFriends();
    }
}

//implement's the friend page view
require_once('Views/friends.phtml');
