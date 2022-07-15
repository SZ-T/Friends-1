<?php

session_start();
require_once ("Models/User.php");

// Anti spam
if (isset($_POST["url"]) && $_POST["url"] != '') {
    die();
}

// If user tries to login
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new User();
    $user->login($email, $password);

    // If login failed, return page with error message
    if (!isset($_SESSION["id"])) {
        $view = new stdClass();
        $view->errorColour = 'danger';
        $view->errorType = 'login';
        $view->errorMessage = 'The username or password is incorrect.';
        $view->email = $email;
    } else {
        // If success, redirect to index page
        header('Location: index.php');
        exit();
    }
}

// If user tries to sign up
if (isset($_POST["signUp"])) {
    $view = new stdClass();
    $view->email = $_POST["email"];
    $view->username = $_POST["username"];
    $view->first_name = $_POST["first_name"];
    $view->last_name = $_POST["last_name"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $user = new User();

    // Check passwords match
    if ($password != $password2) {
        $view->errorColour = 'danger';
        $view->errorType = 'signUp';
        $view->errorMessage = 'Your passwords do not match.';
    } elseif ($user->emailExists($view->email)) {
        // Check email is not already in use
        $view->errorColour = 'danger';
        $view->errorType = 'signUp';
        $view->errorMessage = 'An account already exists with that email address.';
    } elseif ($user->usernameExists($view->username)) {
        // Check username is not already in use
        $view->errorColour = 'danger';
        $view->errorType = 'signUp';
        $view->errorMessage = 'An account already exists with that username.';
    } else {
        // If success, show success message
        $user->createUser($view->email, $view->username, $view->first_name, $view->last_name, $password);
        $view->errorColour = 'success';
        $view->errorMessage = "Your user has been created successfully";
    }
}

require_once("Views/login.phtml");