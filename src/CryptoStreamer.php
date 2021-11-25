<?PHP
    
namespace YouTube;
class CryptoStreamer extends \YouTube\YouTubeStreamer{
  
  protected $body;

  public function __construct(){
    $this->crypto = new Crypto();
  }
  public function bodycallback($ch,$data){
    if(true){
      $this->body .= $data;
    }
    return strlen($data);
  }
  
  public function parseAndSend(){
    echo $this->crypto->encrypt("\n\nPARSEandSEND\n\n");
    $data = $this->body;
    $that = $this;
    $data = preg_replace_callback( "#((?:src|(?<!a )href|action|data)\s?=\s?)(\"|')(.*?)\2#i",array($this,'proxify'), $data);
    $data = $this->crypto->encrypt($data);
    if(true){
      echo $data;
      flush();
    }
  }
 
  public function stream($url){
    if(parent::stream($url) === true){
      echo $this->crypto->encrypt("\n\nSTREAM\n\n");
      $this->parseAndSend();
      return true;
    }
  }
  
  public function proxify($matches){
    echo $this->crypto->encrypt("\n\nPROXIFY\n\n");
    $abs_url = is_abs($matches[3]) ? $matches[3] : absify($matches[3],$this->base());
    $url = "https://ytapp.backup.darrylmcoder.epizy.com";
    $url.= "/stream.php?url=";
    $url.= urlencode($abs_url);
    $return = $matches[1].$matches[2].$url.$matches[2];
    return $return;
  }
  
  
  
  protected function base(){
    echo $this->crypto->encrypt("\n\nBASE\n\n");
    $url = $this->url;
    $file_info = pathinfo($url);
    return isset($file_info['extension'])
        ? str_replace($file_info['filename'] . "." . $file_info['extension'], "", $url)
        : $url;
  }
}

?>