<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dbconn = paliolite();

session_start();

// Versi 32

if(isset($_GET['f_pin'])){
  $f_pin = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $f_pin;
}
else if(isset($_SESSION['user_f_pin'])){
  $f_pin = $_SESSION['user_f_pin'];
}

// Versi Operasional

// if(isset($_SESSION['user_f_pin'])){
//   $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_GET['f_pin'])){
//   $f_pin = $_GET['f_pin'];
// }

$rand_bg = rand(1, 12) . ".png";

// print_r($f_pin);

// QUERY
$query = $dbconn->prepare("SELECT * FROM KTA WHERE KTA.F_PIN = '$f_pin'");
$query->execute();
$exec = $query->get_result()->fetch_assoc();
$query->close();

// QUERY
$query = $dbconn->prepare("SELECT * FROM KIS WHERE KIS.F_PIN = '$f_pin'");
$query->execute();
$exec_kis = $query->get_result()->fetch_assoc();
$query->close();

if (!$exec){
  $exec['STATUS_ANGGOTA'] = 5;
}

// print_r($exec['STATUS_ANGGOTA']);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Membership</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
      <style>
        body {
          /* font-family: 'Poppins', sans-serif; */
        }
        .grid-container {
          display: grid;
          grid-template-columns: repeat(1, minmax(0, 1fr));
          padding: 10px;
        }
        .grid-item {
          background-color: #FFFFFF;
          border: 0px;
          padding: 10px;
          font-size: 20px;
          margin: 5px;
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
          /* background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>'); */
          /* background-size: 100% auto; */
          /* background-repeat: repeat-y; */
          /* background-image: url('../assets/img/slide2.png');
          background-repeat: no-repeat; */
          background-image: url('../assets/img/slide2.png');
          background-size: 100% 350px;
          background-repeat: no-repeat;
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

      <!-- Font Icon -->
      <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">
  </head>

  <body>

    <div class="row">
      <!-- <div class="col-2"></div>
      <div class="col-8 text-center">
        <div style="height: 35px; width: 100%; color: #4b4b4b; font-size: 12px; padding-top: 7px; background-color: #f1f1f1; border-bottom-left-radius: 60px; border-bottom-right-radius: 60px;"><b>LAYANAN KEANGGOTAAN</b></div>
      </div>
      <div class="col-2"></div>
    </div> -->

    <div class="row m-2 mt-4">
      <div class="col-12 text-center">
        <!-- <div class="row">
          <div class="col-12">
            <img id="logo-2" src="" style="width: 160px; height: auto; margin-top: 73px; position: absolute; margin-left: -27px">
          </div>
        </div> -->
        <div class="row mt-3">
          <div class="col-12">
            <img id="logo-2" src="" style="width: 130px; height: auto; margin-left: -195px;">
          </div>
          <div class="col-12">
            <img id="logo-1" src="" style="width: 130px; height: 160px; filter: drop-shadow(3px 0 0 #f1f1f1) drop-shadow(0 3px 0 #f1f1f1) drop-shadow(-3px 0 0 #f1f1f1) drop-shadow(0 -3px 0 #f1f1f1);">
          </div>
          <p class="mt-4 mb-2" style="color: white; font-weight: 700; font-size: 18px; margin-top: 20px; margin-bottom: -20px">View Membership</p>
        </div>
      </div>
    </div>
<!-- 
    <div class="grid-container">
      <div class="grid-item" id="kta-form">
        <img src="../assets/img/membership_card.png" >
        <h3>View KTA</h3>
      </div>
      <div class="grid-item" id="kis-form">
        <img src="../assets/img/membership_card.png" >
        <h3>View KIS</h3>
      </div>
    </div> -->

    <div style="background-color: #f1f1f1; padding-top: 50px; padding-bottom: 120px; position: relative">

      <div class="row">
        <b class="small-text text-center" style="margin-top: -10px">Lihat Kartu</b>
      </div>
      <div class="row gx-0">
          <div id="kta-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
              <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                  <div class="card-body text-center">
                      <img id="menu-1" src="../assets/img/GaspolUIandAnim_e/undraw-3.png" style="width: 150px; max-width: 100%">
                      <p><b style="font-size: 14px; color: dimgrey">View KTA</b></p>
                  </div>
              </div>
          </div>
          <div id="kis-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
              <div class="card shadow" style="width: 80%; height: 170px; border-radius: 20px;">
                  <div class="card-body text-center">
                      <img id="menu-2" src="../assets/img/GaspolUIandAnim_e/undraw-6.png" style="width: 150px; max-width: 100%">
                      <p><b style="font-size: 14px; color: dimgrey">View KIS</b></p>
                  </div>
              </div>
          </div>
      </div>

      <div class="row">
        <div class="col-12 d-flex justify-content-center mt-5 p-3">
          <img id="exit" onclick="closeAndroid()" src="" style="
            width: 120px;
            height: 45px;
            bottom: 75px;
            right: 20px;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;">
        </div>
      </div>
    </div>
    
    <!-- IF ELSE MODAL -->
    <div class="modal fade" id="kta-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-nokta" id="exampleModalLabel">Warning!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p class="no-kta-text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-nokta" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark button-nokta" onclick="registerKTA()">Register</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kis-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-nokta" id="exampleModalLabel">Warning!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p class="no-kis-text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-nokta" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark button-nokta" onclick="registerKIS()">Register</button>
                </div>
            </div>
        </div>
    </div>


    <!-- <div class="row">
      <div class="col-12 d-flex justify-content-center mt-5 p-3">
        <img id="exit" onclick="closeAndroid()" src="" style="
          width: 120px;
          height: 45px;
          bottom: 75px;
          right: 20px;
          color: #FFF;
          border-radius: 50px;
          text-align: center;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 20px;">
      </div> -->
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
      
      var F_PIN = "<?php echo $f_pin; ?>";
      
      // CARD KTA
      $("#kta-form").click(function (e) { 
        e.preventDefault();

        <?php

        if ($exec['STATUS_ANGGOTA'] == 0) {
          ?>
          window.location.href = "card-kta-mobility?f_pin=".concat(F_PIN);
          <?php
        }
        else if($exec['STATUS_ANGGOTA'] == 1) {
          ?>
          window.location.href = "card-kta-pronew?f_pin=".concat(F_PIN);
          <?php
        }

        else if($exec['STATUS_ANGGOTA'] == 5) {
          ?>
          $("#kta-modal").modal('show');
          <?php
        }
        ?>
        
      });
      // END OF CARD KTA

      $("#kis-form").click(function (e) { 
        e.preventDefault();

        // <?php
        // if ($exec_kis){
        //   ?>
        //   window.location.href = "card-kis?f_pin=".concat(F_PIN)
        //   <?php
        // }
        // else{
        //   ?>
        //   $("#kis-modal").modal('show');
        //   <?php
        // }
        // ?>


        <?php
        if (!isset($exec_kis)) {
          if ($exec['STATUS_ANGGOTA'] == 0 || $exec['STATUS_ANGGOTA'] == 1) {
            ?>
            $("#kis-modal").modal('show');
            <?php
          }

          else if($exec['STATUS_ANGGOTA'] == 5){
            ?>
            $("#kta-modal").modal('show');
            <?php
          }
        }

        else { 
          ?>
          window.location.href = "card-kis?f_pin=".concat(F_PIN)
          <?php
        }
      ?>  
      });

      function closeAndroid(){

        if (window.Android){

          window.Android.finishGaspolForm();

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

          window.webkit.messageHandlers.finishGaspolForm.postMessage({
            param1: ""
          });
          return;

        }else{

          history.back();

        }
      }

    </script>
  </body>
</html>

<script>

  if (localStorage.lang == 0){

    $('.modal-title-nokta').text('Warning!');
    $('.no-kta-text').text('You have not registered KTA. Please register KTA first!');
    $('.no-kis-text').text('You have not registered KIS. Please register KIS first!');
    $('.close-nokta').text('Close');
    $('.button-nokta').text('Register');

  }else if(localStorage.lang == 1){

    $('.modal-title-nokta').text('Perhatian!');
    $('.no-kta-text').text('Anda belum mendaftar KTA. Harap mengisi KTA terlebih dahulu!');
    $('.no-kis-text').text('Anda belum mendaftar KIS. Harap mengisi KIS terlebih dahulu!');
    $('.close-nokta').text('Tutup');
    $('.button-nokta').text('Registrasi');

  }else{

    $('.modal-title-nokta').text('Warning!');
    $('.no-kta-text').text('You have not registered KTA. Please register KTA first!');
    $('.no-kis-text').text('You have not registered KIS. Please register KIS first!');
    $('.close-nokta').text('Close');
    $('.button-nokta').text('Register');

  }

</script>

<script>

  function registerKTA(){

    $("#kta-modal").modal('hide');
    window.location.href = "form-kta-mobility.php?f_pin=".concat(F_PIN);
    
  }

  function registerKIS(){

    $("#kis-modal").modal('hide');
    window.location.href = "form-kis-new.php?f_pin=".concat(F_PIN);

  }

</script>

<script> 

var theme = 0;

if (window.Android) {
  theme = window.Android.getThemes();
  getThemes(theme);
}

function getThemes(number){

  // 0 = Blue 
  // 1 = Dark

  if (number == 0){
    $('#logo-1').attr('src','../assets/img/GaspolUIandAnim_e/fb_icon_e.png');
    $('#logo-2').attr('src','../assets/img/GaspolUIandAnim_e/member_off_fia_fim_blue_e.png');
    $('#exit').attr('src','../assets/img/GaspolUIandAnim_d/exit_only_red.png');
  }else if(number == 1){
    $('#logo-1').attr('src','../assets/img/GaspolUIandAnim_e/fb_Icon_black_e.png');
    $('#logo-2').attr('src','../assets/img/GaspolUIandAnim_e/member_off_fia_fim_black_e.png');
    $('#exit').attr('src','../assets/img/GaspolUIandAnim_d/exit_only_black.png');
  }

}

</script>