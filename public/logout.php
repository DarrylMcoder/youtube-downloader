<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 
set_time_limit(0);
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>