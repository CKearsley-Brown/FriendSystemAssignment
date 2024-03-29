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
     * This retrieves and returns all information in the friends database.
     *
     * @return array $dataset
     */
    public function fetchAllFriends() {
        $sqlQuery = 'SELECT * FROM Friends'; //this SQL query selects all information from the friends table

        //echo $sqlQuery;

        $statement = $this->_dbHandle->prepare($sqlQuery); //prepares SQL statement
        $statement->execute(); //executes SQL statement

        $dataSet=[]; //initialises array for the dataset to be inputted
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row); //each row is inputted into the dataset
        }
        return $dataSet; //returns dataset
    }

    /**
     * This method is used to view which confirmed friends the user has
     * in the database. It does this by retrieving the row of friends that the
     * user is involved with. The user is then removed from the row, so it
     * returns an array of friends.
     *
     * @return array $friends
     */
    public function showFriends(){
        $userData = $_SESSION["user"]; //Retrieves the current user's information
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        //var_dump($user);
        $username = $user->getUsername();
        $sqlQuery = 'SELECT * FROM Friends WHERE sender="' . $username . '"AND status=1 OR recipient="' . $username . '" AND status=1';

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
     * This function is used is to show friend requests to the user. It does this by
     * obtaining the users information and then using the information within
     * an SQL statement. The statement check if there is a relationship in the database
     * that has not yet been set as a friend. The user is then removed from the row.
     * The friend requesters information is then returned.
     *
     * @return array $requests
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
     * This is used to add a friend (send a notification). The
     * user's and friend's username is obtained and placed
     * in the database that represents relationships between
     * users. It is set a value to indicate that they are not
     * yet friends.
     *
     * @param String $friend
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
     * on the site. This is done by obtaining the user's and possible
     * friend's username. They are placed within an SQL statement that check
     * if any row indicates that the users are existing friends.
     * If the resulting statement is null, it indicates that they are
     * not friends and the opposite indicates that they are existing
     * friends. This information is passed via a boolean statement.
     *
     * @return array $dataset
     * @return boolean
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
        //var_dump($row);

        if($row != null)
            return true;
        else
            return false;
    }

    /**
     * This method confirms a friend request. This is done by
     * obtaining the user's and possible friend's username. The
     * existing relationship within the database that indicated
     * a notification had been sent is now set to one that indicate
     * that they are friends. A deletion is attempted on a possible
     * existing request from the receiver to the sender.
     *
     * @param $friend
     */
    public function confirmFriend($friend)
    {
        $userData = $_SESSION["user"];
        $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5], $userData[6], $userData[7]);
        $username = $user->getUsername();

        $sqlQuery = 'UPDATE Friends Set status=1 WHERE sender="' . $friend . '" AND recipient ="' . $username .'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        try {
            $sqlQuery = 'DELETE FROM Friends WHERE sender="' . $username. '" AND recipient ="' . $friend .'"';
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute();
        } catch(Exception $e) {

        }
    }

    /**
     * This method rejects a friend request. This is done by
     * obtaining the user's and possible friend's username. The
     * existing relationship within the database that indicated
     * that a notification had been sent will be deleted from the
     * database.
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