<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 

session_start();  // Initialize the session

 //Get full URL of current page to pass to login page in ?next parameter
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php?next=".$full_url);
    exit;
}

set_time_limit(0);

require('../vendor/autoload.php');

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

function download($url,$youtube,$videoSaver){

$links = $youtube->getDownloadLinks($url);
  
  $vids = $links->getCombinedFormats();
  $mp4_vids = array_values( array_filter( array_values($vids), function($var){
  return (strpos($var->mimeType,'video/mp4') === 0 && $var->audioQuality != null);
}));
  

//$formats = $links->getFirstCombinedFormat();

$name = $links->getInfo()->getTitle();

$vid_url = $mp4_vids[0]->url;

$videoSaver->setDownloadedFileName($name);

$videoSaver->download($vid_url);

return $name;
}