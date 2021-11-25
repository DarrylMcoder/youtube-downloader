<?PHP

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 

set_time_limit(0);

require('../vendor/autoload.php');
    
$url = isset($_GET['url']) ? $_GET['url'] : null;

if(!isset($url)){
  die('No url provided!');
}
$crypto_streamer = new \YouTube\CryptoStreamer();
$crypto_streamer->stream($url);

?>