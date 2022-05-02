<?php
require_once ('Models/Database.php');
require_once ('Models/FriendData.php');
require_once ('Models/User.php');
require_once ('Models/UserDataSet.php');

class FriendDataSet
{
    protected $_dbHandle, $_dbInstance;

    /**
     * Constructs the friends table data set. This class provides results
     * to the controller.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * This retrieves all information in the friends database.
     */
    public function fetchAllFriends() {
        $sqlQuery = 'SELECT * FROM Friends';

        echo $sqlQuery;

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }

    /**
     * This method is used to view in the database, which confirmed friends
     * the user has. It does this by retrieving the row of friends that the
     * user is involved with. The user is then removed from the row, so it
     * returns an array of friends.
     */
    public function showFriends(){
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        //var_dump($user);
        $username = $user->getUsername();
        $sqlQuery = 'SELECT * FROM Friends WHERE sender="' . $username . '" OR recipient="' . $username . '" AND status=1';

        //echo $sqlQuery;

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = $row;
        }

        $friends = [];
        $i = 0;
        $rows = count($dataSet);
        while($i<$rows)
        {
            $singleRow = $dataSet[$i];
            if($singleRow[0] != $username)
            {
                array_push($friends,$singleRow[0]);
            }
            if($singleRow[1] != $username)
            {
                array_push($friends,$singleRow[1]);
            }
            $i = $i + 1;
        }

        return $friends;
    }

    /**
     * This function is used is to show friend requests to the user.
     */
    public function showFriendRequests(){
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);

        $username = $user->getUsername();
        $sqlQuery = 'SELECT * FROM Friends WHERE status=0 AND recipient="' . $username . '"';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = $row;
        }

        $requests = [];
        $i = 0;
        $rows = count($dataSet);
        while($i<$rows)
        {
            $singleRow = $dataSet[$i];
            if($singleRow[0] != $username)
            {
                array_push($requests,$singleRow[0]);
            }
            $i = $i + 1;
        }

        return $requests;
    }

    /**
     * This is used to add a friend. The information on the friends
     * is collected and then inserted into the database.
     */
    public function addFriend($friend)
    {
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        $username = $user->getUsername();

        //echo $username;
        //echo $friend;
        $sqlQuery = 'INSERT INTO Friends VALUES ("' . $username . '","' . $friend .'",0)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    /**
     * This method is used to see if the users are existing friends
     * on the site.
     */
    public function friendCheck($friend)
    {
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        $username = $user->getUsername();

        $sqlQuery = 'SELECT * FROM Friends WHERE sender="' . $username . '" AND recipient="' . $friend . '" AND status=1 OR sender="' . $friend . '" AND recipient="' . $username . '" AND status=1';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $row = $statement->fetch();
        var_dump($row);

        if($row != null)
            return true;
        else
            return false;
    }

    /**
     * This method confirms a friend request
     */
    public function confirmFriend($friend)
    {
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        $username = $user->getUsername();

        $sqlQuery = 'UPDATE Friends Set status=1 WHERE sender="' . $friend . '" AND recipient ="' . $username .'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    /**
     * This method rejects a friend request
     */
    public function rejectFriend($friend)
    {
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        $username = $user->getUsername();

        $sqlQuery = 'DELETE FROM Friends WHERE sender="' . $friend . '" AND recipient ="' . $username .'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
}