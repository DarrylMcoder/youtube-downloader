<?PHP

session_start();  // Initialize the session

 //Get full URL of current page to pass to login page in ?next parameter
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 
/*/
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php?next=".urlencode($full_url));
    exit;
}


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
  header("Location: account.php");
  exit;
}

$video_price = getenv("VIDEO_PRICE");
$phone = $_SESSION['phone'];
$sql = "UPDATE credits 
SET amount_cents = amount_cents - ? WHERE phone = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $video_price, $phone);
$stmt->execute();
//*/

set_time_limit(0);

require('../vendor/autoload.php');
include('database_config.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($url == false) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();

$videoSaver = new \YouTube\VideoSaver();

try{
 download($url,$youtube,$videoSaver);
} catch(YouTube\Exception\TooManyRequestsException $e){
 
  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser()->setProxy($fixie);
  download($url,$youtube,$videoSaver);
} catch(Exception $e){
  echo $e->getMessage();
}

function logVid($download_date,$name,$url){
  date_default_timezone_set('EST');
  if(!isset($download_date,$name,$url)){
    throw new Exception("Log params missing!");
  }
  $sql = "INSERT INTO Videos(
download_date,name,downloads,url) 
VALUES('$download_date','$name',1,'$url') ON DUPLICATE KEY UPDATE downloads = downloads +  1,download_date = '$download_date'";

  if(!$mysqli->query($sql)){
    echo $sql."<br>";
    echo $mysqli->error;
  }
}

function download($url,$youtube,$videoSaver){

$links = $youtube->getDownloadLinks($url);
  
  $vids = $links->getCombinedFormats();
  $mp4_vids = array_values( array_filter( array_values($vids), function($var){
  return (strpos($var->mimeType,'video/mp4') === 0 && $var->audioQuality != null);
}));
  

//$formats = $links->getFirstCombinedFormat();

$name = $links->getInfo()->getTitle();

$download_date = new Date("Y-m-d h:i:s");
logVid($download_date, $name, $url);

$vid_url = $mp4_vids[0]->url;

$videoSaver->setDownloadedFileName($name);

$videoSaver->download($vid_url);

return $name;
}