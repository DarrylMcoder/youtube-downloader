<?PHP

session_start();  // Initialize the session

 //Get full URL of current page to pass to login page in ?next parameter
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php?next=".urlencode($full_url));
    exit;
}

//check if user had enough money in account
include('./config.php');
$phone = $_SESSION['phone'];
$sql = "SELECT amount_cents FROM credits WHERE phone = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $phone);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($amount_cents);
$stmt->fetch();

if($amount_cents <= 0){
  header("Location: nomoney.php");
  exit;
}

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