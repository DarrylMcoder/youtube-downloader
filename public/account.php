<?php
  

session_start();  // Initialize the session

 //Get full URL of current page to pass to login page in ?next parameter
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php?next=".urlencode($full_url));
    exit;
}


include('./config.php');
$phone = $_SESSION['phone'];
$sql = "SELECT amount_cents FROM credits WHERE phone = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $phone);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($amount_cents);
$stmt->fetch();
if($amount_cents <= 0){
  $money_msg = "<div class='alert-danger'>Your account has no money in it. Please add money to your account using one of the following methods before watching more videos.</div>";
}elseif($amount_cents < 50){
  $money_msg = "<div class='alert-warning'>You have less than 50&#162; in your account. <br> <a href='account.php'>Add more?</a></div>";
}
$amount = $amount_cents / 100;
$username = $_SESSION['username'];

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
    </style>
    <link rel="stylesheet" href="http://static.darrylmcoder.epizy.com/assets/style.css">
    <script type="text/javascript" src="http://static.darrylmcoder.epizy.com/assets/script.js"></script>
    <!--<link rel="stylesheet" href="style.css"/>-->
    <!--<script defer src="script.js"></script>-->
  </head>
  <body>
    <?php echo file_get_contents('http://static.darrylmcoder.epizy.com/assets/header.html'); ?>
    <div class="content">
      <div class="pagetitle">
        My Account
      </div>
      <?=$money_msg?>
      <h1>
        Hi, <?=$username?>, welcome to your account. Your current balance is $<?=$amount?>.
      </h1>
      <br>
      <h3>
        <p>
          You can use one of the methods below to add money to your account.
        </p>
      </h3>
            <div class="opts">
        The usual way - Give me a minimum of $10.00 cash along with your phone number.
      </div>
      <br>
      <div class="opts">
        Buy an Amazon gift card and send me the activation code on it. You can text it to me or email me at <a href="mailto:darryl9829@gmail.com">darryl9829@gmail.com</a>.
      </div>
      <br>
      <div class="opts">
        That's all the options for now. More may be added later.
      </div>
    </div>
    <?php  echo file_get_contents('http://static.darrylmcoder.epizy.com/assets/footer.html'); ?>
  </body>
</html>
