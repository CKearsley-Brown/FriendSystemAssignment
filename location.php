<?php

//Updating Latitude and Longitude Information
//$userDataSet = new UserDataSet();
$latitude = $_COOKIE['latitude'];
$longitude = $_COOKIE['longitude'];
//var_dump($longitude);
if(isset($_SESSION['user']))
{
    $userDataSet = new UserDataSet();
    $userDataSet->updateCoordinates($_SESSION['user'][0], $latitude, $longitude);
}