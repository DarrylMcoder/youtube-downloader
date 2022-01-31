<?PHP

error_reporting(E_ALL);
ini_set('display_errors', '1');

    
include('./config.php');


$sql = 'CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone BIGINT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);';

//$sql = "DROP TABLE users";

$mysqli->query($sql);
echo $mysqli->error;