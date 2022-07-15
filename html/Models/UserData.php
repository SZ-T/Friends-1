<?php

class UserData {
    
    protected $_id, $_email, $_firstName, $_lastName, $_username, $_image;
    
    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_email = $dbRow['email'];
        $this->_firstName = $dbRow['first_name'];
        $this->_lastName = $dbRow['last_name'];
        $this->_username = $dbRow['username'];
        $this->_image = $dbRow['profile'];
    }

    public function getID() {
        return $this->_id;
    }

    public function getEmail() {
        return $this->_email;
    }
   
    public function getFirstName() {
       return $this->_firstName;
    }
    
    public function getLastName() {
       return $this->_lastName;
    }
    
    public function getUsername() {
       return $this->_username;
    }
    
    public function getImage(): string
    {
        if ($this->_image) {
            return 'images/profile/'.$this->getID().'.png';
        } else {
            return 'images/profile/0.png';
        }
    }

    public function getStatus() {
        return false;
    }

    public function getSender() {
        return false;
    }
}


