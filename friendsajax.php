<?php
require_once('Models/UserDataSet.php');
require("logincontroller.php");
//Fetch for friend's info to be used by JSON
if (isset($_SESSION['loggedIn'])) {
    $friendDataSet = new UserDataSet(); //a user data set is initialised

    $results = $friendDataSet->fetchFriends(); //each row of the user's friend is fetched and set to a results variable

    $friendData = array(); //the data of all the user's friends to be sent

    //each result from the fetched friend will be inputted into an array where each bit of information will be set a key and then pushed into an overall friends data set
    foreach ($results as $result) {
        $friend = array('username' => $result->getUsername(), 'name' => $result->getName(), 'password' => $result->getPassword(), 'emailAddress' => $result->getEmailAddress(), 'profilePicture' => $result->getProfilePicture(), 'activityTime' => $result->getActivityTime(), 'latitude' => $result->getLatitude(), 'longitude' => $result->getLongitude());
        array_push($friendData, $friend);
    }
    //sends the friends data
    echo json_encode($friendData); //the data is wrapped in the json_encode method, so it can be used by javascript
}