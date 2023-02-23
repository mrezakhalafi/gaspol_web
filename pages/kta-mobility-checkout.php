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
else if(isset($_SESSION['user_f_pin'])){
    $f_pin = $_SESSION['user_f_pin'];
}

if (!isset($_GET['env'])) {
    $env = 1;
} else {
    $env = $_GET['env'];
}

$dbconn = paliolite();

$ver = time();

// GET USER DATA

$sqlData = "SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$userData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

// GET USER DATA

$sqlData = "SELECT * FROM USER_LIST_EXTENDED WHERE F_PIN = '$f_pin'";

$queEXDATA = $dbconn->prepare($sqlData);
$queEXDATA->execute();
$userEXData = $queEXDATA->get_result()->fetch_assoc();
$queEXDATA->close();

// GET USER DATA EXTENDED GASPOL

$sqlData = "SELECT * FROM USER_LIST_EXTENDED_GASPOL WHERE F_PIN = '$f_pin'";

$queEXDATA = $dbconn->prepare($sqlData);
$queEXDATA->execute();
$userEXDataGaspol = $queEXDATA->get_result()->fetch_assoc();
$queEXDATA->close();

// PROVINCE

$sqlData = "SELECT * FROM PROVINCE";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$province = $queDATA->get_result();
// $provinceData = $province['PROV_ID'];
$queDATA->close();

// CLUB CHOICE
$club_category = $dbconn->prepare("SELECT * FROM TKT");
$club_category->execute();
$show_club = $club_category->get_result();
$club_category->close();

// KTA F_PIN
$ktaData = $dbconn->prepare("SELECT * FROM KTA WHERE F_PIN = '$f_pin'");
$ktaData->execute();
$ktaName = $ktaData->get_result()->fetch_assoc();
$ktaData->close();

// CONTENT CATEGORY
$content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE");
$content->execute();
$contentCategory = $content->get_result();
$content->close();

// CONTENT CATEGORY JOIN
$contentJ = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE LEFT JOIN USER_LIST_EXTENDED_GASPOL ON CONTENT_PREFERENCE.ID = USER_LIST_EXTENDED_GASPOL.ID_CATEGORY WHERE USER_LIST_EXTENDED_GASPOL.F_PIN = '$f_pin'");
$contentJ->execute();
$contentCategoryJoin = $contentJ->get_result()->fetch_assoc();
$conCatJoin = explode("|", $contentCategoryJoin['ID_CATEGORY']);
$contentJ->close();

// VEHICLE BRAND AND TYPE
// $queryVehicleBrandType = $dbconn->prepare("SELECT * FROM VEHICLE_BRAND LEFT JOIN VEHICLE_TYPE ON VEHICLE_BRAND.ID = VEHICLE_TYPE.VEHICLE_ID");
// $queryVehicleBrandType->execute();
// $vehicleBrandType = $queryVehicleBrandType->get_result();
// $queryVehicleBrandType->close();

$queryVehicleBrand = $dbconn->prepare("SELECT * FROM VEHICLE_BRAND ORDER BY BRAND ASC");
$queryVehicleBrand->execute();
$vehicleBrand = $queryVehicleBrand->get_result();
$queryVehicleBrand->close();

$queryVehicleType = $dbconn->prepare("SELECT * FROM VEHICLE_TYPE ORDER BY VEHICLE_TYPE ASC");
$queryVehicleType->execute();
$vehicleType = $queryVehicleType->get_result();
$queryVehicleType->close();

// foreach ($vehicleBrandType as $vbt) {
//     print_r ($vbt);
// }

// GET CATEGORY NAME

$arrayName = array();

foreach ($conCatJoin as $ct):

    $content = $dbconn->prepare("SELECT * FROM CONTENT_PREFERENCE WHERE ID = '".$ct."'");
    $content->execute();
    $categoryName = $content->get_result()->fetch_assoc();

    if($arrayName){

        $arrayName .= "|".$categoryName['CONTENT_CATEGORY'];

    }else{

        $arrayName = $categoryName['CONTENT_CATEGORY'];

    }
    $content->close();

endforeach;

$queryRoadsideAssistance = $dbconn->prepare("SELECT * FROM ROADSIDE_ASSISTANCE LEFT JOIN VEHICLE_BRAND ON ROADSIDE_ASSISTANCE.VEHICLE_BRAND = VEHICLE_BRAND.ID LEFT JOIN VEHICLE_TYPE ON ROADSIDE_ASSISTANCE.TYPE = VEHICLE_TYPE.ID WHERE F_PIN = '$f_pin'");
$queryRoadsideAssistance->execute();
$roadsideAssistance = $queryRoadsideAssistance->get_result();
$queryRoadsideAssistance->close();

// print_r($roadsideAssistance);

// foreach ($roadsideAssistance as $ra) {
//     print_r($ra);
// }

// GET BIRTHDATE

$date = $userEXData['BIRTHDATE']; 
$sec = strtotime($date);  
$newdate = date ("Y-m-d", $sec);  

// print_r ($userData['IMAGE']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMI Roadside Assistance</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content {
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

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        input[type="radio"] {
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .collapse {
            border-radius: 20px;
        }

        /* .modal-footer {
            display: unset !important;
        } */

        .modal-content-vehicle {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: unset;
        }

        .selectize-input>* {
            font-size: 12px;
        }

        .selectize-dropdown-content {
            font-size: 12px;
        }

        #vehicle-island-selectized {
            font-size: 12px;
        }

        #vehicle-brand-selectized {
            font-size: 12px;
        }

        #vehicle-type-selectized {
            font-size: 12px;
        }

        #vehicle-year-selectized {
            font-size: 12px;
        }

        .selectize-control.single .selectize-input, .selectize-control.single .selectize-input input {
            border: none;
            border-bottom: 1px solid #ebebeb;
        }

        input, select {
            padding-left: 12px;
        }

    </style>
