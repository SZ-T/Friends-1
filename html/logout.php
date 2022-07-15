<?php

session_start();
session_destroy();

// Logout and redirect to index page
header('Location: index.php');
exit();