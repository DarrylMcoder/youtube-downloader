<!DOCTYPE html>
<html>
<head>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta charset="UTF-8">
    <title>YouTube Downloader</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="http://static.darrylmcoder.epizy.com/assets/style.css">
  <link rel="apple-touch-icon"
      href="img/apple-touch-icon.png" />
  <style>
    img{
      border:solid black 1px;
    }
    
  </style>
</head>
<body>
  <?php  echo file_get_contents('http://static.darrylmcoder.epizy.com/assets/header.html'); ?>
  <div class="content">
    <div class="pagetitle">
      Video Downloader
    </div>
    <a href="https://t.me/joinchat/zGTCgHpvKN9iMWYx">
      <img src="stream.php?url=http://ytapp.darrylmcoder.epizy.com/img/telegram.png" width="50" height="50"/><img src="stream.php?url=http://ytapp.darrylmcoder.epizy.com/img/cloudveil.png" width="50" height="50"><br>
    Join our Telegram group!
    </a><br><br>
    <form action="formats.php" method="get">
  <input type="text" value= "<?=$_GET['url']; ?>" class="input" id="txt_url" name="url"><br>
  <button type="button" class="go" id="btn_fetch">
  Watch
  </button>
  <button type="submit" class="go" formaction="ytdown.php">
    Download
  </button><br><br>
  <button type="submit" class="go">
    More Options
  </button>
</form>
    <h3 id="name">
      
    </h3>

  <video width="100%" controls>
    <source src="" type="video/mp4"/>
    <em>Sorry, your browser doesn't support HTML5 video.</em>
</video>
    
    <p id="description">
      
    </p>


<script>
    $(function () {

        $("#btn_fetch").click(function () {

            var url = $("#txt_url").val();

            var oThis = $(this);
            oThis.attr('disabled', true);

            $.get('video_info.php', {url: url}, function (data) {

                console.log(data);

                oThis.attr('disabled', false);

                var links = data['links'];
                var error = data['error'];
                var name = data['name'];
                var description = data['description'];
              
                $("#name").html(name);
                $("#description").html(description);

                if (error) {
                    alert('Error: ' + error);
                    return;
                }

                // first link with video
                var first = links[0];

                if (typeof first === 'undefined') {
                    alert('No video found!');
                    return;
                }

                var stream_url = 'stream.php?url=' + encodeURIComponent(first);

                var video = $("video");
                video.attr('src', stream_url);
                video[0].load();
               var today = new Date();
          var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
          var time = today.getHours() + ":" + today.getMinutes();
          var dateTime = date+' '+time;
          
          
              $.post("log.php",{
                download_date: dateTime,
                name: name,
                url: url
              },function(data) {
                //process result here
              });
            });

        });

    });
  
  function isPlaylist(url){
    if(url.includes('/playlist')){
      return true;
    }else{
      return false;
    }
  }
  
    
  function isVideo(url){
    if(url.includes('/watch')){
      return true;
    }else{
      return false;
    }
  }
</script>
</div>
  <?php echo file_get_contents('http://static.darrylmcoder.epizy.com/assets/footer.html'); ?>
</body>
</html>
