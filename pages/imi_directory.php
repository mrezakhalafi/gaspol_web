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

    // IF OTHERS

    if (isset($_GET['l_pin'])){
        $f_pin = $_GET['l_pin'];
    }


  // GET USER INFO

  $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
  $query->execute();
  $userData = $query->get_result()->fetch_assoc();
  $query->close();

  // GET FOLLOWING USER

  $followData;

  $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST LEFT JOIN USER_LIST ON FOLLOW_LIST.L_PIN = USER_LIST.F_PIN WHERE FOLLOW_LIST.F_PIN 
                            = '$f_pin'");
  $query->execute();
  $followDataUser = $query->get_result();

  while ($row = $followDataUser->fetch_array(MYSQLI_ASSOC))
  {
      $followData[] = $row;
  }

   // GET FOLLOWING TKT

   $query = $dbconn->prepare("SELECT FOLLOW_TKT.*, TKT.*, TKT.CLUB_NAME AS FIRST_NAME, TKT.PROFILE_IMAGE AS IMAGE FROM FOLLOW_TKT LEFT JOIN TKT ON TKT.ID = FOLLOW_TKT.TKT_ID WHERE FOLLOW_TKT.F_PIN 
                             = '$f_pin'");
   $query->execute();
   $followDataUser = $query->get_result();
 
   while ($row = $followDataUser->fetch_array(MYSQLI_ASSOC))
   {
       $followData[] = $row;
   }

  $query->close();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMI Directory</title>
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

    b{
        font-size: 14px;
    }

    small{
        font-size: 12px;
    }

    </style>

  </head>

    <body>

    <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
        <div class="row">
            <div class="col-2">
                <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
            </div>
            <div class="col-10 pt-1">
                <b style="font-size: 14px">IMI Directory</b>
            </div>
        </div>
    </div>

    <div class="section-menu">
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link1()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/path.svg?v=2" style="width: 25px; height: 25px">
            </div>
            <div class="col-8">
                <b>About IMI</b><br />
                <small class="text-secondary">Introduction</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link2()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/directory.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-8">
                <b>IMI Management</b><br />
                <small class="text-secondary">Administration</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link3()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/doc.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-8">
                <b>AD/ART IMI</b><br />
                <small class="text-secondary">Documents</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link4()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/docs.svg" style="width: 22px; height: 22px">
            </div>
            <div class="col-8">
                <b>IMI Decree Law</b><br />
                <small class="text-secondary">Court of Law</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link5()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/association.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-8">
                <b>IMI Province</b><br />
                <small class="text-secondary">Structure</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
        <div class="row pt-3 pb-3 gx-0" style="border-bottom: 1px solid #eaeaea" onclick="link6()">
            <div class="col-2 text-center">
                <img src="../assets/img/social/club.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-8">
                <b>IMI Club Directory</b><br />
                <small class="text-secondary">List of Club</small>
            </div>
            <div class="col-2 text-center">
                <img src="../assets/img/social/chevron.svg" style="width: 30px; height: 30px">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

    function link1(){

        window.location.href = "http://imi.id/imi/organisasi";

    }

    function link2(){

        window.location.href = "http://imi.id/imi/peraturan-organisasi";

    }


    function link3(){

     window.location.href = "http://imi.id/imi/anggaran-dasar";

    }


    function link4(){

        window.location.href = "http://imi.id/imi/sk-organisasi";

    }


    function link5(){

        window.location.href = "http://imi.id/imi/imi-provinsi";

    }


    function link6(){

        window.location.href = "http://imi.id/anggota/daftar/anggota-prestasi/3";

    }


    function closeAndroid(){

        history.back();
    }

</script>