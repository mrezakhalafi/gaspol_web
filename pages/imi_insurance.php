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

    // CHECK INSURANCE

    // $query = $dbconn->prepare("");
    // $query->execute();
    // $insurance = $query->get_result()->fetch_assoc();
    $insurance = true;
    // $query->close();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMI Insurance</title>
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

    a:not([href]):not([class]), a:not([href]):not([class]):hover {
        color: grey;
      }

    .navbar a{
        color: grey;
        text-decoration: none;
        font-size: 14px;
        padding-bottom: 8px;
    }

    .activeNav{
        color: black !important;
        border-bottom: 3px solid darkorange !important;
    }
    
    </style>

  </head>

    <body>

    <div class="p-3 shadow-sm fixed-top" style="border-bottom: 1px solid #e4e4e4; background-color: white">
        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
            </div>
            <div class="col-10 pt-1">
                <b style="font-size: 14px" id="insurance-title">Insurance</b>
            </div>
        </div>
    </div>
    <div class="navbar fixed-top" style="margin-top: 60px; padding-bottom: 20px; background-color: white">
        <div class="col-6 text-center">
            <a id="navLife" class="activeNav" onclick="changeLife()"><b style="font-size: 15px">Life</b></a>
        </div>
        <div class="col-6 text-center">
            <a id="navVehicle" onclick="changeVehicle()"><b style="font-size: 15px">Vehicle</b></a>
        </div>
    </div>

    <div class="section-life container" style="margin-top: 140px">

        <div class="row">
            <div class="col-8">
                <?php if($statusKTA == 0): ?>
                    <p style="margin-bottom: 10px"><b>KTA Mobility</b></p>
                <?php elseif($statusKTA == 1): ?>
                    <p style="margin-bottom: 10px"><b>KTA Pro</b></p>
                <?php endif; ?>
            </div>
            <div class="col-4">
                
                <?php if (isset($insurance)): ?>
                    <!-- <div class="gx-auto" style="background-color: #325af7; border-radius: 20px; font-size: 12px; padding: 7px; width: 85%; padding-left: 12px; margin-bottom: 11px; color: white">
                        <img src="../assets/img/social/Property 1=line.svg" class="rounded-circle" 
                        style="height: 15px; width: 15px; margin-right: 10px; background-color: white; padding: 2px">Active
                    </div> -->
                <?php endif; ?>

            </div>
        </div>

        <p><b style="font-size: 19px">Life Insurance</b></p>
        <p style="font-size: 13px" class="text-secondary">Now IMI members can feel safe while driving and live the automotive spirit without worrying with Accident Insurance protection from PT Asuransi Sinar Mas as follows:</p>

        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/social/accept.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-10">
                <b>Death Benefits</b>
                <p class="text-secondary" style="font-size: 13px; margin-top: 5px">Died due to accident worth Rp10.000.000,-</p>
            </div>
        </div>
        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/social/accept.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-10">
                <b>Disability Benefits</b>
                <p class="text-secondary" style="font-size: 13px; margin-top: 5px">Disability cause by accident up to Rp10.000.000,-</p>
            </div>
        </div>
        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/social/accept.svg" style="width: 30px; height: 30px">
            </div>
            <div class="col-10">
                <b>Hospital Benefits</b>
                <p class="text-secondary" style="font-size: 13px; margin-top: 5px">Reimbursement for hospital costs due to accidents up to Rp1.000.000</p>
            </div>
        </div>

        <p style="font-size: 13px" class="text-secondary">Personal Accident insurance from PT Asuransi Sinar Mas is valid for 1 year from the date of membership registration. For detailed information on benefits and filing an insurance claim, please contact:</p>
    
        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/social/question.svg" style="width: 22px; height: 22px">
            </div>
            <div class="col-10">
                <b>(021) 2356 7888 / 5050 7888</b>
                <p class="text-secondary" style="font-size: 13px; margin-top: 5px">Customer care</p>
            </div>
        </div>
        <div class="row">
            <div class="col-2 text-center">
                <img src="../assets/img/social/whatsapp.svg" style="width: 22px; height: 22px">
            </div>
            <div class="col-10">
                <b>021 8060 0691</b>
                <p class="text-secondary" style="font-size: 13px; margin-top: 5px">Whatsapp</p>
            </div>
        </div>

    </div>

    <div class="section-vehicle text-center d-none" style="margin-top: 340px">
      <img src="../assets/img/social/empty-state.svg" style="height: 90px; width: 90px">
      <div class="row mt-3 text-secondary">
        <b style="font-size: 18px">Coming Up Soon</b>
        <p style="font-size: 14px; margin-top: 5px">Look forward for special vehicle insurance offer</p>
      </div>
    </div>

    <div class="modal fade p-3" id="modalSuccess" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content p-3" style="z-index: 9999">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Claim Insurance Success</h1>
                    <p class="mt-2">Verifying your insurance, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="homepage.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">OK</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="claimButton" class="bg-white p-2 fixed-bottom">
      <div class="section-button mt-2 mb-2 mx-4 bg-white">
        
        <?php if (isset($checkKTA)): ?>
            <div class="row">
                <div class="col-6">
                    <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: white; width: 100%; color: black; border: 1px solid black" onclick="policy()"><b>View Policy</b></button>
                </div>
                <div class="col-6">
                    <button class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 100%; color: white" onclick="claim()"><b>Claim</b></button>
                </div>
            </div>
        <?php else: ?>
        
        <div class="badge-danger text-center" style="background-color: #a3d3ff; padding: 10px; border-radius: 11px; color: #336bed">
          <b style="font-size: 10px">Special insurance for IMI member (KTA Pro and Mobility)</b>
        </div>

      <?php endif; ?>

      </div>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

    if (localStorage.lang == 1){

        $('#insurance-title').text('Asuransi');
        $('#navLife').text('Jiwa');
        $('#navLife').css('font-weight','bold');
        $('#navVehicle').text('Kendaraan');
        $('#navVehicle').css('font-weight','bold');

    }

    var switch_tab = localStorage.getItem('switch_tab');

    if (!switch_tab){
        switch_tab = 0;
    }

    if (switch_tab == 0){
        changeLife();
    }
    else if(switch_tab == 1){
        changeVehicle();
    }

    function changeLife(){

        localStorage.setItem('switch_tab','0');

        $('.section-life').removeClass('d-none');
        $('.section-vehicle').addClass('d-none');

        $('#navLife').addClass('activeNav');
        $('#navVehicle').removeClass('activeNav');

        $('#claimButton').show();
    }

    function changeVehicle(){

        localStorage.setItem('switch_tab','1');

        $('.section-life').addClass('d-none');
        $('.section-vehicle').removeClass('d-none');

        $('#navLife').removeClass('activeNav');
        $('#navVehicle').addClass('activeNav');

        $('#claimButton').hide();

    }

    function policy(){

        window.location.href = "";

    }

    function claim(){

        $('#modalSuccess').modal('show');

        // var formData = new FormData();

        // formData.append('f_pin', '<?= $f_pin ?>');
        // formData.append('type', 1);

        // let xmlHttp = new XMLHttpRequest();
        // xmlHttp.onreadystatechange = function(){
        //     if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
        //         // console.log(xmlHttp.responseText);

        //     }
        // }
        // xmlHttp.open("post", "../logics/claim_insurance");
        // xmlHttp.send(formData);

    }

    function closeAndroid(){

        history.back();

    }

</script>

<script>

  if (window.Android) {
    window.Android.tabShowHide(false);
  }
  
</script>