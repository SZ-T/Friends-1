<?php

require_once ("Models/Database.php");
require_once ("Models/UserData.php");

// The class contains methods for updating the current user
class User{

    protected $_dbInstance, $_dbHandle;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    // Check the given password and login
    public function login($email, $password) {
        $sqlQuery = 'SELECT * FROM users WHERE email = :email';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute(['email' => $email]); // execute the PDO statement
        $users = $statement->fetchAll();
        if (count($users) != 1 || !password_verify($password, $users[0]["password"])) {
            return;
        }
        $thisUser = $users[0];
        $_SESSION["id"] = $thisUser["id"];
        $_SESSION["username"] = $thisUser["username"];
    }

    // Logout
    public function logout() {
        session_destroy();
    }

    // Check if username is already in use any any user (other than the give $id)
    public function usernameExists($username, $id=0): bool
    {
        $sqlQuery = 'SELECT username FROM users WHERE username = ? AND id != ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username, $id]); // execute the PDO statement
        if (count($statement->fetchAll()) == 0) {
            return false;
        } else {
            return true;
        }
    }

    // Check if email is already in use any any user (other than the give $id)
    public function emailExists($email, $id=0): bool
    {
        $sqlQuery = 'SELECT email FROM users WHERE email = ? AND id != ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$email, $id]); // execute the PDO statement
        if (count($statement->fetchAll()) == 0) {
            return false;
        } else {
            return true;
        }
    }

    // Create a new user with the given details
    public function createUser($email, $username, $first_name, $last_name, $password) {
        $sqlQuery = 'INSERT INTO users (email, username, first_name, last_name, password) Values (?, ?, ?, ?, ?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $password = password_hash($password,  PASSWORD_DEFAULT);
        $statement->execute([$email, $username, $first_name, $last_name, $password]);
    }

    // Update he current users details
    public function updateUser($email, $username, $first_name, $last_name) {
        $sqlQuery = 'UPDATE users SET email = ?, username = ?, first_name = ?, last_name = ? WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$email, $username, $first_name, $last_name, $_SESSION["id"]]);
        $_SESSION["username"] = $username;
    }

    // Fetch the current user as UserData
    public function getCurrentUser() {
        $sqlQuery = 'SELECT id, email, first_name, last_name, username, profile FROM users WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$_SESSION["id"]]);
        return new UserData($statement->fetch());
    }

    // Confim and update the current users password
    public function updatePassword($old, $new): bool
    {
        $sqlQuery = 'SELECT * FROM users WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$_SESSION["id"]]);
        $users = $statement->fetchAll();
        if (!password_verify($old, $users[0]["password"])) {
            return false;
        }
        $sqlQuery = 'UPDATE users SET password = ? WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION["id"]]);
        return true;
    }

    // Remove the current users image
    public function removeImage() {
        $image = $this->getCurrentUser()->getImage();
        if ($image != 'images/profile/0.png') {
            unlink($image);
        }
        $sqlQuery = 'UPDATE users SET profile = false WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$_SESSION["id"]]);
    }

    // Add or update the current users image
    public function updateImage() {
        $sqlQuery = 'UPDATE users SET profile = true WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$_SESSION["id"]]);
    }
}


