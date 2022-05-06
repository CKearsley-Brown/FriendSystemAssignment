<?php
//Login controller is used to detect if user is logged in
require("logincontroller.php");
require_once('Models/FriendDataSet.php');
require_once('location.php');

//Initialises objects
$view = new stdClass();
$view->pageTitle = "Search";

//Results message
global $resultsMessage; //this is a global string to be used by the view
$resultsMessage = "Here are your results.";

//Used to show the user's friend notifications
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) {
        $friendNotificationDataSet = new UserDataSet();
        $view->userDataSet = $friendNotificationDataSet->fetchNotifications($_SESSION["user"]);
    }
}

//Pagination
$resultPerPage = 30; //defines how many users should be shown on one page
$searchDataSet = new UserDataSet();
//$numberOfUsers = $searchDataSet->getNumberOfUsers();
//$view->numberOfPages = ceil($numberOfUsers/$resultPerPage);
//var_dump($view->numberOfPages);
$search="";

//This is used to search users on the website
if (isset($_POST["searchButton"]) || isset($_GET['search'])) { //runs function if the search button has been pressed or the search get superglobal variable is set
    $searchDataSet = new UserDataSet();
    //Orginal - $view->usersDataSet = $searchDataSet->searchUsers($_POST["search"]);
    if(isset($_POST["searchButton"])) //if statement sets the searched string into the search variable
    {
        $search = $_POST["search"];
    } else
    {
        $search = $_GET['search'];
    }

    $numberOfUsers = $searchDataSet->getNumberOfUsers($search); //Gets number of users there are within the search
    //var_dump($numberOfUsers);
    $view->numberOfPages = ceil($numberOfUsers/$resultPerPage); //the number of pages for pagination is determined by a rounded up number of the number of users divided by the defined number of users on page

    $view->usersDataSet = $searchDataSet->paginationSearchUsers($search, 0, $resultPerPage); //this is used for the initial search page and returns all users within the determined limit
}

if(isset($_GET['page'])) //this function is run when a page is clicked on
{
    $numberOfUsers = $searchDataSet->getNumberOfUsers($search); //Gets number of users there are within the search
    $view->numberOfPages = ceil($numberOfUsers/$resultPerPage); //the number of pages for pagination is determined by a rounded up number of the number of users divided by the defined number of users on page

    $pageInt = (int) $_GET['page']; //the page string is converted into an integer
    $startingLimit = ($pageInt-1)*$resultPerPage; // this determines the limit to where to start to within the database
    $searchDataSet = new UserDataSet();
    $view->usersDataSet = $searchDataSet->paginationSearchUsers($search, $startingLimit, $resultPerPage);//this is used searches for uses from set limit and will return the determined limit of users
}

//This is used for the user to add a friend
if (isset($_POST["addButton"])) {
    //var_dump($_POST["friend"]);
    $friendDataSet = new FriendDataSet();
    try {
        if($friendDataSet->friendCheck($_POST['friend']) == false) //checks if the users are not already friends
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