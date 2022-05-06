<?php
//Updating Latitude and Longitude Information
//$userDataSet = new UserDataSet();
if(isset($_COOKIE['latitude']))
{
    $latitude = $_COOKIE['latitude'];
    $_SESSION['user'][6] = $latitude;
}
if(isset($_COOKIE['longitude']))
{
    $longitude = $_COOKIE['longitude'];
    $_SESSION['user'][7] = $longitude;
}
//var_dump($longitude);
if(isset($_SESSION['loggedIn']) && isset($_COOKIE['latitude']))
{
    $userDataSet = new UserDataSet();
    $userDataSet->updateCoordinates($_SESSION['user'][0], $latitude, $longitude);
}