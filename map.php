<?php
//Login controller is used to detect if user is logged in
require_once('Models/UserDataSet.php');
require("logincontroller.php");
require_once('location.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Map';

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotifications($_SESSION["user"]);
    }
}

require_once('Views/map.phtml');
