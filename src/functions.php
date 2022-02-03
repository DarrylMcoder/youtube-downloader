<?PHP
    
function is_abs($url){
  return (strpos($url,"http") < 5);
}

function absify($url,$abs){
  if(strpos($url,"http") < 5){
    return $url;
  }elseif(str_starts_with($url,"//")){
    return "https:".$url;
  }else{
    return $abs.ltrim($url,"/");
  }
}

function crypt_enable($loadingURL, $callback = null){
  if(isset($_GET["crypt_enabled"]) && $_GET["crypt_enabled"] === "on"){
    //javascript request
    $c = curl_init(getURL()."?crypt_enabled=off");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($c);
    $crypto = new \YouTube\Crypto();
    $result = str_replace("</body>","<script type='text/javascript'>window.dispatchEvent( new Event('load'));</script></body>",$result);
    echo $crypto->encrypt($result);
    exit;
    
  }elseif(isset($_GET["crypt_enabled"]) && $_GET["crypt_enabled"] === "off"){
    //encoding proxy request
    return;
  }elseif(!isset($_GET["crypt_enabled"])){
    //new client request.
    
    //run second parameter function which may contain authentication functionality and such
    if($callback !== null){
      $callback();
    }
    
    //Send javascript encoding functions
    $loadingPage = file_get_contents($loadingURL);
    $loadingPage = str_replace("</body>","<script type=\"text/javascript\">
     window.onload = (function() {
        var x = new XMLHttpRequest();
        if(location.href.includes(\"?\")){
          var q = \"&crypt_enabled=on\";
        }else{
          var q = \"?crypt_enabled=on\";
        }
        x.open(\"GET\",location.href+q);
        
        x.onreadystatechange = function() {
          if(x.readyState === 4) {
            if(x.status !== 200) {
              alert(x.status);
            }
          document.write( decrypt( x.responseText,\"WERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890Q\"));
            
          }
        };
        x.send();
        
        function decrypt(crypted,key) {
        var alpha = \"QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890\";
        var decrypted_str = \"\";
        var found = false;
        for(var i = 0; i < crypted.length; i++) {
          var crypted_val = crypted.charAt(i);
          for(var j = 0; j < key.length; j++){
            var key_val = key.charAt(j);
            var alpha_val = alpha.charAt(j);
            if(key_val == crypted_val) {
              decrypted_str += alpha_val;
              found = true;
            }
          }
          if(found != true) {
            decrypted_str += crypted_val;
          }
          found = false;
        }
        
        return decrypted_str;
      }
      })
    </script>
 </body>",$loadingPage);
    echo $loadingPage;
    exit;
  }
}


function getURL(){
  if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on")   
    $url = "https://";   
  else  
    $url = "http://";   
  // Append the host(domain name, ip) to the URL.   
  $url.= $_SERVER["HTTP_HOST"];   
    
  // Append the requested resource location to the URL   
  $url.= $_SERVER["REQUEST_URI"];
  return $url;
}