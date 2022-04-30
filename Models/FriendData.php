<?php

class FriendData
{
    //private fields
    private $sender, $recipient, $status;

    /**
     * Constructed to replicate a row in the friends database and implement
     * the data into the website.
     */
    public function __construct($dbRow)
    {
        $this->sender = $dbRow['sender'];
        $this->recipient = $dbRow['recipient'];
        $this->status = $dbRow['status'];
    }

    /**
     * @return mixed
     */
    public function getSender(){
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getRecipient(){
        return $this->recipient;
    }

    /**
     * @return mixed
     */
    public function getStatus(){
        return $this->status;
    }
}