<?php

class User
{
    private $username;
    private $name;
    private $password;
    private $emailAddress;
    private $profilePicture;
    private $activityTime;
    private $latitude;
    private $longitude;

    /**
     * This contructs a user class that contains and replicates a user's
     * attributes.
     */
    public function __construct($username, $name, $password, $emailAddress, $profilePicture, $activityTime, $latitude, $longitude)
    {
        $this->username=$username;
        $this->name=$name;
        $this->password=$password;
        $this->emailAddress=$emailAddress;
        $this->profilePicture=$profilePicture;
        $this->activityTime=$activityTime;
        $this->latitude=$latitude;
        $this->longitude=$longitude;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return mixed
     */
    public function getActivityTime()
    {
        return $this->activityTime;
    }

    /**
     * @param mixed $activityTime
     */
    public function setActivityTime($activityTime)
    {
        $this->activityTime = $activityTime;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
}