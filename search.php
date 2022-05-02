<?php
//Login controller is used to detect if user is logged in
require("logincontroller.php");
require_once('Models/FriendDataSet.php');
require_once('Models/User.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = "Search";

//Results message
global $resultsMessage;
$resultsMessage = "Here are your results.";

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotificaitons($_SESSION["user"]);
    }
}

//Pagination
$resultPerPage = 10;
$searchDataSet = new UserDataSet();
//$numberOfUsers = $searchDataSet->getNumberOfUsers();
//$view->numberOfPages = ceil($numberOfUsers/$resultPerPage);
//var_dump($view->numberOfPages);
$search="";

//This is used to search users on the website
if (isset($_POST["searchButton"])) {
    $searchDataSet = new UserDataSet();
    //Orginal - $view->usersDataSet = $searchDataSet->searchUsers($_POST["search"]);
    $search = $_POST["search"];

    $numberOfUsers = $searchDataSet->getNumberOfUsers($search);
    //var_dump($numberOfUsers);
    $view->numberOfPages = ceil($numberOfUsers/$resultPerPage);

    $view->usersDataSet = $searchDataSet->paginationSearchUsers($search, 0, $resultPerPage);
}

if(isset($_GET['page']))
{
    $numberOfUsers = $searchDataSet->getNumberOfUsers($search);
    $view->numberOfPages = ceil($numberOfUsers/$resultPerPage);

    $pageInt = (int) $_GET['page'];
    $startingLimit = ($pageInt-1)*$resultPerPage;
    $searchDataSet = new UserDataSet();
    $view->usersDataSet = $searchDataSet->paginationSearchUsers($search, $startingLimit, $resultPerPage);
}

//This is used for the user to add a friend
if (isset($_POST["addButton"])) {
    //var_dump($_POST["friend"]);
    $friendDataSet = new FriendDataSet();
    try {
        if($friendDataSet->friendCheck($_POST['friend']) == false)
        {
            $friendDataSet->addFriend($_POST["friend"]);
        }
        else{
            $resultsMessage = "Already friends with this user.";
        }
    } catch (Exception $e)
    {
        $resultsMessage = "An application already exists.";
    }
    //var_dump($_POST[$friendDataSet]);
}

//Updates the search page view
require_once('Views/search.phtml');