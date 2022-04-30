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

$usersDataSet = new UserDataSet();
$view->usersDataSet = $usersDataSet->fetchAllUsers();

require_once('Views/home.phtml');