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
        <div class="col-10">
            <h3 class="mb-0 mt-3" style="font-weight: 700; font-size: 20px">Choose favourite content</h3>
            <p class="mt-2" style="color: #939393; font-size: 13px">Select minimum of 3 categories</p>
        </div>
        <div class="col-2">
            <img src="../assets/img/xicon.png" style="width: 20px; height: 20px" alt="" class="mt-3 ms-3" onclick="back()">
        </div>
    </div>

    <form id="imi-community" method="POST" class="main-form" enctype="multipart/form-data">

        <div class="row px-4 gx-0">
            <?php
                foreach ($contentCategory as $cc) {
                    ?>
                        <!-- <div class="col-6"> -->
                            <div id="id_category_<?= $cc['ID'] ?>" data-id="<?= $cc['ID'] ?>" style="padding: 8px; padding-right: 15px; border-radius: 15px; width: auto; border: 1px solid #f1f1f1" class="class_category btn shadow text-secondary m-2">
                                <img src="../assets/img/<?= $cc['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><span class="name_category-<?= $cc['ID'] ?>" style="font-size: 14px"><?= $cc['CONTENT_CATEGORY'] ?></span>
                            </div>
                        <!-- </div> -->
                    <?php
                }
            ?>
        </div>

        <input id="idcat_val" type="hidden" value="" name="idcat_val">

        <div class="row p-4">
            <div class="col-12">
                <h3 class="mb-0 mt-5" style="font-weight: 700; font-size: 20px">IMI TKT for you</h3>
                <p class="mt-2" style="color: #939393; font-size: 13px">Follow commmunity that excite you</p>
            </div>
        </div>

        <div class="row p-4" style="margin-top: -40px; margin-bottom: 100px">
            <?php
            foreach ($tktName as $tn) {

                // TKT F_PIN
                $id = $tn['ID'];

                // GET FOLLOW COUNT

                $tktDataFollow = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE TKT_ID = '".$id."'");
                $tktDataFollow->execute();
                $tktNameFollow = $tktDataFollow->get_result();
                $tktDataFollow->close();

                // CHECK IS FOLLOW

                $tktDataFollow = $dbconn->prepare("SELECT * FROM FOLLOW_TKT WHERE F_PIN = '$f_pin' AND TKT_ID = '".$id."'");
                $tktDataFollow->execute();
                $isFollow = $tktDataFollow->get_result();
                $tktDataFollow->close();

                // GET MEMBERS COUNT

                $tktDataMember = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP WHERE CLUB_CHOICE = '".$id."'");
                $tktDataMember->execute();
                $tktNameMember = $tktDataMember->get_result();
                $tktDataMember->close();

                // print_r(mysqli_num_rows($tktNameMember));

                ?>
                <div class="col-6 mt-3">
                    <div id="user_list" class="p-4 text-center shadow" style="border: 1px solid #f0f0f0; border-radius: 15px; height: 250px">
                        <?php
                            if (mysqli_num_rows($isFollow) > 0) {
                                ?>
                                <img id="followed-<?= $tn['ID'] ?>" src="../assets/img/followed.svg" alt="" style="width: 25px; height: 25px; position: absolute; margin-left: 78px; margin-top: -14px" onclick="unfollow('<?= $tn['ID'] ?>')">
                                <?php
                            }
                            else {
                                ?>
                                <img id="followed-<?= $tn['ID'] ?>" src="../assets/img/tkt_add_follow.png" alt="" style="width: 25px; height: 25px; position: absolute; margin-left: 78px; margin-top: -14px" onclick="follow('<?= $tn['ID'] ?>')">
                                <?php
                            }
                        ?>
                        <?php
                        if ($tn['PROFILE_IMAGE']) {
                            ?>
                            <img src="../images/<?= $tn['PROFILE_IMAGE'] ?>" alt="" style="width: 80px; height: 80px; border-radius: 50%">
                            <?php
                        }
                        else {
                            ?>
                            <img src="../assets/img/no-avatar.jpg?v=2" alt="" style="width: 80px; height: 80px; border-radius: 50%">
                            <?php
                        }
                        ?>
                        <p class="mb-0 mt-2"><img src="../assets/img/star-rating.svg" alt="" style="width: 15px"><span style="font-size: 13px; margin-top: 5px; margin-left: 8px; color: #939393">Club</span></p>
                        <p class="mt-3 mb-0" style="font-size: 14px; font-weight: 700"><?= $tn['CLUB_NAME'] ?></p>
                        <p class="mt-3 mb-0"><img src="../assets/img/tkt_member.png" alt="" style="width: 20px; margin-top: -3px"><span style="font-size: 13px; color: #939393; margin-left: 3px"><?= mysqli_num_rows($tktNameFollow) ?></span>&nbsp;&nbsp;&nbsp;<img src="../assets/img/tkt_follower.png" alt="" style="width: 20px; margin-top: -3px"><span style="font-size: 13px; color: #939393; margin-left: 3px"><?= mysqli_num_rows($tktNameMember) ?></span></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <div style="width: 100%; background-color: #e5e5e5"></div>
        
        <div class="row p-4 bg-light fixed-bottom" style="border-top: 1px solid #e9e9e9">
            <div class="col-12">
                <button type="button" id="next-grey" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #c9c9c9; font-weight: 700; border: none">Next</button>
                <button type="button" id="submit-orange" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white" onclick="submitCategory()">Next</button>
            </div>
        </div>
        
    </form>

    <!-- JS -->
    <script src="../assets/js/jquery.min.js"></script>
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

    $("#submit-orange").hide();
    var array_idcat = [];
    
    $('body').on('click', '.class_category', function() {

        var choice_id = $(this).data('id');

        if (array_idcat.includes(choice_id)) {

            array_idcat = array_idcat.filter(function(item) {
                return item !== choice_id
            });  

            $(this).css('background-color', 'white');
            $(this).css('border', '1px solid #f1f1f1');
            $('.name_category-'+choice_id).attr('class','name_category-'+choice_id+' text-secondary');  

        }
        else {

            array_idcat.push(choice_id);

            $(this).css('background-color', '#ff6b00');
            $(this).css('border', '1px solid #ffc107');
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

                $('#followed-'+id_tkt).attr('src','../assets/img/followed.svg');
                $('#followed-'+id_tkt).attr('onclick','unfollow("'+id_tkt+'")');

            }
        }
        xmlHttp.open("post", "../logics/follow_gaspol_tkt");
        xmlHttp.send(formData);

    }

    function unfollow(id_tkt){

        var F_PIN = "<?= $_GET['f_pin'] ?>";
        var id_unfollow = id_tkt;

        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('id_unfollow', id_unfollow);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                var result = xmlHttp.responseText;

                $('#followed-'+id_tkt).attr('src','../assets/img/tkt_add_follow.png');
                $('#followed-'+id_tkt).attr('onclick','follow("'+id_tkt+'")');

            }
        }
        xmlHttp.open("post", "../logics/unfollow_gaspol_tkt");
        xmlHttp.send(formData);

    }

</script>