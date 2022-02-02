<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $amount = $amount_cents = $phone = $sql = '';
  $amount_err = $phone_err = $sql_err = '';
  
  $amount = $_POST['amount'];
  $phone  = $_POST['phone'];
  
  if(!isset($amount)){
    $amount_err = 'Enter an amount';
  }
  $amount_cents = $amount * 100;
  if($amount_cents < 1000){
    $amount_err = 'Minimum of $10';
  }
  
  if(!isset($phone) || strlen($phone) < 10){
    $phone_err = 'Please enter a valid phone number';
  }
  
  include('./config.php');
  
  $sql = 'INSERT INTO credits (
  phone,
  amount_cents
)
VALUES (
  '.$phone.',
  '.$amount_cents.'
) 
ON DUPLICATE KEY UPDATE 
  amount_cents = amount_cents + '.$amount_cents.';';
  
  $mysqli->query($sql);
  $sql_err = $mysqli->error;
}

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://static.darrylmcoder.epizy.com/assets/style.css">
    <script src="http://static.darrylmcoder.epizy.com/assets/script.js"></script>
    <!--<link rel="stylesheet" href="style.css"/>-->
    <!--<script defer src="script.js"></script>-->
  </head>
  <body>
    <div class="content"><br>
      <div class="titletext">
        Add cash page
      </div>
      <br>
      <?php
if(!empty($sql_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
      ?>
      <form action="" method="post">
        <input type="number" class="form-control <?php echo isset($amount_err) ? 'is-invalid' : '' ?>" name="amount" placeholder="Amount in Canadian dollars">
        <div class="invalid-feedback">
          <?=$amount_err?>
        </div>
        <input type="tel" class="form-control <?php echo isset($phone_err) ? 'is-invalid' : '' ?>" name="phone" placeholder="Phone number of account">
        <div class="invalid-feedback">
          <?=$amount_err?>
        </div>
        
        <button type="submit"  class="go">
          Add to account
        </button>
      </form>
    </div>
  </body>
</html>
