<?PHP
    
require('../../vendor/autoload.php');

$term = isset($_GET['term']) ? $_GET['term'] : null;

$yt = new \YouTube\YouTubeDownloader();
$json = $yt->getSearchSuggestions($term);
echo json_encode($json);