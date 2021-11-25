<?PHP

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 
set_time_limit(0);

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if(isset($_GET['crypt']) && $_GET['crypt'] == 'on'){
$url = base64_decode($url);
}

if ($url == false) {
    die("No url provided");
}

$name = isset($_GET['n']) ? $_GET['n'] : "Unnamed file downloaded from ".$url;

$downloader = new \YouTube\VideoSaver();
$downloader->setDownloadedFileName($name);
$downloader->download($url);