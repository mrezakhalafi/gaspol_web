<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $f_pin = $_GET['f_pin'];

if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}
else if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}

$dbconn = paliolite();

$ver = time();

// $sqlData = "SELECT COUNT(*) as exist
//   FROM KTA
//   WHERE F_PIN = '$f_pin'";

$content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE");
$content->execute();
$contentCategory = $content->get_result();
$content->close();

// TKT F_PIN
$tktData = $dbconn->prepare("SELECT * FROM TKT");
$tktData->execute();
$tktName = $tktData->get_result();
$tktData->close();

// CONTENT CATEGORY
// $content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE");
// $content->execute();
// $contentCategory = $content->get_result();
// $content->close();

// foreach ($contentCategory as $ct) {
//     print_r($ct);
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMI Communities</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <style>

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }
        
    </style>
</head>
<body>

    <div class="row p-4">
        <div class="col-2">
            <img src="../assets/img/back_arrow.png" alt="" style="width: 30px; height: 30px" onclick="closeAndroid()">
        </div>
        <div class="col-10">
            <h3 class="mb-0" id="notif-text" style="font-weight: 700; font-size: 20px; margin-top: 3px">Notification</h3>
        </div>
    </div>
    
    <div class="row gx-0 p-4 bg-light" style="width: 100%">
        <div class="col-12">
            <p id="this-week" class="mb-0" style="font-weight: 700">This week</p>
        </div>
    </div>

    <section id="notification">

        <div class="row p-4">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">
            </div>
            <div class="col-8">
                <p class="mb-0" style="font-size: 12px"><span style="font-weight: 700">Mazda CX-5 Club</span> has successfully renewed and now active.</p>
                <p class="mb-0" style="font-size: 10px; color: #979797">2 Mar 2022 at 03:06</p>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/ic_collaps_arrow.png" alt="" style="width: 30px; height: 30px; border-radius: 50%; transform: rotate(90deg)" class="mt-2">
            </div>
        </div>

        <div class="row p-4">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">
            </div>
            <div class="col-8">
                <p class="mb-0" style="font-size: 12px"><span style="font-weight: 700">Mazda CX-5 Club</span> has expired and inactive, renew club active period here.</p>
                <p class="mb-0" style="font-size: 10px; color: #979797">2 Mar 2022 at 03:06</p>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/ic_collaps_arrow.png" alt="" style="width: 30px; height: 30px; border-radius: 50%; transform: rotate(90deg)" class="mt-2">
            </div>
        </div>

        <div class="row p-4">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">
            </div>
            <div class="col-10">
                <p class="mb-0" style="font-size: 12px"><span style="font-weight: 700">Mazda CX-5 Club</span> has removed from the club.</p>
                <p class="mb-0" style="font-size: 10px; color: #979797">2 Mar 2022 at 03:06</p>
            </div>
        </div>

        <!-- ACCEPT AND DECLINE -->
        <div class="row p-4">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">
            </div>
            <div class="col-8">
                <p class="mb-0" style="font-size: 12px"><span style="font-weight: 700">Mazda CX-5 Club</span> invited you to join the club association.</p>
                <p class="mb-0" style="font-size: 10px; color: #979797">2 Mar 2022 at 03:06</p>
            </div>
            <div class="col-1 d-flex justify-content-center mt-2">
                <img src="../assets/img/decline.svg" alt="" style="width: 30px; height: 30px; border-radius: 50%">
            </div>
            <div class="col-1 d-flex justify-content-center mt-2">
                <img src="../assets/img/accept.svg" alt="" style="width: 30px; height: 30px; border-radius: 50%">
            </div>
        </div>

        <div class="row p-4">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">
            </div>
            <div class="col-8">
                <p class="mb-0" style="font-size: 12px"><span style="font-weight: 700">Mazda CX-5 Club</span> requested to become a member.</p>
                <p class="mb-0" style="font-size: 10px; color: #979797">2 Mar 2022 at 03:06</p>
            </div>
            <div class="col-1 d-flex justify-content-center mt-2">
                <img src="../assets/img/decline.svg" alt="" style="width: 30px; height: 30px; border-radius: 50%">
            </div>
            <div class="col-1 d-flex justify-content-center mt-2">
                <img src="../assets/img/accept.svg" alt="" style="width: 30px; height: 30px; border-radius: 50%">
            </div>
        </div>
        <!-- END AC & DC -->

    </section>

    <section id="no-notification">
        <div class="row p-3">
            <div class="col-12 text-center">
                <p id="no-notif" class="mb-0" style="color: #858585">No Notifications.</p>
            </div>
        </div>
    </section>

    <form id="imi-notification" method="POST" class="main-form" enctype="multipart/form-data">

      
        
    </form>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
    </script>

    <!-- <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kta-mobility.js?v=<?php echo $ver; ?>"></script> -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <!-- Javascript -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>

<script>

    if (localStorage.lang == 1){

        $('#notif-text').text('Notifikasi');
        $('#this-week').text('Minggu Ini');
        $('#no-notif').text('Tidak ada notifikasi.');
    }

    $("#submit-orange").hide();
    var array_idcat = [];
    
    $('body').on('click', '.class_category', function() {

        var choice_id = $(this).data('id');

        if (array_idcat.includes(choice_id)) {

            array_idcat = array_idcat.filter(function(item) {
                return item !== choice_id
            });  

            $(this).css('background-color', 'white');
            $(this).css('border', '#f1f1f1');
            $('.name_category-'+choice_id).attr('class','name_category-'+choice_id+' text-secondary');  

        }
        else {

            array_idcat.push(choice_id);

            $(this).css('background-color', '#ff6b00');
            $(this).css('border', '#ffc107');
            $('.name_category-'+choice_id).css('color','white');
            $('.name_category-'+choice_id).attr('class','name_category-'+choice_id+'');

        }
        
        console.log(array_idcat);
        $("#idcat_val").val(array_idcat.join("|"));

        if (array_idcat.length > 2) {

            $("#next-grey").hide();
            $("#submit-orange").show();

        }
        else {

            $("#next-grey").show();
            $("#submit-orange").hide();

        }

    });

    function submitCategory() {
        var myform = $("#imi-community")[0];
        var fd = new FormData(myform);

        fd.append("f_pin", F_PIN);

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register-imi-communities",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                
                if(window.Android){
                    window.Android.closeCategoryContent();
                }

            },
            error: function (response) {
                // modalProgress.style.display = "none";
                $('#modalProgress').modal('hide');
                $('#modal-payment').modal('hide');
                // alert(response.responseText);

                $('#error-modal-text').text(response.responseText);
                $('#modal-error').modal('show');
                $("#submit").prop("disabled", false);
            }
        });
    }

    function back() {
        
        if(window.Android){
            window.Android.backCategoryContent();
        }

    }

    function follow(id_tkt){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var L_PIN = id_tkt;

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('l_pin', L_PIN);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                $('#unfollowed-'+id_tkt).attr('src','../assets/img/followed.svg');


            }
        }
        xmlHttp.open("post", "../logics/follow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    $("#notification").hide();
    $("#no-notification").show();

    function closeAndroid(){

    history.back();
    
    }

</script>