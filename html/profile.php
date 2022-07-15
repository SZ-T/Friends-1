<?php

session_start();
require_once ("Models/User.php");

// Ensure user is logged in
if (!isset($_SESSION["id"])) {
    header('Location: login.php');
    exit();
}

$view = new stdClass();

// If user wants to update their profile information
if (isset($_POST["profile"])) {
    $error = new stdClass();
    $error->email = $_POST["email"];
    $error->username = $_POST["username"];
    $error->firstName = $_POST["first_name"];
    $error->lastName = $_POST["last_name"];
    $user = new User();
    // Check new email is not already in use
    if ($user->emailExists($error->email, $_SESSION["id"])) {
        $error->errorMessage = 'An account already exists with that email address.';
    } elseif ($user->usernameExists($error->username, $_SESSION["id"])) {
        // Check new username is not already in use
        $error->errorMessage = 'An account already exists with that username.';
    } else {
        // If success, show success message
        $user->updateUser($error->email, $error->username, $error->firstName, $error->lastName);
        $view->message = 'Your details have been updated successfully';
    }
}

// If user wants to update their password
if (isset($_POST["password"])) {
    $error = new stdClass();
    // Check passwords match
    if ($_POST["password1"] != $_POST["password2"]) {
        $error->errorMessage = 'Your passwords do not match.';
    } elseif (!(new User())->updatePassword($_POST["oldPassword"], $_POST["password1"])) {
        $error->errorMessage = 'Your current password is incorrect.';
    } else {
        $view->message = 'Your password has been updated successfully';
    }
}

// If user wants to update or add an image
if (isset($_POST["image"])) {
    (new User())->updateImage();
    $input = $_POST["file"];
    $output = 'images/profile/' . $_SESSION["id"] . '.png';
    file_put_contents($output, file_get_contents($input));
    $view->message = 'Your image has been updated';
}

// If user wants to remove their image
if (isset($_GET["action"]) && $_GET["action"] == 'removeImage') {
    (new User())->removeImage();
    $view->message = 'Your image has been updated';
    header('Location: profile.php');
    exit();
}

$view->user = (new User())->getCurrentUser();

require_once ("Views/profile.phtml");