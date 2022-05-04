<?php
require_once('Models/UserDataSet.php');

$userDataSet = new UserDataSet();

if(isset($_REQUEST["in"]))
{
    $in = $_REQUEST["in"];
}
else{
    $in = "";
}

if($_REQUEST["in"]!="") {
    $results = $userDataSet->searchUsers($in);

    $userData = array();
    $num = 0;

    foreach ($results as $result) {
        $user = array('username' => $result->getUsername(), 'name' => $result->getName(), 'password' => $result->getPassword(), 'emailAddress' => $result->getEmailAddress(), 'profilePicture' => $result->getProfilePicture(), 'activityTime' => $result->getActivityTime(), 'latitude' => $result->getLatitude(), 'longitude' => $result->getLongitude());
        array_push($userData, $user);
    }
}
echo json_encode($userData);

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