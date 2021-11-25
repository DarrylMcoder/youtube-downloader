<?PHP
    
namespace YouTube;
class VideoSaver extends YouTubeStreamer {
  
  protected $file_name;
  
  public function headerCallback($ch,$data){
    $len = parent::headerCallback($ch,$data);
    $name = $this->file_name;
    $this->sendHeader("Content-Disposition: attachment; filename=\"".$name."\"");
    return $len;
  }
  
  public function setDownloadedFileName($name){
    $this->file_name = $name;
  }
  
  public function download($url){
    $this->stream($url);
  }
}
    
?>
