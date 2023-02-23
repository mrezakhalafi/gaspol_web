<?php

  include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

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

  // GET CLUB

  $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON TKT.ID =  CLUB_MEMBERSHIP.CLUB_CHOICE WHERE CLUB_MEMBERSHIP.F_PIN = '$f_pin'");
  $query->execute();
  $getClub = $query->get_result()->fetch_assoc();
  $query->close();

  if (!isset($getClub['CLUB_NAME'])){
    $getClub['CLUB_NAME'] = "No Club";
  }

  // FOR PADDING DIV BASIC ACCOUNT

  $padding = 0;

  if(!isset($checkKTA)){
    $padding = 1;
  }

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <style>

      /* FOR HTML NOT OFFSIDE */

      html,
      body {
        max-width: 100%;
        overflow-x: hidden;
        font-family: 'Poppins' !important;
      }

      .progress {
        background-color: grey;
        height: 10px;
      }

      .card {
        border-radius: 1rem;
        color: white !important;
      }

    </style>

  </head>

  <body style="visibility: hidden">

  <div class="row p-3 fixed-top" style="background-color:#ffa500; height: 55px">
    <div class="col-6">

        <?php if ($_GET['m'] !=2 ): ?>
          <p style="color: white; font-size: 15px"><b id="benefits-title" >KTA Benefits</b></p>
        <?php else: ?>
          <p style="color: white; font-size: 15px"><b id="benefits-title" >KIS Benefits</b></p>
        <?php endif; ?>

    </div>
    <div class="col-6 text-end">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/VisualEditor_-_Icon_-_Close_-_white.svg/1200px-VisualEditor_-_Icon_-_Close_-_white.svg.png" style="width: 28px; margin-top: -10px" onclick="closeAndroid()">
    </div>
  </div>

  <div class="fixed-top section-menu bg-white shadow" style="margin-top: 55px">
    <div class="slide-menu" style="height: 60px">
      <div class="row" style="height: 100%">
        <div class="col-4 text-center pt-3" onclick="ktaMobility()">

          <?php if ($_GET['m'] != 0): ?>

            <img id="mob-off" src="../assets/img/social/level=Mobility, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">
            <img id="mob-on" src="../assets/img/social/level=Mobility, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">

          <?php else: ?>

            <img id="mob-off" src="../assets/img/social/level=Mobility, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">
            <img id="mob-on" src="../assets/img/social/level=Mobility, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">

          <?php endif; ?>
            
        </div>
        <div class="col-4 text-center pt-3" onclick="ktaPro()">

          <?php if ($_GET['m'] != 1): ?>
            
            <img id="pro-off" src="../assets/img/social/level=KTA Pro, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">
            <img id="pro-on" src="../assets/img/social/level=KTA Pro, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">

          <?php else: ?>      
            
            <img id="pro-off" src="../assets/img/social/level=KTA Pro, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">
            <img id="pro-on" src="../assets/img/social/level=KTA Pro, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">
            
          <?php endif; ?>
        </div>
        <div class="col-4 text-center pt-3" onclick="kis()">

          <?php if ($_GET['m'] != 2): ?>
            
            <img id="kis-off"  src="../assets/img/social/level=KIS, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">
            <img id="kis-on"  src="../assets/img/social/level=KIS, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">

          <?php else: ?>      

            <img id="kis-off"  src="../assets/img/social/level=KIS, state=off.png" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2 d-none">
            <img id="kis-on"  src="../assets/img/social/level=KIS, state=on.svg" style="width: 40px; height: 40px; margin-top: -7px !important" class="mt-2">
          
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

    <div id="ktaMobility" class="slide-menu pt-3 px-4 <?php if ($_GET['m'] != 0): ?> d-none <?php endif; ?>" style="margin-bottom: 100px; margin-top: 120px">

      <div class="row">
        <div class="col-8">
          <b style="font-size: 16px">KTA Mobility</b>
        </div>
        <div class="col-4">

          <?php if (isset($checkKTA) && $statusKTA == 0): ?>
            <div class="gx-auto" style="background-color: #325af7; border-radius: 20px; font-size: 12px; padding: 7px; width: 85%; padding-left: 12px; margin-bottom: 11px; color: white">
              <img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
              style="height: 15px; width: 15px; margin-right: 10px; background-color: white; padding: 2px">Active
            </div>
          <?php endif; ?>

        </div>
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px; font-weight: 600">Benefits</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/globe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Wide range IMI network</b><br />
            <small style="font-size: 11px; color: #646464">Indonesia’s largest automotive organization.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/club.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Automotive Club</b><br />
            <small style="font-size: 11px; color: #646464">Join and or create club according to your passion.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/discount.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Special Member Discount</b><br />
            <small style="font-size: 11px; color: #646464">Discount at IMI’s official merchant/tennant.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/towing.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Emergency Roadside Assistance</b><br />
            <small style="font-size: 11px; color: #646464">Drive without worry, get free towing (+add on).</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/safe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Life Insurance</b><br />
            <small style="font-size: 11px; color: #646464">Receive protection from traffic accident.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/hospital.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Hospital Care</b><br />
            <small style="font-size: 11px; color: #646464">Discounted price at Siloam hospital.</small>
          </div>
        </div>
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Requirements</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/docs.svg" style="width: 22px; height: 22px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Fill out registration forms</b><br />
            <small style="font-size: 11px; color: #646464">Prepare your ID and fill needed information.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/member.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Join a Club</b><br />
            <small style="font-size: 11px; color: #646464">You need to select club during registration.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/money.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Pay Rp 50.000 fee</b><br />
            <small style="font-size: 11px; color: #646464">KTA Mobility annual membership fee.</small>
          </div>
        </div>
      </div>
    </div>
    <div id="ktaMobilityButton" class="bg-white p-2 fixed-bottom <?php if ($_GET['m'] != 0): ?> d-none <?php endif; ?>">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        
      <?php if (isset($checkKTA)): ?>
        <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: grey; width: 100%; color: white" disabled><b>UPGRADE</b></button>
      <?php else: ?>
        <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="upgradeMobility()"><b>UPGRADE</b></button>
      <?php endif; ?>

      </div>
    </div>

    <div id="ktaPro" class="slide-menu pt-3 px-4 <?php if ($_GET['m'] != 1): ?> d-none <?php endif; ?>" style="margin-bottom: 100px; margin-top: 120px">

      <div class="row">
        <div class="col-8">
          <b style="font-size: 16px">KTA Pro</b>
        </div>
        <div class="col-4">

          <?php if ((isset($checkKTA) && $statusKTA == 1) && !isset($checkKIS)): ?>
            <div class="gx-auto" style="background-color: #325af7; border-radius: 20px; font-size: 12px; padding: 7px; width: 85%; padding-left: 12px; margin-bottom: 11px; color: white">
              <img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
              style="height: 15px; width: 15px; margin-right: 10px; background-color: white; padding: 2px">Active
            </div>
          <?php endif; ?>

        </div>
      </div>

      <div class="section-slide mt-3">
        <p style="font-size: 12px; font-weight: 600">Benefits</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/globe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Wide range IMI network</b><br />
            <small style="font-size: 11px; color: #646464">Indonesia’s largest automotive organization.</small>
          </div>
        </div>
        <!-- <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/category.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">All of Mobility Benefits</b><br />
            <small style="font-size: 11px; color: #646464">Automotive club and special member discount</small>
          </div>
        </div> -->
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=fia-black.png" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">FIA Membership</b><br />
            <small style="font-size: 11px; color: #646464">Federation Internationale de I'Automobile.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=fim-black.png" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">FIM Membership</b><br />
            <small style="font-size: 11px; color: #646464">Federation Internationale de Motocyclisme.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/club.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Automotive Club</b><br />
            <small style="font-size: 11px; color: #646464">Join and or create club according to your passion.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/discount.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Special Member Discount</b><br />
            <small style="font-size: 11px; color: #646464">Discount at IMI’s official merchant/tennant.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/towing.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Emergency Roadside Assistance</b><br />
            <small style="font-size: 11px; color: #646464">Drive without worry, get free towing (+add on).</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/safe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Life Insurance</b><br />
            <small style="font-size: 11px; color: #646464">Receive protection from traffic accident.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/hospital.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Hospital Care</b><br />
            <small style="font-size: 11px; color: #646464">Discounted price at Siloam hospital.</small>
          </div>
        </div>
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Requirements</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/docs.svg" style="width: 22px; height: 22px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Fill out registration forms</b><br />
            <small style="font-size: 11px; color: #646464">Prepare your ID and fill needed information.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/member.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Join a Club</b><br />
            <small style="font-size: 11px; color: #646464">You need to select club during registration.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/money.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Pay Rp 150.000 fee</b><br />
            <small style="font-size: 11px; color: #646464">KTA Pro annual membership fee.</small>
          </div>
        </div>
      </div>
    </div>
    <div id="ktaProButton" class="bg-white p-2 fixed-bottom <?php if ($_GET['m'] != 1): ?> d-none <?php endif; ?>">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        
        <?php if (isset($checkKTA) && $statusKTA == 1): ?>
          <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: grey; width: 100%; color: white" disabled><b>UPGRADE</b></button>
        <?php elseif (isset($checkKTA) && $statusKTA == 0): ?>
          <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="upgradePro()"><b>UPGRADE</b></button>
        <?php else: ?>
          <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="newPro()"><b>UPGRADE</b></button>
        <?php endif; ?>

      </div>
    </div>

    <div id="kis" class="slide-menu pt-3 px-4 <?php if ($_GET['m'] != 2): ?> d-none <?php endif; ?>" style="margin-bottom: 100px; margin-top: 120px">
      
      <div class="row">
        <div class="col-8">
          <b style="font-size: 16px">KIS (Kartu Izin Start)</b>
        </div>
        <div class="col-4">

          <?php if (isset($checkKIS)): ?>
            <div class="gx-auto" style="background-color: #325af7; border-radius: 20px; font-size: 12px; padding: 7px; width: 85%; padding-left: 12px; margin-bottom: 11px; color: white">
              <img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
              style="height: 15px; width: 15px; margin-right: 10px; background-color: white; padding: 2px">Active
            </div>
          <?php endif; ?>

        </div>
      </div>

      <div class="section-slide mt-3">
        <p style="font-size: 12px; font-weight: 600">Benefits</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/globe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Wide range IMI network</b><br />
            <small style="font-size: 11px; color: #646464">Indonesia’s largest automotive organization.</small>
          </div>
        </div>
        <!-- <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/category.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">All of Mobility Benefits</b><br />
            <small style="font-size: 11px; color: #646464">Automotive club and special member discount</small>
          </div>
        </div> -->
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=fia-black.png" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">FIA Membership</b><br />
            <small style="font-size: 11px; color: #646464">Federation Internationale de I'Automobile.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/Property 1=fim-black.png" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">FIM Membership</b><br />
            <small style="font-size: 11px; color: #646464">Federation Internationale de Motocyclisme.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/club.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Automotive Club</b><br />
            <small style="font-size: 11px; color: #646464">Join and or create club according to your passion.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/discount.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Special Member Discount</b><br />
            <small style="font-size: 11px; color: #646464">Discount at IMI’s official merchant/tennant.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/towing.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Emergency Roadside Assistance</b><br />
            <small style="font-size: 11px; color: #646464">Drive without worry, get free towing (+add on).</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/safe.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Life Insurance</b><br />
            <small style="font-size: 11px; color: #646464">Receive protection from traffic accident.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/hospital.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Hospital Care</b><br />
            <small style="font-size: 11px; color: #646464">Discounted price at Siloam hospital.</small>
          </div>
        </div>
        <!-- <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/traffic-sign.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Samsat & Dishub Priority</b><br />
            <small style="font-size: 11px; color: #646464">Driving license and other government related procedure</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/car.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Join Racing Events</b><br />
            <small style="font-size: 11px; color: #646464">Driving license and other government related procedure</small>
          </div>
        </div> -->
      </div>
      <div class="section-slide mt-3">
        <p style="font-size: 12px">Requirements</p>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/docs.svg" style="width: 22px; height: 22px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Fill out registration forms</b><br />
            <small style="font-size: 11px; color: #646464">Prepare your ID and fill needed information.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/member.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">KTA Pro Member</b><br />
            <small style="font-size: 11px; color: #646464">You need to be a Professional IMI member.</small>
          </div>
        </div>
        <div class="row pt-2 pb-2" style="border-bottom: 1px solid #eaeaea">
          <div class="col-2 text-center">
            <img src="../assets/img/social/money.svg" style="width: 28px; height: 28px">
          </div>
          <div class="col-10">
            <b style="font-size: 13px">Rp 50.000 /license</b><br />
            <small style="font-size: 11px; color: #646464">KIS License cost vary in each Province.</small>
          </div>
        </div>
      </div>
    </div>
    <div id="kisButton" class="bg-white p-2 fixed-bottom <?php if ($_GET['m'] != 2): ?> d-none <?php endif; ?>">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        
        <?php if (isset($checkKTA) && $checkKTA['STATUS_ANGGOTA'] == 1): ?>
          <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="registerKIS()"><b>BUY LICENSE</b></button>
        <?php else: ?>
        
        <div class="badge-danger text-center" style="background-color: #a3d3ff; padding: 11px; font-size: 12px; border-radius: 11px; color: #336bed">
          <b>You haven't met the requirements.</b>
        </div>

      <?php endif; ?>

      </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

  var F_PIN = new URLSearchParams(window.location.search).get('f_pin');

  function ktaMobility(){

    let dest = 'imi_benefit?f_pin=' + F_PIN + '&m=0';
    window.history.replaceState(null, "", dest);

    $('#benefits-title').text('KTA Benefits');

    $('#ktaMobility').removeClass('d-none');
    $('#ktaMobilityButton').removeClass('d-none');

    $('#ktaPro').addClass('d-none');
    $('#ktaProButton').addClass('d-none');

    $('#kis').addClass('d-none');
    $('#kisButton').addClass('d-none');

    $('#mob-off').addClass('d-none');
    $('#mob-on').removeClass('d-none');

    $('#pro-off').removeClass('d-none');
    $('#pro-on').addClass('d-none');
    $('#kis-off').removeClass('d-none');
    $('#kis-on').addClass('d-none');

  }

  function ktaPro(){

    let dest = 'imi_benefit?f_pin=' + F_PIN + '&m=1';
    window.history.replaceState(null, "", dest);

    $('#benefits-title').text('KTA Benefits');

    $('#ktaMobility').addClass('d-none');
    $('#ktaMobilityButton').addClass('d-none');

    $('#ktaPro').removeClass('d-none');
    $('#ktaProButton').removeClass('d-none');

    $('#kis').addClass('d-none');
    $('#kisButton').addClass('d-none');

    $('#pro-off').addClass('d-none');
    $('#pro-on').removeClass('d-none');

    $('#mob-off').removeClass('d-none');
    $('#mob-on').addClass('d-none');
    $('#kis-off').removeClass('d-none');
    $('#kis-on').addClass('d-none');

  }

  function kis(){

    let dest = 'imi_benefit?f_pin=' + F_PIN + '&m=2';
    window.history.replaceState(null, "", dest);

    $('#benefits-title').text('KIS Benefits');

    $('#ktaMobility').addClass('d-none');
    $('#ktaMobilityButton').addClass('d-none');

    $('#ktaPro').addClass('d-none');
    $('#ktaProButton').addClass('d-none');

    $('#kis').removeClass('d-none');
    $('#kisButton').removeClass('d-none');

    $('#kis-off').addClass('d-none');
    $('#kis-on').removeClass('d-none');

    $('#mob-off').removeClass('d-none');
    $('#mob-on').addClass('d-none');
    $('#pro-off').removeClass('d-none');
    $('#pro-on').addClass('d-none');

  }

  function upgradeMobility(){

    if (window.Android) {
      if (!window.Android.checkProfile()) {
      return;
      }
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      window.webkit.messageHandlers.checkProfile.postMessage({
      param1: $productCode,
      param2: 'like'
      });
      return;
    }

    window.location.href = "form-kta-mobility?f_pin=".concat(F_PIN)

  }

  function newPro(){

    if (window.Android) {
      if (!window.Android.checkProfile()) {
      return;
      }
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      window.webkit.messageHandlers.checkProfile.postMessage({
      param1: $productCode,
      param2: 'like'
      });
      return;
    }

    window.location.href = "form-kta-pronew?f_pin=".concat(F_PIN)

  }

  function upgradePro(){

    window.location.href = "form-kta-pronew?f_pin=".concat(F_PIN)

  }

  function registerKIS(){

    window.location.href = "form-kis-new?f_pin=".concat(F_PIN)

  }

  function viewCardMobility(){

    window.location.href = "card-kta-mobility?f_pin=".concat(F_PIN)

  }

  function viewCardPro(){

    window.location.href = "card-kta-pronew?f_pin=".concat(F_PIN)

  }

  function viewKIS(){

    window.location.href = "card-kis?f_pin=".concat(F_PIN)

  }

  function claimKTA(){

    window.location.href = "".concat(F_PIN)

  }

  function closeAndroid(){

    if (window.Android) {
      window.Android.tabShowHide(true);
    }

    history.back();

  }

  if (window.Android) {
    window.Android.tabShowHide(false);
  }
  

  $(document).ready(function(e) {

    $('body').css('visibility','visible');

  });

</script>