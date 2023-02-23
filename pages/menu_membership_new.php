<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();

session_start();
// if(isset($_SESSION['user_f_pin'])){
//   $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_GET['f_pin'])){
  $f_pin = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $f_pin;
// }

// QUERY KTA
$query = $dbconn->prepare("SELECT * FROM KTA WHERE KTA.F_PIN = '$f_pin'");
$query->execute();
$ktainfo = $query->get_result()->fetch_assoc();
$query->close();

$rand_bg = rand(1, 12) . ".png";

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu Membership</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

      <style>
        body {
          font-family: 'Poppins', sans-serif;
        }
        .grid-container {
          display: grid;
          grid-template-columns: repeat(1, minmax(0, 1fr));
          padding: 10px;
        }
        .grid-item {
          background-color: #FFFFFF;
          border: 0px;
          /* padding: 10px; */
          height: 70px;
          padding-top: 10px;
          font-size: 20px;
          margin: 20px;
          text-align: center;
          border-radius: 15px;
          box-shadow: 2px 2px 4px black;
        }
        .grid-item img {
          max-width: 100px;
          height:auto;
        }
        * {
          box-sizing: border-box;
        }
        html {
          height: 100%;
          /* background-image: url("../assets/img/body-bg.jpeg"); */
          background-repeat: no-repeat;
          background-size: cover;
          background-attachment: fixed;
          -moz-background-size: cover;
          -webkit-background-size: cover;
          -o-background-size: cover;
          -ms-background-size: cover;
          background-position: center center;
        }
        body {
          /* background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
          background-size: 100% auto;
          background-repeat: repeat; */
        }

        /* FOR HTML NOT OFFSIDE */

		html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

      </style>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

      <!-- Font Icon -->
      <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">
  </head>

  <body style="background-color: #009be0">

    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <div style="height: 35px; width: 100%; color: #4b4b4b; font-size: 12px; padding-top: 7px; background-color: #f1f1f1; border-bottom-left-radius: 60px; border-bottom-right-radius: 60px;"><b>LAYANAN KEANGGOTAAN<b></div>
        </div>
        <div class="col-3"></div>
    </div>

    <div class="row m-4">
      <div class="col-12 text-center">
        <img src="../assets/img/imi-new.png" style="width: 220px; filter: drop-shadow(3px 0 0 #f1f1f1) drop-shadow(0 3px 0 #f1f1f1) drop-shadow(-3px 0 0 #f1f1f1) drop-shadow(0 -3px 0 #f1f1f1);">
        <p style="color: white; font-weight: 700; font-size: 18px; margin-top: 20px; margin-bottom: -20px">Registrasi Keanggotaan<br>IMI-mu Sekarang!</p>
      </div>
    </div>

    <div class="mt-5" style="background-color: #f1f1f1; border-top-left-radius: 60px; border-top-right-radius: 60px; padding-top: 50px; padding-bottom: 120px; position: relative">
        <div class="row gx-0">
            <b class="small-text text-center" style="margin-top: -10px">Pilih Layanan</b>
            <img onclick="closeAndroid()" src="../assets/img/close.png" style="width: 40px; height: 40px; right: 0; position:absolute; margin-right: 25px; margin-top: -20px">

            <div id="kta-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-1.png" style="width: 150px">
                        <p><b style="font-size: 12px; color: dimgrey">KTA Mobility</b></p>
                    </div>
                </div>
            </div>
            <div id="upgrade-kta-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-2.png" style="width: 150px; margin-top: 15px">
                        <p><b style="font-size: 12px; color: dimgrey">KTA Pro</b></p>
                    </div>
                </div>
            </div>
            <div id="kis-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-3.png" style="width: 150px; margin-top: 5px; margin-bottom: 5px">
                        <p><b style="font-size: 12px; color: dimgrey">Kartu Ijin Start</b></p>
                    </div>
                </div>
            </div>
            <div id="tkt-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-5.png" style="width: 150px; margin-top: 10px">
                        <p><b style="font-size: 12px; color: dimgrey">Gaspol Club</b></p>
                    </div>
                </div>
            </div>
            <div id="tkt-imi-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-4.png" style="width: 150px; height: 70%;">
                        <p><b style="font-size: 12px; color: dimgrey">IMI Club</b></p>
                    </div>
                </div>
            </div>
            <div id="taa-form" class="col-6 mt-4 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="../assets/img/undraw-6.png" style="width: 150px">
                        <p><b style="font-size: 12px; color: dimgrey">TAA Club</b></p>
                    </div>
                </div>
            </div>
        </div>

      <!-- <img onclick="closeAndroid()" src="../assets/img/close.png" style="position: fixed;
        width: 60px;
        height: 60px;
        bottom: 75px;
        right: 20px;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;"> -->
    </div>

    <!-- IF ELSE MODAL -->
    <div class="modal fade" id="kta-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title-nokta" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="no-kta-text"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-nokta" data-bs-dismiss="modal"></button>
            <a href="form-kta-mobility.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark button-nokta"></button></a>
          </div>
        </div>
      </div>
    </div>

    <!-- <div style="width: 100%; height: 125px; background-color: transparent"> -->

    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
      var F_PIN = "<?php echo $f_pin; ?>";

      // var F_PIN = '';
      // if (window.Android) {
      //   F_PIN = window.Android.getFPin();
      // } else {
      //   F_PIN = "<?php //echo $f_pin; ?>";
      // }

      $("#tkt-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "form-tkt-gaspol?f_pin=".concat(F_PIN)
      });

      $("#tkt-imi-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "tkt-imi-club?f_pin=".concat(F_PIN)
      });
      
      $("#kta-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "form-kta-mobility?f_pin=".concat(F_PIN)
      });
      $("#upgrade-kta-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "form-kta-pronew?f_pin=".concat(F_PIN)
      });
      $("#kis-form").click(function (e) { 
        e.preventDefault();

        <?php
        if ($ktainfo) {
          ?>
          window.location.href = "form-kis-new?f_pin=".concat(F_PIN);
          <?php  
        }

        else {
          ?>
          $("#kta-modal").modal('show');
          <?php
        }
        ?>

      });
      $("#taa-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "taa-club?f_pin=".concat(F_PIN)
      });

      function closeAndroid(){

        if (window.Android){
          window.Android.finishGaspolForm();
        }else{
          history.back();
        }
      }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

  if (localStorage.lang == 0){

    $('.modal-title-nokta').text('Warning!');
    $('.no-kta-text').text('You have not registered KTA. Please register KTA first!');
    $('.close-nokta').text('Close');
    $('.button-nokta').text('Register');

  }else if(localStorage.lang == 1){

    $('.modal-title-nokta').text('Perhatian!');
    $('.no-kta-text').text('Anda belum mendaftar KTA. Harap mengisi KTA terlebih dahulu!');
    $('.close-nokta').text('Tutup');
    $('.button-nokta').text('Registrasi');

  }

</script>