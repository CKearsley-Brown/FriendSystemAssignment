<?php
require_once('Models/UserDataSet.php');

$userDataSet = new UserDataSet();

if($_REQUEST["in"]!="") { //if the request sent by the XMLHttpRequest is not blank, the following instructions will run
    $results = $userDataSet->searchUsers($_REQUEST["in"]); //the information within the request is searched in the database, this function include a limit to just 10 users to be outputted

    $userData = array(); //initialisation of the user data to be sent

    //each result from the searched user will be inputted into an array where each bit of information will be set a key and then pushed into an overall friends data
    foreach ($results as $result) {
        $user = array('username' => $result->getUsername(), 'name' => $result->getName(), 'password' => $result->getPassword(), 'emailAddress' => $result->getEmailAddress(), 'profilePicture' => $result->getProfilePicture(), 'activityTime' => $result->getActivityTime(), 'latitude' => $result->getLatitude(), 'longitude' => $result->getLongitude());
        array_push($userData, $user);
    }
    //sends the users data
    echo json_encode($userData); //the data is wrapped in the json_encode method, so it can be used by javascript
}

// Orginal Suggestion List
/*
$usernames=array();
foreach ($results as $userData)
{
    $username = $userData->getUsername();
    array_push($usernames, $username);
}

//var_dump($results);

$hint="";

if($in !== "")
{
    foreach ($usernames as $user) {
        if ($hint === "") {
            $hint ="<br/> $user";
        } else {
            $hint .= "<br/> $user";
        }
    }
}

echo $hint === "" ? "user doesn't exist" : $hint;
*/