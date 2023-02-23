<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

session_start();

if(isset($_GET['f_pin'])){
  $f_pin = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $f_pin;
}
else if(isset($_SESSION['user_f_pin'])){
  $f_pin = $_SESSION['user_f_pin'];
}

// GET USER INFO

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
$query->execute();
$userData = $query->get_result()->fetch_assoc();
$query->close();

// CHECK KTA

$query = $dbconn->prepare("SELECT * FROM KTA LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA =  REGISTRATION_PAYMENT.REF_ID WHERE KTA.F_PIN = '$f_pin'");
$query->execute();
$checkKTA = $query->get_result()->fetch_assoc();
$query->close();

if (isset($checkKTA)){

  if ($checkKTA['STATUS_ANGGOTA'] == 0){
    $statusKTA = 0;
  }else{
    $statusKTA = 1;
  }

}

// CHECK KIS

$query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU =  REGISTRATION_PAYMENT.REF_ID WHERE KIS.F_PIN = '$f_pin'");
$query->execute();
$checkKIS = $query->get_result()->fetch_assoc();
$query->close();

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

    /* FOR HTML NOT OFFSIDE */

    html,
    body {
      max-width: 100%;
      overflow-x: hidden;
      font-family: 'Poppins' !important;
    }

    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

  </head>

  <body>

  <div class="fixed-top">

    <div class="section-menu bg-white shadow">
        <div class="row p-3">
            <div class="col-2">
                <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
            </div>
            <div class="col-10" style="padding-top: 5px">
                <b>Create Club</b>
            </div>
        </div>
        <div class="slide-menu" style="height: 75px">
          <div class="row" style="height: 100%">
            <div class="col-6 text-center pt-3" onclick="tktMasyarakat()">
              <img id="basic-off" src="../assets/img/social/level=Gaspol, state=off.png" style="width: 40px; height: 40px; margin-top: -3px; !important" class="mt-2">
              <img id="basic-on" src="../assets/img/social/level=Gaspol, state=on.svg" style="width: 40px; height: 40px; margin-top: -3px; !important" class="mt-2 d-none">
              <!-- <b style="font-size: 13px">Gaspol Community</b> -->
            </div>
            <div class="col-6 text-center pt-3" onclick="tktIMI()">
              <img id="tkt-off" src="../assets/img/social/level=IMI, state=off.png" style="width: 40px; height: 40px; margin-top: -3px; !important" class="mt-2">
              <img id="tkt-on" src="../assets/img/social/level=IMI, state=on.svg" style="width: 40px; height: 40px; margin-top: -3px; !important" class="mt-2 d-none">
              <!-- <b style="font-size: 13px">IMI TKT</b> -->
            </div>
        </div>
      </div>
    </div>

    <div id="tktMasyarakat" class="slide-menu pt-3 px-4" style="margin-top: 23px">

    <!-- <div class="gx-auto" style="background-color: #9aabec; border-radius: 20px; font-size: 11px; padding: 5px; width: 60px; padding-left: 17px; margin-bottom: 11px; color: #0d55dc">Aktif</div> -->

      <b style="font-size: 14px">Gaspol Community</b>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Current Benefits</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/follower.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Member Capacity</b><br />
            <small style="font-size: 11px; color: #646464">Up to 50 Gaspol user as members</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/support.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Assemble Gaspol Users</b><br />
            <small style="font-size: 11px; color: #646464">Meet people with the same passion</small>
          </div>
        </div>
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Requirements</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=default.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Fill out registration forms</b><br />
            <small style="font-size: 11px; color: #646464">Prepare your ID and upload them</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/money.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Free Payment Fee</b><br />
            <small style="font-size: 11px; color: #646464">No admission money required</small>
          </div>
        </div>
      </div>
    </div>
    <div id="tktMasyarakatButton" class="bg-white p-2 fixed-bottom">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="registerMasyarakat()"><b>CREATE</b></button>
      </div>
    </div>
    </div>

    <div id="tktIMI" class="slide-menu pt-3 px-4 d-none" style="margin-top: 145px; margin-bottom: 100px">

    <!-- <div class="gx-auto" style="background-color: #9aabec; border-radius: 20px; font-size: 11px; padding: 5px; width: 60px; padding-left: 17px; margin-bottom: 11px; color: #0d55dc">Aktif</div> -->
      
      <b style="font-size: 14px">TKT IMI</b>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Current Benefits</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/official.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Official IMI TKT</b><br />
            <small style="font-size: 11px; color: #646464">Verified officialy as IMI TKT Indonesia</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/follower.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Unlimited Member</b><br />
            <small style="font-size: 11px; color: #646464">Assemble your members without worries</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/directory.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Club Management</b><br />
            <small style="font-size: 11px; color: #646464">Easily manage your club and member approval</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/events.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Create Event</b><br />
            <small style="font-size: 11px; color: #646464">Able to hold a club event (Jambore, touring and others)</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=line.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Voting Rights</b><br />
            <small style="font-size: 11px; color: #646464">Entitled to vote in IMI national events</small>
          </div>
        </div>
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Requirements</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/member.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">IMI KTA Membership</b><br />
            <small style="font-size: 11px; color: #646464">Minimal of 3 KTA members inside the club</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=default.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Fill out registration forms</b><br />
            <small style="font-size: 11px; color: #646464">Prepare your ID and upload them</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/money.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Pay Rp 200.000 fee</b><br />
            <small style="font-size: 11px; color: #646464">Admission and registration fee</small>
          </div>
        </div>
      </div>
    </div>
    <div id="tktIMIButton" class="bg-white p-2 fixed-bottom d-none">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="registerIMI()"><b>CREATE</b></button>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

  var F_PIN = "<?= $f_pin ?>";

  $('#basic-off').addClass('d-none');
  $('#basic-on').removeClass('d-none');

  function tktMasyarakat(){

    $('#tktMasyarakat').removeClass('d-none');

    $('#tktIMI').addClass('d-none');
    $('#tktIMIButton').addClass('d-none');

    $('#basic-off').addClass('d-none');
    $('#basic-on').removeClass('d-none');
    $('#tkt-off').removeClass('d-none');
    $('#tkt-on').addClass('d-none');

  }

  function tktIMI(){

    $('#tktMasyarakat').addClass('d-none');

    $('#tktIMI').removeClass('d-none');
    $('#tktIMIButton').removeClass('d-none');

    $('#basic-off').removeClass('d-none');
    $('#basic-on').addClass('d-none');
    $('#tkt-off').addClass('d-none');
    $('#tkt-on').removeClass('d-none');

  }

  function registerMasyarakat(){

    window.location.href = "tkt-masyarakat?f_pin=".concat(F_PIN)

  }

  function registerIMI(){

    window.location.href = "tkt-imi-club?f_pin=".concat(F_PIN)

  }

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