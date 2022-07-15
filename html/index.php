<?php

session_start();

require_once ("Models/SearchParams.php");
require_once ("Models/Pagination.php");
require_once ("Models/UserData.php");

// Anti spam
if (isset($_POST["url"]) && $_POST["url"] != '') {
    die();
}

// Create the $view variable and pagination height and search statement
$view = new stdClass();
if (isset($_GET["submit"])) {
    $view->searchStatement = $_GET["search"];
} else {
    $view->searchStatement = "";
}

if (isset($_GET["height"])) {
    $height = $_GET["height"];
} else {
    $height = 10;
}

// Set up the pagination variables
$pagination = new Pagination();

// Import relevant user type models depending on whether user is logged in
if (isset($_SESSION["id"])) {
    require_once ("Models/FriendsDataSet.php");
    require_once ("Models/FriendData.php");
    $userDataSet = new FriendsDataSet();
} else {
    require_once ("Models/UserDataSet.php");
    $userDataSet = new UserDataSet();
}
$sort = 
// Search for users matching the search statement (all users if empty)
$data = $userDataSet->searchUsers($view->searchStatement, (new SearchParams())->sort(), $pagination->getCurrent(), $height);
$view->userData = $data["data"];

// Set number of pages for pagination
$pagination->set($data["count"], $height);

require_once("Views/home.phtml");