</head>

<body style="background-color: #ededed">

    <!-- CHECKOUT -->
    <section id="checkout" style="border-bottom: 1px solid white; border-radius: 20px; background-color: white">

        <div class="row gx-0 p-3" style="background-color: white">
            <div class="col-10">
    
            </div>
            <div class="col-2 d-flex justify-content-center">
                <a class="" onclick="closeAndroid()" style="position: absolute"><img src="../assets/img/xicon.png" alt="" style="height: 22px; margin-left: 13px; margin-top: 5px"></a>
            </div>
        </div>

        <div class="row p-5">
            <div class="col-12 d-flex justify-content-center">
                <img src="../assets/img/success.png" alt="" style="height: 80px; width: 80px; margin-top: -23px">
            </div>
            <div class="col-12 text-center mt-4">
                <p id="success-message" style="font-size: 20px; font-weight: 600"></p>
            </div>
            <div class="col-12 text-center mt-2">
                <p style="font-size: 13px">Verifying your information, usually takes  within 24 hours or less.</p>
            </div>
            <div class="col-12 d-flex justify-content-center mt-4">
                <button onclick="goToAccount()" class="btn" style="background-color: #FF6B00; font-size: 14px; color: white; border-radius: 20px; width: 100%; font-weight: 600">Go to Account</button>
            </div>
        </div>

    </section>
    <!-- END CHECKOUT -->

    <!-- <div style="width: 100%; height: 20px; background-color: #e5e5e5"></div> -->

    <!-- <div class="row gx-0 p-3">
        <p style="font-size: 12px; font-weight: 600; color: #777777">Other Information</p>
    </div> -->

    <!-- INFORMATION -->
    <section id="checkout" class="p-4">

        <div class="row gx-0">
            <p style="font-size: 12px; font-weight: 600; color: #777777">Other Information</p>
        </div>

        <div class="row p-3 mt-3" style="background-color: white; border: 1px solid white; border-radius: 5px">
            <div class="col-12 text-center">
                <div class="row mt-2 mb-2">
                    <div class="col-2 d-flex justify-content-center">
                        <img src="../assets/img/information.svg" alt="" style="width: 18px; height: 18px">
                    </div>
                    <div class="col-8 d-flex justify-content-start">
                        <p style="font-size: 13px; font-weight: 600">Frequently Asked Questions</p>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                    <img src="../assets/img/Back-(White).png" alt="" style="width: 18px; height: 18px; transform: rotate(180deg)">
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-3">
                <div class="row mt-2 mb-2">
                    <div class="col-2 d-flex justify-content-center">
                        <img src="../assets/img/ktp-icon.svg" alt="" style="width: 18px; height: 18px">
                    </div>
                    <div class="col-8 d-flex justify-content-start">
                        <p style="font-size: 13px; font-weight: 600">About Membership</p>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                    <img src="../assets/img/Back-(White).png" alt="" style="width: 18px; height: 18px; transform: rotate(180deg)">
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-3">
                <div class="row mt-2 mb-2">
                    <div class="col-2 d-flex justify-content-center">
                        <img src="../assets/img/anonym-icon.svg" alt="" style="width: 18px; height: 18px">
                    </div>
                    <div class="col-8 d-flex justify-content-start">
                        <p style="font-size: 13px; font-weight: 600">View Profile</p>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                    <img src="../assets/img/Back-(White).png" alt="" style="width: 18px; height: 18px; transform: rotate(180deg)">
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-3">
                <div class="row mt-2 mb-2">
                    <div class="col-2 d-flex justify-content-center">
                        <img src="../assets/img/headphone.svg" alt="" style="width: 18px; height: 18px">
                    </div>
                    <div class="col-8 d-flex justify-content-start">
                        <p style="font-size: 13px; font-weight: 600">Costumer Suppport</p>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                    <img src="../assets/img/Back-(White).png" alt="" style="width: 18px; height: 18px; transform: rotate(180deg)">
                    </div>
                </div>
            </div>
            
        </div>

    </section>
    <!-- END INFORMATION -->

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-error-body">
                    <p id="error-modal-text">Please fill the required form.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p id="validation-text">Please fill all required form</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSuccess" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Vehicle Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <!-- <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="card-kta-mobility.php?f_pin=< $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">View Card</button></a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";

        var title = '';
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';
    </script>

    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/imi-roadside-assistance.js?v=<?php echo $ver; ?>"></script>

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

    var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var env = new URLSearchParams(window.location.search).get('env');

    if (env == null) {
        env = 1;
    }

    if (env == 1) {
        $("#success-message").text("KTA Mobility Registration Success!");
    }
    else if (env == 2) {
        $("#success-message").text("KTA Pro Registration Success!");
    }
    else if (env == 3) {
        $("#success-message").text("KIS License Purchase Success!");
    }

    function closeAndroid(){

        window.location.href = "menu_membership?f_pin=".concat(f_pin)

    }

    function goToAccount(){

        window.location.href = "menu_membership?f_pin=".concat(f_pin)

    }

</script>