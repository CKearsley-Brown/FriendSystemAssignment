<?php
//Login controller is used to detect if user is logged in
include("logincontroller.php");

/*
 * If the user is logged in, they will be automatically be redirected
 * to the website home page
 */
if (isset($_SESSION['loggedIn']))
{
    if ($_SESSION['loggedIn'])
    {
        echo '<script type="text/javascript">window.location.href = "home.php";</script>';
    }
}
//Initialises objects
$view = new stdClass();
$view->pageTitle = 'Homepage';

include("registeruser.php");

require_once('Views/index.phtml');