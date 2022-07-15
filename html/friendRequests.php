<?php

session_start();

// Anti spam
if (isset($_POST["url"]) && $_POST["url"] != '') {
    die();
}

// Ensure user is logged in
if (!isset($_SESSION["id"])) {
    header('Location: login.php');
    exit();
}
require_once ("Models/FriendsDataSet.php");
require_once ("Models/FriendData.php");
require_once ("Models/SearchParams.php");
require_once ("Models/Pagination.php");

// Create the $view variable and pagination height and search statement
$view = new stdClass();
if (isset($_GET["submit"])) {
    $view->searchStatement = $_GET["search"];
    $height = $_GET["height"];
} else {
    $view->searchStatement = "";
    $height = 10;
}

$pagination = new Pagination();

// Search for friend requests matching the search statement (all friend requests if empty)
$friendDataSet = new FriendsDataSet();
$data = $friendDataSet->searchUsers($view->searchStatement, (new SearchParams())->sort(), $pagination->getCurrent(), $height, "Pending");
$view->userData = $data["data"];

// Set number of pages for pagination
$pagination->set($data["count"], $height);

require_once ("Views/friendRequests.phtml");
