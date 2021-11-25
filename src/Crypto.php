<?PHP
    
namespace YouTube;
class Crypto{
  
  protected $template_path;

  protected $encryption_key = "WERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890Q";
  
  protected $encryption_base = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890";
  
  protected $encryption_function = "charReplace";
  
  public function __construct(){
    $this->template_path = "template.html";
  }
  
  public function setEncryptionKey($key){
    $this->encryption_key = $key;
  }
  
  public function encrypt($page){
    $function = $this->encryption_function;
    return $this->$function($page);
  }
  
  protected function charReplace($page){
    $alpha = $this->encryption_base;
    $key = $this->encryption_key;
    $page = str_split($page);
    $alpha = str_split($alpha);
    $key = str_split($key);
    $crypto_str = "";
    $found = false;

    foreach($page as $page_val){
      foreach($alpha as $alpha_key=>$alpha_val){
        if($alpha_val === $page_val){
          $crypto_str .= $key[$alpha_key];
          $found = true;
        }
      }
      if($found !== true){
        $crypto_str .= $page_val;
      }
      $found = false;
    }
    return $crypto_str;
  }
  
  public function wrapCode($str){
    $template_path = $this->template_path;
    $template_code = file_get_contents($template_path);
    $key = $this->encryption_key;
    $wrapped = preg_replace("#<\s?crypto(.*?)?>#i","<crypto$1 key=\"".$key."\">".$str,$template_code);
    return $wrapped;
  }
}
    
?>
