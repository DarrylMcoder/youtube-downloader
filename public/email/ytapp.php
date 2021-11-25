<?PHP
    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/../../src/PHPMailer/src/Exception.php');

require (__DIR__ . '/../../src/PHPMailer/src/PHPMailer.php');

require (__DIR__ . '/../../src/PHPMailer/src/SMTP.php');


require('../../vendor/autoload.php');
$imap_driver = new \YouTube\imap_driver();
$imap_driver->full_debug = true;
if ($imap_driver->init('ssl://imap.gmail.com', 993) === false) {
    echo "init() failed: " . $imap_driver->error . "\n";
    exit;
}

if ($imap_driver->login('darrylmcoder.ytapp@gmail.com', 'ytapp@ytapp') === false) {
    echo "login() failed: " . $imap_driver->error . "\n";
    exit;
}

if ($imap_driver->select_folder("INBOX") === false) {
    echo "select_folder() failed: " . $imap_driver->error . "\n";
    return false;
}

$ids = $imap_driver->get_uids_by_search('UNSEEN');
if ($ids === false)
{
    echo "get_uids_failed: " . $imap_driver->error . "\n";
    exit;
}

foreach($ids as $uid){
  $headers = $imap_driver->get_these_headers($uid, "FROM SUBJECT");
  if($headers === false){
    echo "get_these_headers() failed: " . $imap_driver->error. "\n";
    exit;
  }
  $imap_driver->get_headers_from_uid($uid);
  $imap_driver->close();
  $from = $headers['from'];
  echo "from: ".$from."\n";
  if(strpos($from, "<") !== false){
    $from_addr = get_string_between($from,"<",">");
  }else{
    $from_addr = $from;
  }
  echo "from_addr: $from_addr\n";
  $subj = $headers['subject'];
  $subj = iconv_mime_decode($subj);
  echo "subject_utf8: $subj";
  $url = $subj;
  
    $browser = new \YouTube\Browser();
    $page = $browser->get($url);
    $body = (!empty($page->body)) ? $page->body : "Empty email body.";
  
  if(strpos($url,"/watch")){
    $yt = new \YouTube\YouTubeDownloader();
    try{
      $links = $yt->getDownloadLinks($url);
    } catch(\YouTube\Exception\TooManyRequestsException $e){
      $fixie = getenv('FIXIE_URL');
      $yt->getBrowser()->setProxy($fixie);
      $links = $yt->getDownloadLinks($url);
    }
    $name = $links->getInfo()->getTitle();
    $best = $links->getFirstCombinedFormat();
    $video = $browser->get($best->url)->body;
    echo "\n";
    flush();
    $fp = fopen("video.mp4","w+");
    fwrite($fp,$video);
    fclose($fp);
    echo "\n";
    flush();
  }
  
  
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'darrylmcoder.ytapp@gmail.com';                     //SMTP username
    $mail->Password   = 'ytapp@ytapp';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('darrylmcoder.ytapp@gmail.com', 'Email Browser');
    $mail->addAddress($from_addr,"");     //Add a recipient
    
    //Attachments
    if(isset($video)){  
      //$mail->addStringAttachment($video,$name);
      $mail->addAttachment("video.mp4",$name.".mp4",PHPMailer::ENCODING_BASE64,"video/mp4");
    }

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Web Downloader';
    $mail->Body    = $body;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
  
  /*if(mail($from,"Email Browser",$page->body) === false){
    echo "mail() failed:";
    exit;
  }*/
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}