<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $f_pin = $_GET['f_pin'];

session_start();

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
else if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}

$dbconn = paliolite();

$ver = time();

// PROVINCES

$sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$provinces = $queDATA->get_result();
$queDATA->close();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '3'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

// LOAD DATA FROM KTA MOBILITY

$sqlData = "SELECT * FROM KTA WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$ktaData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

// $sqlQuery = "SELECT * FROM KTA LEFT JOIN TKT ON KTA.CLUB_CHOICE = TKT.ID WHERE KTA.F_PIN = '$f_pin'";

// $clubName = $dbconn->prepare($sqlQuery);
// $clubName->execute();
// $showClub = $clubName->get_result()->fetch_assoc();
// $clubName->close();

// print_r($showClub);

// LOAD CLUB LIST

$sqlData = "SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON CLUB_MEMBERSHIP.CLUB_CHOICE = TKT.ID WHERE CLUB_MEMBERSHIP.F_PIN = '$f_pin' AND CLUB_MEMBERSHIP.STATUS != 3";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$clubList = $queDATA->get_result();
$queDATA->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Join IMI Club</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
            margin: 0;
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="radio"]{
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        }

        body.modal-open {
            overflow: hidden;
            position: fixed;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" id="kta-form" style="padding: 0px" enctype="multipart/form-data">
            
            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b style="font-size: 14px">Form Join IMI Club</b>
                    </div>
                </div>
            </div>
            
            <div class="container mx-auto mt-3">

                <?php if($ktaData): ?>
                    <b><p class="text-success mt-3">KTA Data Found.</p></b>
                    <p class="mb-3" style="color: grey">These club are connected to your KTA membership.</p>
                <?php else: ?>
                    <!-- <b><p class="text-danger mt-3 mb-2">KTA Data not found.</p></b> -->
                <?php endif; ?>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClub" aria-expanded="false" aria-controls="collapseClub">
                    <b>Add Club</b>
                    <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClub">
                    
                    <p class="mt-3 mb-1"><b>Club Type</b></p>
                    <div class="row mt-3">
                        <div class="col-6">
                            <input type="radio" id="clubIMI" name="club_type" class="radio" value="1" checked>
                            <label for="clubIMI">&nbsp;&nbsp;IMI Club</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="clubPrivate" name="club_type" class="radio" value="2">
                            <label for="clubPrivate">&nbsp;&nbsp;Private</label><br>
                        </div>
                    </div>
                    <select class="mt-3 mb-2" id="club_location" name="club_location" aria-label="" style="font-size: 16px">
                        <option value=""  selected>Club Location</option>

                        <?php foreach($provinces as $p): ?>
                            <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                        <?php endforeach; ?>

                    </select>

                    <span class="starclubl text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 115px">*</span>
                    <span id="clublocation-error" class="error" style="color: red"></span>
                    
                    <div class="club-bungkus">
                        <select class="mt-3 mb-2" id="club_choice" name="club_choice" aria-label="" style="font-size: 16px">
                            <option value=""  selected>Club Choice</option>
                        </select>

                        <span class="starclubc text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 106px">*</span>
                        <span id="clubchoice-error" class="error" style="color: red"></span>
                    </div>

                </div>

                <div class="form-check mb-4" style="margin-top: 50px">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        <b>I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!</b>
                    </label>
                </div>


                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClubList" aria-expanded="false" aria-controls="collapseClubList">
                    <b>My Club List</b>
                    <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClubList">

                    <?php if (mysqli_num_rows($clubList) == 0) {
                        ?>
                        <p class="mt-2 mb-2">You are not joining any club yet.</p>
                        <?php
                    }
                    
                    else {

                        foreach($clubList as $index => $c):
                            // print_r($provinces);

                        $sqlQuery = "SELECT * FROM TKT WHERE ID = '".$c['CLUB_CHOICE']."'";

                        $clubName = $dbconn->prepare($sqlQuery);
                        $clubName->execute();
                        $showClub = $clubName->get_result()->fetch_assoc();
                        $clubName->close();

                        $sqlQuery = "SELECT * FROM PROVINCE WHERE PROV_ID = '".$c['CLUB_LOCATION']."'";

                        $clubName = $dbconn->prepare($sqlQuery);
                        $clubName->execute();
                        $province = $clubName->get_result()->fetch_assoc();
                        $clubName->close();

                        ?>
                        <div id="card-<?= $index ?>" class="card mt-3">
                            <div class="card-body">
                                
                                <div class="row gx-0">

                                    <div class="col-9">
                                        <b><?= $showClub['CLUB_NAME']; ?></b>
                                        <p style="color: grey"><?= ucfirst(strtolower($province['PROV_NAME'])) ?></p>
                                        <p>
                                            <?php
                                                if ($c['CLUB_TYPE'] == 1){
                                                    echo "Public";
                                                }
                                                else{
                                                    echo "Private";
                                                }
                                            ?>
                                        </p>
                                    </div>

                                    <div class="col-3">

                                        <?php if ($c['STATUS'] == 1): ?>
                                            <div onclick="deleteClub('<?= $c['CLUB_CHOICE'] ?>','<?= $c['GROUP_ID'] ?>')" class="btn btn-sm btn-danger">Delete</div>
                                        <?php elseif ($c['STATUS'] == 2): ?>
                                            <b class="text-danger">Rejected</b>
                                        <?php else: ?>
                                            <b style="color: #f66701">Waiting Approval</b>
                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>
                        </div>
                        <?php
                    endforeach;
                    }
                    ?>
                </div>

            </div>
            <!-- <div class="container">
                <div class="form-group-2 mt-4 mb-4">
                    <div class="row">
                        <div class="col-6" style="color: #626262">
                            Mobility Upgrade Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b style="font-size: 20px">Rp. <?= number_format($upgradeFee, 0, '', '.') ?></b>
                        </div>
                    </div> -->
                    <!-- <div class="row">
                        <div class="col-6" style="color: #626262">
                            Administration Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b>Rp. <?= number_format($adminFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6" style="color: #626262">
                            Total Payment
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b style="font-size: 20px">Rp. <?= number_format($upgradeFee+$adminFee, 0, '', '.') ?></b>
                        </div>
                    </div> -->
                <!-- </div>
            </div> -->

            <div style="width: 100%; height: 5px; margin-top: 25px; background-color: #e5e5e5"></div>
            <div style="background-color: #eee; padding-top: 25px">
                <div class="mt-5">
                    <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                        <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #ff6700; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b>Join Club</b></button>
                    </div>
                </div>
            </div>

        </form>

    <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modalProgress">
                <p>Upload in progress...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Join Club Pending!</h1>
                    <p class="mt-2">Your request need to be approval by club admin.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalDeleteSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Delete IMI Club Success!</h1>
                    <p class="mt-2">Your choosen club has successfully deleted.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="form-join-imi.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 90vh">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalMembership">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Join Club Pending!</h1>
                    <p class="mt-2">Your request need to be approval by club admin.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                        <button type="button" class="btn btn-dark mt-3" id="modalMembership-mainMenu" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="is_kta" name="is_kta" value="<?= $ktaData['ID'] ?>">
    <input type="hidden" id="destination" name="destination" value="">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 9;
        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
    </script>

    <script>

        var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
        var title = 'Join Club Fee';
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';

    </script>

    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-join-club.js?v=<?php echo $ver; ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>

<script>
    
    $(".starclubl").show();
    $(".starclubc").show();

    $(document).ready(function(e) {

        $('#club_location').selectize();    
        $('#club_choice').selectize();

        getDataCookie();

        $('input[type=radio][name=club_type]').change(function() {

            if (this.value == '1') {
                $('#club_choice').attr('disabled',false);
                $('.club-bungkus').show();
            }
            else if (this.value == '2') {
                $('#club_choice').attr('disabled',false);
                $('.club-bungkus').show();
            }

            // CLEAR LOCATION
            var $select = $(document.getElementById('club_location'));
            var selectize = $select[0].selectize;
            // selectize.clearOptions();
            selectize.clear(); 

        });

        $('#collapseClub').on('shown.bs.collapse', function () {
            $('#collapse-img-3').attr('src','../assets/img/arrow-up.png');
        });

        $('#collapseClub').on('hidden.bs.collapse', function () {
            $('#collapse-img-3').attr('src','../assets/img/arrow-down.png');
        });

        $("#club_location-selectized").bind("change paste keyup", function() {

            if($('#club_location-selectized').val()){
                $('.starclubl').hide();
            }else{
                $('.starclubl').show();
            }
        });

        $("#club_choice-selectized").bind("change paste keyup", function() {

            if($('#club_choice-selectized').val()){
                $('.starclubc').hide();
            }else{
                $('.starclubc').show();
            }
        });

        $("#club_location").change(function() {
            var clublvalue = $(this).val();

            if (clublvalue) {
                $(".starclubl").hide();
            }

            else {
                $(".starclubl").show();
            }
        });

        $("#club_choice").change(function() {
            var clubcvalue = $(this).val();

            if (clubcvalue) {
                $(".starclubc").hide();
            }

            else {
                $(".starclubc").show();
            }
        });

    });
    // FOR SELECTIZED VALIDATION

    $("#club_location").change(function() {

        $('#clublocation-error').text("");

        var $select = $(document.getElementById('club_choice'));
        var selectize = $select[0].selectize;

        // CLEAR CHOICE
        selectize.clearOptions();
        selectize.clear(); 

        var location = $(this).val();
        localStorage.setItem("location", location);

        var type = document.querySelector('input[name="club_type"]:checked').value
        var formData = new FormData();

        formData.append('f_pin', F_PIN);
        formData.append('type', type);
        formData.append('location', location);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(atob(xmlHttp.responseText));
                    
                    Object.keys(obj).forEach(function (item){

                        // // console.log(obj[item]['POSTAL_CODE']);
                    
                        selectize.addOption({value: obj[item]['ID'], text: capitalize(obj[item]['CLUB_NAME'])});

                    });
            }
        }
        xmlHttp.open("post", "../logics/get_club");
        xmlHttp.send(formData);
    });

    $("#club_choice").change(function() {
        $('#clubchoice-error').text("");

        var choice = $(this).val();

        var formData = new FormData();
        formData.append('choice', choice);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // console.log(xmlHttp.responseText);

                var obj = JSON.parse(atob(xmlHttp.responseText));
                   
                // console.log(obj.L_PIN);

                $('#destination').val(obj.L_PIN);
            }
        }
        xmlHttp.open("post", "../logics/get_details_club");
        xmlHttp.send(formData);

    });

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    function selectizeValid(){

        storeValues();

        var clublocation = $('#club_location').val();
        var clubchoice = $('#club_choice').val();

        if(!clubchoice){
            $('#clubchoice-error').text("This field is required.");
        }else{
            $('#clubchoice-error').text("");
        }

        if(!clublocation){
            $('#clublocation-error').text("This field is required.");
        }else{
            $('#clublocation-error').text("");
        }
    }

    function deleteClub(club,group) {

        var fd = new FormData();
        fd.append("f_pin", F_PIN);
        fd.append("club", club);

        var group = group;

        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/delete_club",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#modalDeleteSuccess").modal('show');

                exitGroup(group);
            },
            error: function (response) {
                alert("Failed To Delete Club");
            }
        });

    }
    
