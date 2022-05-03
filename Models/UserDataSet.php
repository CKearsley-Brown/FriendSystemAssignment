<?php

require_once ('Models/Database.php');
require_once ('Models/UserData.php');
require_once ('Models/FriendDataSet.php');

class UserDataSet {
    protected $_dbHandle, $_dbInstance;

    /**
     * Constructs the user table data set. This class provides results
     * to the controller.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * This method is used to retrieve all users in the database.
     */
    public function fetchAllUsers() {
        $sqlQuery = 'SELECT * FROM User';

        //echo $sqlQuery;

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * This method registers a new user to the site. It gets passed the
     * imformation and inserts the information into the user database.
     * It provides protection when doing this by binding the parameters
     * to the database. It also hashes the password.
     */
    public function registerUser($username, $name, $password, $emailAddress, $profilePicture, $activityTime, $latitude, $longitude)
    {
        try {
            $sqlQuery = 'INSERT INTO User (username, name, password, email_address, profile_picture, activity_time, latitude, longitude) values (?,?,?,?,?,?,?,?)';
            $statement = $this->_dbHandle->prepare($sqlQuery);

            $encpwd = password_hash($password,PASSWORD_DEFAULT);

            $statement->bindParam(1, $username);
            $statement->bindParam(2, $name);
            $statement->bindParam(3, $encpwd);
            $statement->bindParam(4, $emailAddress);
            $statement->bindParam(5, $profilePicture);
            $statement->bindParam(6, $activityTime);
            $statement->bindParam(7, $latitude);
            $statement->bindParam(8, $longitude);

            return $statement->execute();
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * This method is used to create a user. This is done to create information
     * on the users that other classes can use.
     */
    public function makeUser($username)
    {
        $sqlQuery = 'SELECT * FROM User WHERE username="' . $username . '"';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        while ($row = $statement->fetch()) {
            $dataSet = new UserData($row);
        }
        return [$dataSet->getUsername(), $dataSet->getName(), $dataSet->getPassword(),$dataSet->getEmailAddress(),$dataSet->getProfilePicture(),$dataSet->getActivityTime(), $dataSet->getLatitude(), $dataSet->getLongitude()];
    }

    /**
     * This method logs to user into the website by verifying their
     * details with the database. The hashed passwords are compared here.
     */
    public function loginCheck($username, $password)
    {
        $sqlQuery = 'SELECT password FROM User WHERE username="' . $username . '"';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $result = $statement->fetchAll();
        //var_dump($result);
        if ($result == false)
        {
            return false;
        }
        $array = $result[0];
        $pwd = $array[0];

        if (password_verify($password, $pwd)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This method is used to return the friends of the user, it utalises
     * the Friend Data Set to get this information and then is able
     * to return the friend's information.
     */
    public function fetchFriends() {
        $friendData = new FriendDataSet();
        $friends = $friendData->showFriends();

        $dataSet=[];
        foreach ($friends as $friend)
        {
            $sqlQuery = 'SELECT * FROM User WHERE username="' . $friend . '"';

            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $dataSet[] = new UserData($row);
            }
        }

        return $dataSet;
    }

    /**
     * This method is used to return the notifications of the user, it utalises
     * the Friend Data Set to get this information and then is able
     * to return this information.
     */
    public function fetchNotificaitons($user) {
        $friendData = new FriendDataSet();
        $friends = $friendData->showFriendRequests();

        $dataSet=[];
        foreach ($friends as $friend)
        {
            $sqlQuery = 'SELECT * FROM User WHERE username="' . $friend . '"';

            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $dataSet[] = new UserData($row);
            }
        }

        return $dataSet;
    }

    /**
     * This method searches for users in the user database. It does
     * this through a search in the database via SQL. It will then
     * return this information.
     */
    public function searchUsers($user) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE "%' . $user . '" OR name LIKE "%' . $user . '"';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * This method searches for users in the user database then
     * readys it for pagination. It does a search in the database
     * via SQL. It will then return this information.
     */
    public function paginationSearchUsers($user, $startingLimit, $limit) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE "%' . $user . '" OR name LIKE "%' . $user . '" LIMIT ' . $startingLimit . ', ' . $limit . ' ';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * This method searches for users in the user database. It does
     * this through a search in the database via SQL. It will then
     * return this information.
     */
    public function getNumberOfUsers($user) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE "%' . $user . '" OR name LIKE "%' . $user . '"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }

        return count($dataSet);
    }

    /**
     * This method updates the user's latitude and longitude
     * information within the database.
     */
    public function updateCoordinates($user, $latitude, $longitude) {
        $sqlQuery = 'UPDATE User SET latitude = ' . $latitude . ', longitude = ' . $longitude . ' WHERE username = "' . $user .'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
}