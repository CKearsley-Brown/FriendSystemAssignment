<?php
require_once('Models/UserDataSet.php');
$success = false;

//Used to register user
if (isset($_POST["confirmButton"]))
{
    //var_dump($_POST);
    //Attempt to register a user
    $success = false;

    $userDataSet = new UserDataSet();

    //Sets the registered users information within the database
    $result = $userDataSet->registerUser($_POST["username"], $_POST["name"], $_POST["password"], $_POST["email"], "/images/default_img.png", 0, NULL, NULL);

    //Resulting message to the user
    if($result == false)
    {
        $message = 'There was an error in creating the user';
    }
    else{
        $message = 'The user has been created, you can now login';
    }
}

//Used with index page so shows and updates page
require_once('Views/index.phtml');