<?PHP

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 

include('./config.php');
    
$str = 'CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);';

$link->query($str);