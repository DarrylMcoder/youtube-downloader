<?PHP
    
include('database_config.php');

$download_date = isset($_POST['download_date']) ? $_POST['download_date'] : null;

$name = isset($_POST['name'][0]) ? $_POST['name'][0] : null;

$url = isset($_POST['url']) ? $_POST['url'] : null;

if(!isset($download_date,$name,$url)){
  throw new Exception("Log params missing!");
}

$sql = "CREATE TABLE IF NOT EXISTS Videos(
  ID INT AUTO_INCREMENT,
  download_date VARCHAR(255),
  name VARCHAR(255) UNIQUE,
  downloads INT,
  url VARCHAR(255) UNIQUE,
  PRIMARY KEY(ID)
);";

if(!$mysqli->query($sql)){
  echo $sql."<br>";
  echo $mysqli->error;
}

$sql = "INSERT INTO Videos(
download_date,name,downloads,url) 
VALUES('$download_date','$name',1,'$url') ON DUPLICATE KEY UPDATE downloads = downloads + 1,download_date = '$download_date'";

if(!$mysqli->query($sql)){
  echo $sql."<br>";
  echo $mysqli->error;
}