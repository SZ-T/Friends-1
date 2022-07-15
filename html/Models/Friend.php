<?php

require_once ("Models/Database.php");

class Friend
{
    protected $_dbInstance, $_dbHandle;

    // Connect to the database
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    // Create a friend request
    public function create($sender, $recipient) {
        $sqlQuery = 'INSERT INTO friends (sender, recipient, status) VALUES (?, ?, ?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$sender, $recipient, 'Pending']);
    }

    // Remove a friend or friend request
    public function delete($user, $friend)
    {
        $sqlQuery = 'SELECT * FROM friends WHERE sender IN (:id1, :id2) AND recipient IN (:id1, :id2)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute(['id1' => $user, 'id2' => $friend]);
        $data = $statement->fetch();
        $sqlQuery = 'DELETE FROM friends WHERE sender = :sender AND recipient = :recipient';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute(['sender' => $data["sender"], 'recipient' => $data["recipient"]]);
    }

    // Accept a friend request
    public function accept($sender, $recipient) {
        $sqlQuery = 'UPDATE friends SET status = ? WHERE sender = ? AND recipient = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute(['Accepted', $sender, $recipient]);
    }
}