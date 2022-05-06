<?php
//Updating Latitude and Longitude Information
//$userDataSet = new UserDataSet();
if(isset($_COOKIE['latitude'])) //follows instructions if the cookie named latitude is set
{
    $latitude = $_COOKIE['latitude'];
    $_SESSION['user'][6] = $latitude; //Sets current user's session latitude information as the latitude
}
if(isset($_COOKIE['longitude'])) //follows instructions if the cookie named latitude is set
{
    $longitude = $_COOKIE['longitude'];
    $_SESSION['user'][7] = $longitude; //Sets current user's session longitude information as the latitude
}
//var_dump($longitude);
if(isset($_SESSION['loggedIn']) && isset($_COOKIE['latitude'])) //follows instructions if the user is logged in and the latitude cookie has been set (latitude represents that both cookies have been made)
{
    $userDataSet = new UserDataSet();
    $userDataSet->updateCoordinates($_SESSION['user'][0], $latitude, $longitude); //updates the user's latitude and longitude in the database
}