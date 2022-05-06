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
     *
     * @return array dataSet
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
     * This method registers a new user to the site. It gets the passed
     * information and inserts it into the user database via an SQL statement.
     * It provides protection when doing this by binding the parameters
     * to the database. It also hashes the password.
     *
     * @return mixed
     * @param String $username
     * @param String $password
     * @param String $emailAddress
     * @param String $profilePicture
     * @param int $activityTime
     * @param int $latitude
     * @param int $longitude
     */
    public function registerUser($username, $name, $password, $emailAddress, $profilePicture, $activityTime, $latitude, $longitude)
    {
        try {
            $sqlQuery = 'INSERT INTO User (username, name, password, email_address, profile_picture, activity_time, latitude, longitude) values (?,?,?,?,?,?,?,?)';
            $statement = $this->_dbHandle->prepare($sqlQuery);

            $encpwd = password_hash($password,PASSWORD_DEFAULT); //hashing the password

            //the binding of the parameters
            $statement->bindParam(1, $username);
            $statement->bindParam(2, $name);
            $statement->bindParam(3, $encpwd);
            $statement->bindParam(4, $emailAddress);
            $statement->bindParam(5, $profilePicture);
            $statement->bindParam(6, $activityTime);
            $statement->bindParam(7, $latitude);
            $statement->bindParam(8, $longitude);

            return $statement->execute(); //returns executed statement
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * This method is used to create a user. This is done to create information
     * on the users that can be used by other classes. An SQL statement retrieves
     * the row where the username matches with the one in the parameter. Each
     * value is returned in an array.
     *
     * @return array
     * @param String $username
     */
    public function makeUser($username)
    {
        $sqlQuery = 'SELECT * FROM User WHERE username=?';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$username); //binding of username parameter
        $statement->execute();

        //the while loop fetches the appropriate row and sets it to the dataSet variable
        while ($row = $statement->fetch()) {
            $dataSet = new UserData($row);
        }
        //return of the user's information
        return [$dataSet->getUsername(), $dataSet->getName(), $dataSet->getPassword(),$dataSet->getEmailAddress(),$dataSet->getProfilePicture(),$dataSet->getActivityTime(), $dataSet->getLatitude(), $dataSet->getLongitude()];
    }

    /**
     * This method is used to log the user into the website by verifying their
     * details with the database. This is done by executing an SQL statement
     * that retrieves the user's password in hash from. If the username is
     * wrong, the statement will result in being null. If not, the password
     * is verified by making sure the hashes in the database and what has been
     * inputted match. If so, a true boolean statement is return and a false
     * one is returned if the hashes do not match.
     *
     * @return boolean
     * @param String $username
     * @param String $password
     */
    public function loginCheck($username, $password)
    {
        $sqlQuery = 'SELECT password FROM User WHERE username=?';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$username);
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
     * This method is used to return the friends of the user, it utilises
     * the Friend Data Set to get this information and then is able
     * to return the friend's information.
     *
     * @return array $dataset
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
     * This method is used to return the notifications of the user, it utilises
     * the Friend Data Set to get this information and then is able
     * to return this information.
     *
     * @return array $dataset
     * @param String $user
     */
    public function fetchNotifications($user) {
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
     * this through a search in the database via SQL. The SQL obtains
     * values that start with the parameter $user. The output of the
     * search is limited to 10.
     *
     * @return array $dataset
     * @param String $user
     */
    public function searchUsers($user) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE ?"%" OR name LIKE ?"%" LIMIT 0,10';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$user);
        $statement->bindParam(2,$user);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * This method searches for users in the user database then
     * prepares it for pagination. It achieves this through returning particular
     * users, which is determined by a starting limit and a limit of the number of
     * users to obtain. It does a search in the database via SQL. The SQL obtains
     * values that start with the parameter $user.
     *
     * @return array $dataset
     * @param String $user
     * @param int $startingLimit
     * @param int $limit
     */
    public function paginationSearchUsers($user, $startingLimit, $limit) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE ?"%" OR name LIKE ?"%" LIMIT ' . $startingLimit . ', ' . $limit . ' ';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$user);
        $statement->bindParam(2,$user);
        $statement->execute();

        $dataSet=[];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * This method is used to return the number of particular users.
     * It achieves this via a search in the database via SQL. The SQL
     * obtains values that start with the parameter $user. The values
     * are counted and then returned.
     *
     * @return int
     * @param String $user
     */
    public function getNumberOfUsers($user) {
        $sqlQuery = 'SELECT * FROM User WHERE username LIKE "' . $user . '%" OR name LIKE "' . $user . '%"';
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
     *
     * @param String $user
     * @param int $latitude
     * @param int $longitude
     */
    public function updateCoordinates($user, $latitude, $longitude) {
        $sqlQuery = 'UPDATE User SET latitude = ' . $latitude . ', longitude = ' . $longitude . ' WHERE username = "' . $user .'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
}