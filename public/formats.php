<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 

set_time_limit(0);

require('../vendor/autoload.php');
require('../src/functions.php');

crypt_enable("http://static.darrylmcoder.epizy.com/assets/loading.html");

$url = isset($_GET['url']) ? $_GET['url'] : null;

if(isset($_GET['crypt']) && $_GET['crypt'] == 'on'){
$url = base64_decode($url);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta charset="utf-8">
    <style>
      body, html {
        min-width: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
        font: Arial 14px;
      }
      
      #mainitem{
        background-color: white;
        border-radius:50px;
        color:black;
        vertical-align:center;
      }
    </style>
    <link rel="stylesheet" href="<?=getenv('ASSETS')?>/style.css"/>
    <script defer src="<?=getenv('ASSETS')?>/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//imap.darrylmcoder.epizy.com/analytics/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '3']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
    
  </head>
  <body>
    <?php echo file_get_contents(getenv('ASSETS')."/header.html"); ?>
    <div class="content"><br>
      <div class="pagetitle">
        Video Downloader
      </div>

<?php
     
$youtube = new \YouTube\YouTubeDownloader();
try{
  $links = $youtube->getDownloadLinks($url);
} catch(\YouTube\Exception\TooManyRequestsException $e){
  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser()->setProxy($fixie);
  $links = $youtube->getDownloadLinks($url);
}
  $formats = $links->getAllFormats();
  $info = $links->getInfo();
  $name = $info->getTitle();
  //$combined = $links->getFirstCombinedFormat();
      
  
  $vids = $links->getCombinedFormats();
  $mp4_vids = array_values( array_filter( array_values($vids), function($var){
  return (strpos($var->mimeType,'video/mp4') === 0 && $var->audioQuality != null);
}));
  $combined = $mp4_vids[0];
  
  $best = $links->getSplitFormats("high");
  $audios = $links->getAudioFormats();
  $best_audio = end(array_values( array_filter( array_values($audios), function($var){
  return strpos($var->mimeType,'audio/mp4') === 0;
})));
  echo "<img src='stream.php?url=".$info->videoDetails['thumbnail']['thumbnails'][0]['url']."' width='100%' ><br>";
echo "<h3>".$name."</h3><br>";

    ?>
      <a href="download.php?n=<?=urlencode($name)?>&url=<?=urlencode($combined->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download <?=$combined->qualityLabel?> video with audio: <?php echo round($combined->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a>
      <a href="download.php?n=<?=urlencode($name)?>&url=<?=urlencode($best_audio->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download highest quality audio: <?php echo round($best_audio->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a>
      <a href="download.php?n=<?=urlencode($name)?>&url=<?=urlencode($best->video->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download <?=$best->video->qualityLabel?> video: <?php echo round($formats[1]->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a><br>
      <div class="morebox">
       <!-- <button class="morebtn">
          Show full list
        </button> -->
        <h1>
          <br><b>All formats</b><br>
        </h1>
        <div>
      <?php
  foreach($formats as $key=>$format){
    echo"<div class='listitem'>";
    preg_match("#^(.*?);#i",$format->mimeType,$m);
    echo "<b>Type: ".$m[1]."<br>";
    if(isset($format->qualityLabel)){
      echo $format->qualityLabel." video<br> ";
    }else{
      echo " audio only<br>";
    }
    if(isset($format->audioQuality)){
      echo $format->audioQuality." <br>";
    }else{
      echo " No audio<br> ";
    }
    echo round($format->contentLength / 1000000,1)."mb</b><br>";
    
    echo"<a href='download.php?n=".urlencode($info->getTitle())."&url=".urlencode($format->url)."'><button class='go'>Download</button></a>";
    echo"</div>";
  }
?>
        </div>
      </div>

    </div>
    <?php echo file_get_contents(getenv('ASSETS')."/footer.html"); ?>
  </body>
</html>
