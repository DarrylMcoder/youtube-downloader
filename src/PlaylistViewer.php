<?PHP
    
namespace YouTube;

class PlaylistViewer {
  protected $ytconfig_preg = "#(?:var ytInitialData = (.*?);)#i";
  protected $client;
  protected $html;
  
  public function __construct(){
    $this->client = new Browser();
  }

  public function getYtconfig($url){
    $this->html = $this->client->get($url)->body;
    $html = $this->html;
    $preg = $this->ytconfig_preg;
    preg_match($preg,$html,$matches);
    return $matches;
  }
}