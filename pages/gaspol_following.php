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
    <title>Gaspol Profile</title>
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

    .modal-dialog {
        position:fixed;
        top:auto;
        right:auto;
        left:auto;
        bottom:0;
        margin: 0;
        padding: 0;
        width: inherit;
        margin-bottom: -10px;
    }
        
    .modal-content{
        height: 100px;
        padding-top: 20px;
        border: none;
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
                <b style="font-size: 14px"><?= count($followData) ?> Following</b>
            </div>
        </div>
    </div>

    <div class="px-3 pt-2">
        
        <?php if (count($followData) > 0): ?>
            <?php foreach($followData as $fd): ?>

                <!-- CHECK IF CLICK GOTO PROFILE USER OR CLUB USER -->

                <div class="row pt-3">

                <?php if (isset($fd['TKT_ID'])): ?>
                    <div class="col-2" onclick="openClub('<?= $fd['TKT_ID'] ?>')">
                <?php else: ?>
                    <div class="col-2" onclick="openProfile('<?= $fd['F_PIN'] ?>')">
                <?php endif; ?>

                        <!-- CHECK IMG SRC FROM PROFILE OR TKT -->

                        <?php if (isset($fd['TKT_ID'])): ?>

                            <img class="rounded-circle" style="width: 35px; height: 35px" src="../images/<?= $fd['IMAGE'] ?>"> 

                        <?php elseif ($fd['IMAGE']): ?>

                            <img class="rounded-circle" style="width: 35px; height: 35px" src="http://108.136.138.242/filepalio/image/<?= $fd['IMAGE'] ?>"> 

                        <?php else: ?>

                            <img class="rounded-circle" style="width: 35px; height: 35px" src="../assets/img/tab5/no-avatar.jpg"> 
                        
                        <?php endif; ?>
                            
                    </div>

                    <?php if (isset($fd['TKT_ID'])): ?>
                        <div class="col-8" onclick="openClub('<?= $fd['TKT_ID'] ?>')">
                    <?php else: ?>
                        <div class="col-8" onclick="openProfile('<?= $fd['F_PIN'] ?>')">
                    <?php endif; ?>

                        <div style="font-size: 13px"><b><?= $fd['FIRST_NAME']." ".$fd['LAST_NAME'] ?></b></div>

                        <?php 
                        
                        // GET CLUB INFO

                        $a = $fd['F_PIN'];

                        $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON TKT.ID = CLUB_MEMBERSHIP.CLUB_CHOICE WHERE CLUB_MEMBERSHIP.F_PIN = '".$a."'");
                        $query->execute();
                        $clubName = $query->get_result()->fetch_assoc();
                        $query->close();

                        if(isset($clubName)): 

                            // CHECK IF THIS CLUB OR PEOPLE

                            if (isset($fd['TKT_ID'])): ?>

                                <div style="font-size: 12px"><span style="color: darkorange">Club</span></div>

                            <?php else: ?>

                                <div style="font-size: 12px"><span style="color: darkorange">Member </span>of <?= $clubName['CLUB_NAME'] ?></div>

                            <?php endif; ?>

                        <?php else: ?>

                            <div style="font-size: 12px"><span style="color: grey">Not member of a club</div>

                        <?php endif; ?>
                    </div>

                    <?php if(!isset($_GET['l_pin'])):

                        if (isset($fd['TKT_ID'])): ?>

                            <div class="col-2" onclick="unfollowModalClub('<?= $fd['TKT_ID'] ?>')">
                                ...
                            </div>

                        <?php else: ?>

                            <div class="col-2" onclick="unfollowModalUser('<?= $fd['L_PIN'] ?>')">
                                ...
                            </div>

                        <?php endif; ?>

                    <?php else:

                        // GET ALREADY FOLLOW USER & TKT

                        $a = $_GET['f_pin'];
                        $b = $fd['F_PIN'];
                        $c = $fd['TKT_ID'];

                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$a' AND L_PIN = '$b'");
                        $query->execute();
                        $isFollow = $query->get_result()->fetch_assoc();
                        $query->close();

                        $query = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE F_PIN = '$a' AND TKT_ID = '$c'");
                        $query->execute();
                        $isFollowTKT = $query->get_result()->fetch_assoc();
                        $query->close();

                        if (isset($c)):

                            // CLUB

                            if (!isset($isFollowTKT)):
                                ?>
            
                                <div class="col-2">
                                    <img id="icon-follow-<?= $fd['TKT_ID'] ?>" src="../assets/img/social/follow.svg" style="height: 30px; width: 30px" onclick="followClub('<?= $fd['TKT_ID'] ?>')">
                                    <img id="icon-unfollow-<?= $fd['TKT_ID'] ?>" class="d-none" src="../assets/img/social/followed.svg" style="height: 30px; width: 30px" onclick="unfollowClub('<?= $fd['TKT_ID'] ?>')">
                                </div>
            
                            <?php else: ?>
            
                                <div class="col-2">
                                    <img id="icon-follow-<?= $fd['TKT_ID'] ?>" class="d-none" src="../assets/img/social/follow.svg" style="height: 30px; width: 30px" onclick="followClub('<?= $fd['TKT_ID'] ?>')">
                                    <img id="icon-unfollow-<?= $fd['TKT_ID'] ?>" src="../assets/img/social/followed.svg" style="height: 30px; width: 30px" onclick="unfollowClub('<?= $fd['TKT_ID'] ?>')">
                                </div>
            
                            <?php endif; ?>
                        
                        <?php else:

                            // NON CLUB

                            if (!isset($isFollow)):
                            ?>

                            <div class="col-2">
                                <img id="icon-follow-<?= $fd['F_PIN'] ?>" src="../assets/img/social/follow.svg" style="height: 30px; width: 30px" onclick="follow('<?= $fd['F_PIN'] ?>')">
                                <img id="icon-unfollow-<?= $fd['F_PIN'] ?>" class="d-none" src="../assets/img/social/followed.svg" style="height: 30px; width: 30px" onclick="unfollow('<?= $fd['F_PIN'] ?>')">
                            </div>

                            <?php else: ?>

                            <div class="col-2">
                                <img id="icon-follow-<?= $fd['F_PIN'] ?>" class="d-none" src="../assets/img/social/follow.svg" style="height: 30px; width: 30px" onclick="follow('<?= $fd['F_PIN'] ?>')">
                                <img id="icon-unfollow-<?= $fd['F_PIN'] ?>" src="../assets/img/social/followed.svg" style="height: 30px; width: 30px" onclick="unfollow('<?= $fd['F_PIN'] ?>')">
                            </div>

                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <b class="text-secondary">No following available!</b>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <div class="modal fade" id="modalUnfollowUser" tabindex="-1" aria-labelledby="modalUnfollowUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content">
            <div class="modal-body">
                <div class="row" onclick="unfollowUserDot()">
                    <div class="col-2 text-center">
                        <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/tab5/no-avatar.jpg"> 
                    </div>
                    <div class="col-10">
                        <b style="font-size: 13px">Unfollow</b>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUnfollowClub" tabindex="-1" aria-labelledby="modalUnfollowClubLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div style="margin: 10px; width: 50px; height: 7px; background-color: white; border-radius: 20px"></div>
                </div>
            </div>
            <div class="modal-content">
            <div class="modal-body">
                <div class="row" onclick="unfollowClubDot()">
                    <div class="col-2 text-center">
                        <img class="rounded-circle" style="width: 20px; height: auto" src="../assets/img/tab5/no-avatar.jpg"> 
                    </div>
                    <div class="col-10">
                        <b style="font-size: 13px">Unfollow</b>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

<script>

    function unfollowModalUser(id){

        $('#modalUnfollowUser').modal('show');
        localStorage.setItem('id_unfollow', id);

    }

    function unfollowModalClub(id){

        $('#modalUnfollowClub').modal('show');
        localStorage.setItem('id_unfollow', id);

    }

    function unfollowUserDot(){

        var id_unfollow = localStorage.getItem('id_unfollow');
        var f_pin = "<?= $f_pin ?>";

        console.log(id_unfollow);

        var formData = new FormData();

        formData.append('f_pin', f_pin);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#modalUnfollow').modal('hide');
                    window.location.href = "gaspol_following?f_pin=".concat(f_pin)

                }
            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol");
        xmlHttp.send(formData);

    }

    function unfollowClubDot(){

        var id_unfollow = localStorage.getItem('id_unfollow');
        var f_pin = "<?= $f_pin ?>";

        console.log(id_unfollow);

        var formData = new FormData();

        formData.append('f_pin', f_pin);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#modalUnfollow').modal('hide');
                    window.location.href = "gaspol_following?f_pin=".concat(f_pin)

                }
            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function follow(l_pin){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = l_pin;

        if (window.Android) {
            if (!window.Android.checkProfile()) {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: '',
                param2: 'follow_store'
            });
            return;

        }

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('l_pin', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#icon-unfollow-'+l_pin).removeClass('d-none');
                    $('#icon-follow-'+l_pin).addClass('d-none');

                }

            }
        }
        xmlHttp.open("post", "../logics/follow_gaspol");
        xmlHttp.send(formData);

    }

    function unfollow(l_pin){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var id_unfollow = l_pin;

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#icon-unfollow-'+l_pin).addClass('d-none');
                    $('#icon-follow-'+l_pin).removeClass('d-none');

                }

            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol");
        xmlHttp.send(formData);

    }

    function followClub(l_pin){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = l_pin;

        if (window.Android) {
            if (!window.Android.checkProfile()) {
                return;
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: '',
                param2: 'follow_store'
            });
            return;

        }

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('l_pin', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#icon-unfollow-'+l_pin).removeClass('d-none');
                    $('#icon-follow-'+l_pin).addClass('d-none');

                }

            }
        }
        xmlHttp.open("post", "../logics/follow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function unfollowClub(l_pin){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var id_unfollow = l_pin;

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                if (result == 0){

                    $('#icon-unfollow-'+l_pin).addClass('d-none');
                    $('#icon-follow-'+l_pin).removeClass('d-none');

                }

            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function openProfile(l_pin){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var l_pin = l_pin;

        window.location.href = "tab3-profile?f_pin=".concat(f_pin)+"&l_pin="+l_pin;

    }

    function openClub(tkt_id){

        var f_pin = "<?= $_GET['f_pin'] ?>";
        var tkt_id = tkt_id;

        window.location.href = "gaspol_club?f_pin=".concat(f_pin)+"&l_pin="+tkt_id;

    }

    function closeAndroid(){

        // if (window.Android){

        // window.Android.finishGaspolForm();

        // }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        // window.webkit.messageHandlers.finishGaspolForm.postMessage({
        //     param1: ""
        // });
        // return;

        // }else{

        history.back();

        // }
    }

</script>