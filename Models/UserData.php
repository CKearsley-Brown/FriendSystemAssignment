<?php

class UserData {
    //private fields
    private $username, $name, $password, $emailAddress, $profilePicture, $activityTime, $latitude, $longitude;

    /**
     * Constructed to replicate a row in the user database and implement
     * the data into the website.
     */
    public function __construct($dbRow) {
        $this->username = $dbRow['username'];
        $this->name = $dbRow['name'];
        $this->password = $dbRow['password'];
        if (isset($this->_emailAddress['email_address'])){
            $this->emailAddress = 'NULL';
        }
        else{
            $this->emailAddress = $dbRow['email_address'];
        }
        $this->profilePicture = $dbRow['profile_picture'];
        if (is_null($this->profilePicture))
        {
            $this->profilePicture = "NULL";
        }
        $this->activityTime = $dbRow['activity_time'];
        $this->latitude = $dbRow['latitude'];
        $this->longitude = $dbRow['longitude'];
    }

    /**
     * @return mixed
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * @return mixed|string
     */
    public function getEmailAddress(){
        return $this->emailAddress;
    }

    /**
     * @return mixed|string
     */
    public function getProfilePicture(){
        return $this->profilePicture;
    }

    /**
     * @return mixed
     */
    public function getActivityTime(){
        return $this->activityTime;
    }

    /**
     * @return mixed
     */
    public function getLatitude(){
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude(){
        return $this->longitude;
    }
}