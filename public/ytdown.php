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