</script>

<script type="text/javascript">

    var today = new Date();
    var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days

    function setCookie(name, value)
    {
        document.cookie=name + "=" + escape(value) + "; path=/; expires=" + expiry.toGMTString();
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function deleteCookie(name){
        document.cookie=name + "=; path=/; expires=" + expiry.toGMTString();
    }

    function storeValues()  
    {

        setCookie("club_typeJOINIMI", document.querySelector('input[name="club_type"]:checked').value);
        setCookie("club_locationJOINIMI", $('#club_location').val());
        setCookie("club_choiceJOINIMI", $('#club_choice').val());

        return true;

    }

    function getDataCookie(){

        if (club_type = getCookie("club_typeJOINIMI")){
            if (club_type==1){
                $('#clubIMI').prop('checked', true);
            }else{
                $('#clubPrivate').prop('checked', true);
            }
        }

        if (lokasiklubs = getCookie("club_locationJOINIMI")){
            var $select = $(document.getElementById('club_location'));
            var selectize = $select[0].selectize;
            selectize.setValue(lokasiklubs);
            $('.starclubl').hide();
        }

        // if (club_choice = getCookie("club_choiceJOINIMI")){
        //     var $select = $(document.getElementById('club_choice'));
        //     var selectize = $select[0].selectize;
        //     selectize.setValue(club_choice);
        // }

    }

    function deleteAllCookie(){

        deleteCookie("club_typeJOINIMI");
        deleteCookie("club_locationJOINIMI");
        deleteCookie("club_choiceJOINIMI");
        
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