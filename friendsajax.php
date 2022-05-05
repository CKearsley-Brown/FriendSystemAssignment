<?php
require_once('Models/UserDataSet.php');
require("logincontroller.php");
//Fetch for friend's info to be used by JSON
if (isset($_SESSION['loggedIn'])) {
    $friendDataSet = new UserDataSet();

    $results = $friendDataSet->fetchFriends();

    $friendData = array();

    foreach ($results as $result) {
        $friend = array('username' => $result->getUsername(), 'name' => $result->getName(), 'password' => $result->getPassword(), 'emailAddress' => $result->getEmailAddress(), 'profilePicture' => $result->getProfilePicture(), 'activityTime' => $result->getActivityTime(), 'latitude' => $result->getLatitude(), 'longitude' => $result->getLongitude());
        array_push($friendData, $friend);
    }
    echo json_encode($friendData);
}