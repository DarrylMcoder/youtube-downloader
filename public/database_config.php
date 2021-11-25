<?PHP

$url = getenv('JAWSDB_URL');

$parts = parse_url($url);
    
define('DB_SERVER', $parts['host']);
define('DB_USERNAME', $parts['user']);
define('DB_PASSWORD', $parts['pass']);
define('DB_NAME', 'mqpklhdps7eegv9f');
 
/* Attempt to connect to MySQL database */
$mysqli = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}