<?PHP
    
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

$formats = $links->getFirstCombinedFormat();

$name = $links->getInfo()->getTitle();

$vid_url = $formats->url;

$videoSaver->setDownloadedFileName($name);

$videoSaver->download($vid_url);

return $name;
}