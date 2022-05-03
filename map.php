<?php
//Login controller is used to detect if user is logged in
require_once('Models/UserDataSet.php');
require("logincontroller.php");
//require_once("Views/js/map.js");

//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Map';

//Used to show the user's friend notifications
global $latitude, $longitude;
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotificaitons($_SESSION["user"]);
    }
}

//Updating Latitude and Longitude Information, this is used to fetch all users and pass them onto the home page's view
//$userDataSet = new UserDataSet();
$latitude = $_COOKIE['latitude'];
$longitude = $_COOKIE['longitude'];
//var_dump($longitude);
if(isset($_SESSION['user']))
{
    $userDataSet = new UserDataSet();
    $userDataSet->updateCoordinates($_SESSION['user'][0], $latitude, $longitude);
}

require_once('Views/map.phtml');
