
<?php

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

function send_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

if (!$url) {
    send_json([
        'error' => 'No URL provided!'
    ]);
}

$youtube = new \YouTube\YouTubeDownloader();

try {
    
  sendCombinedFormats($youtube,$url);
  
} catch (\YouTube\Exception\TooManyRequestsException $e)
{

  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser()->setProxy($fixie);
  sendCombinedFormats($youtube,$url);

} catch (YouTube\Exception\YouTubeException $e) {

    send_json([
        'error' => $e->getMessage()
    ]);
} 

//functions

function sendCombinedFormats($youtube,$url){
  
  $links = $youtube->getDownloadLinks($url);

  //$best = $links->getFirstCombinedFormat();
  
  
  $vids = $links->getCombinedFormats();
  $mp4_vids = array_values( array_filter( array_values($vids), function($var){
  return (strpos($var->mimeType,'video/mp4') === 0 && $var->audioQuality != null);
}));
  
  $best = $mp4_vids[0];
  
  $info = $links->getInfo();
  
  $name = $info->getTitle();
  
  $description = $info->getShortDescription();
  
  $files = [];

  if ($best) {
    
    send_json([
      'links' => [$best->url],
      'name'  => [$name],
      'description' => [$description],
    ]);
  } else {
        send_json(['error' => 'No links found']);
  }

}
