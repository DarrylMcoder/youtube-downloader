<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$creds = parse_url(getenv('JAWSDB_URL'));
define('DB_SERVER', $creds['host']);
define('DB_USERNAME', $creds['user']);
define('DB_PASSWORD', $creds['pass']);
define('DB_NAME', ltrim($creds['path'], '/'));
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>