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

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '11'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

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

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

// print_r($upgradeFee);

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
            font-family: 'Poppins' !important;
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

<body>

    <!-- ROADSIDE ASSISTANCE -->
    <section id="imi-roadside-assistance">

        <div class="main" style="padding: 0px">

            <form method="POST" class="main-form" style="padding: 0px" id="roadside-assistance" enctype="multipart/form-data">
                <div class="row gx-0 p-3" style="border-bottom: 2px #e5e5e5 solid">
                    <div class="col-2">
                        <a class="ms-2" onclick="history.back()" style="position: absolute"><img src="../assets/img/membership-back.png" alt="" style="height: 22px"></a>
                    </div>
                    <div class="col-10">
                        <p style="margin-bottom: 0px; font-size: 14px; font-weight: 700">Gaspol RodA</p>
                    </div>
                </div>

                <div class="container mx-auto mt-3">

                    <!-- <div id="popup-success" class="alert alert-success" role="alert">
                        You've successfully changed your profile.
                    </div> -->

                    <div aria-expanded="false" aria-controls="collapseClubInfo">
                        <p style="font-size: 13px; font-weight: 600">Emergency</p>
                        <b style="font-size: 19px; font-weight: 700">Roadside Assistance<b>
                    </div>

                    <div class="collapse show mt-3" id="collapseClubInfo">
                        
                        <p style="color: #555555; font-size: 13px; font-weight: normal">Specialized in Vehicle Logistics for all types of vehicles, we cover Sumatra, Java, and Bali. Fast delivery with 24/7 customer service.</p>
                        
                        <div class="row mt-3">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/path.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px">Value for Money</p>
                            </div>
                            <div class="col-2">
                                
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px; color: #777777">Get 1x towing for your vehicle yearly.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/thunder.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px">Quick Response</p>
                            </div>
                            <div class="col-2">
                                
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px; color: #777777">Ready for you 24/7, responds to your calls below 30 sec with attendance max. 1 hour.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/paper.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px">Wide Coverage Tow</p>
                            </div>
                            <div class="col-2">
                                
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px; color: #777777">With distance up to 50Km, we are sure to help.</p>
                            </div>
                        </div>

                        <section id="contact" style="display: inline">

                            <div class="row mt-3">
                                <div class="col-12">

                                    <p style="font-size: 13px; color: #777777">For more information:</p>

                                    <div class="row mt-3">
                                        <div class="col-2 d-flex justify-content-center">
                                            <img src="../assets/img/question.svg" alt="" style="width: 18px; height: 23px">
                                        </div>
                                        <div class="col-10">
                                            <p style="font-size: 13px">(021) 2356 7888 / 5050 7888</p>
                                        </div>
                                        <div class="col-2">
                                            
                                        </div>
                                        <div class="col-10">
                                            <p style="font-size: 13px; color: #777777">Customer care</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-2 d-flex justify-content-center">
                                            <img src="../assets/img/whatsapp.svg" alt="" style="width: 18px; height: 23px">
                                        </div>
                                        <div class="col-10">
                                            <p style="font-size: 13px">021 8060 0691</p>
                                        </div>
                                        <div class="col-2">
                                            
                                        </div>
                                        <div class="col-10">
                                            <p style="font-size: 13px; color: #777777">Whatsapp</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                        </section>

                        <section id="vehicle" class="mt-3" style="display: none">
                            <b style="font-size: 19px; font-weight: 700">Vehicle Information<b>
                            <p style="font-size: 13px; font-weight: 600; color: #555555;">You can add more than one vehicle.</p>

                            <?php
                            foreach ($roadsideAssistance as $ra) {
                                ?>
                                <div class="row mt-3" id="vehicle-<?= $ra['ID'] ?>">
                                    <div class="col-2 d-flex justify-content-center">
                                        <img src="../assets/img/accept.svg" alt="" style="width: 23px; height: 23px">
                                    </div>
                                    <div class="col-8">
                                        <p style="font-size: 13px"><span id="vehicle-name"><?= $ra['BRAND'] ?></span>, <span id="vehicle-series"><?= $ra['VEHICLE_TYPE'] ?></span>, <span id="vehicle-production"><?= $ra['YEAR'] ?></span></p>
                                    </div>
                                    <div class="col-2 d-flex justify-content-center">
                                        <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="row mt-3" id="add-vehicle">
                                <div class="col-2 d-flex justify-content-center">
                                    <img src="../assets/img/additional.svg" alt="" style="width: 16px; height: 16px" class="mt-1">
                                </div>
                                <div class="col-8">
                                    <p style="font-size: 13px">Add vehicle</p>
                                </div>
                                <div class="col-2 d-flex justify-content-center">
                                    <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                                </div>
                            </div>
                        </section>

                    </div> 
                </div>

                <div class="row p-4 bg-light fixed-bottom" style="border-top: 1px solid #e9e9e9">
                    <div class="row gx-0" id="price" style="display: none">
                        <div class="col-6 text-start">
                            <p class="ms-3" style="font-size: 14px; color: #000000">Price</p>
                        </div>
                        <div class="col-6 text-end">
                            <p id="total-price" class="me-3" style="font-size: 14px; font-weight: 600"></p>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="button" id="submit-orange" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white; display: inline" onclick="purchaseCategory()">Purchase</button>
                        <button type="button" id="buy-add-on" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white; display: none" onclick="buyCategory()">Buy Add-on</button>
                    </div>
                </div>

                <!-- <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                <!-- <div class="row p-5">
                    <div class="col-6 d-flex justify-content-start">
                        <p>Favourite Content</p>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" id="changetomodal" style="color: #f66701" onclick="modalCategory()">Change</a>
                    </div>
                </div> -->

                <div class="row px-5" id="row_category">
                    <?php  
                        // foreach ($conCatJoin as $cj) {
                        //     foreach ($contentCategory as $ct) {
                        //         if ($cj == $ct['ID']) {
                        //         ?>
                                    <!-- <div class="col-6 text-center" id="col_category">
                                        <div style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                            <img src="../assets/img/<?= $ct['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><?= $ct['CONTENT_CATEGORY'] ?>
                                        </div>
                                    </div> -->
                                    <?php
                        //         }
                        //     }
                        // }
                    ?>
                </div>

                <div class="mt-4" style="width: 100%; height: 250px; background-color: white"></div>

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

        </div>

    </section>
    <!-- END OF ROADSIDE ASSISTANCE -->

    <!-- CHECKOUT -->
    <section id="imi-checkout" style="display: none">

        <div class="main" style="padding: 0px">

            <form method="POST" class="main-form" style="padding: 0px" id="checkout" enctype="multipart/form-data">
                <div class="row gx-0 p-3" style="border-bottom: 2px #e5e5e5 solid">
                    <div class="col-10">
                        <p class="ms-2" style="margin-bottom: 0px; font-size: 14px; font-weight: 700">Checkout</p>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                        <a class="" nclick="history.back()" style="position: absolute"><img src="../assets/img/xicon.png" alt="" style="height: 22px; margin-top: -11px"></a>
                    </div>
                </div>

                <div class="container mx-auto mt-3">

                    <!-- <div id="popup-success" class="alert alert-success" role="alert">
                        You've successfully changed your profile.
                    </div> -->

                    <div aria-expanded="false" aria-controls="collapseClubInfo">

                        <p style="font-size: 13px; font-weight: 600">Order Summary</p>
                        <div class="row mt-4">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/wheels.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-7">
                                <p style="font-size: 13px; font-weight: normal">Emergency Roadside Assistance</p>
                            </div>
                            <div class="col-3 text-end">
                                <p class="text-primary" style="font-size: 13px; font-weight: normal">Details</p>
                            </div>
                            <div class="col-2">
                                
                            </div>
                            <div class="col-10">
                                <p id="price-detail" style="font-size: 13px; color: #000000; font-weight: 700">2 x Rp 30.000</p>
                            </div>
                        </div>

                    </div>
                    
                </div>

                <!-- <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                <div id="add-on-container" class="container mx-auto mt-3">

                    <!-- <div id="popup-success" class="alert alert-success" role="alert">
                        You've successfully changed your profile.
                    </div> -->

                    <div aria-expanded="false" aria-controls="collapseClubInfo">

                        <p class="mt-4" style="font-size: 13px; font-weight: 600">Add-on</p>
                        <div class="row mt-4">
                            <div class="col-2 d-flex justify-content-center">
                                <div class="form-check" style="margin-bottom: 0px">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                </div>
                            </div>
                            <div class="col-7">
                                <p style="font-size: 13px; font-weight: normal">Car Insurance - All Risk</p>
                            </div>
                            <div class="col-3 text-end">
                                <p class="text-primary" style="font-size: 13px; font-weight: normal">Details</p>
                            </div>
                            <div class="col-2">
                                
                            </div>
                            <div class="col-10">
                                <p style="font-size: 13px; color: #000000; font-weight: 700">Rp 20.000</p>
                            </div>
                        </div>

                    </div>
                    
                </div>

                <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

                <div class="container mx-auto mt-3">
                    <div class="row mt-4">
                        <div class="col-8">
                            <p style="font-size: 13px; font-weight: 600">Payment Method</p>
                        </div>
                        <div class="col-4 text-end">
                            <p id="other-method" class="text-primary" style="font-size: 13px; font-weight: normal">other method</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-2 d-flex justify-content-center">
                            <img src="../assets/img/card.svg" alt="" style="width: 23px; height: 23px">
                        </div>
                        <div class="col-10">
                            <p style="font-size: 13px; font-weight: 600">Credit / Debit Card</p>
                        </div>
                        <!-- <div class="col-4 text-end">
                            <p class="text-primary">other method</p>
                        </div> -->
                        <div class="col-2">
                            
                        </div>
                        <div class="col-10">
                            <p style="font-size: 13px; color: #777777; font-weight: normal">Accept VISA and Mastercard</p>
                        </div>
                    </div>

                </div>

                <div class="row p-4 bg-light fixed-bottom" style="border-top: 1px solid #e9e9e9">
                    <div class="row gx-0" id="total-payment">
                        <div class="col-6 text-start">
                            <p class="ms-3" style="font-size: 14px; color: #000000; font-weight: normal">Total Payment</p>
                        </div>
                        <div class="col-6 text-end">
                            <p id="total-payment-price" class="me-3" style="font-size: 14px; font-weight: 600">Rp. 60.000</p>
                        </div>
                    </div>
                    <div class="row gx-0 mt-3">
                        <div class="col-6 p-3">
                            <button type="button" id="submit-orange" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: transparent; font-weight: 700; border: 1px solid #000000; color: #000000" onclick="backButton()">Back</button>
                        </div>
                        <div class="col-6 p-3">
                            <button type="button" id="buy-add-on" class="btn btn-secondary" style="width: 100%; border-radius: 20px; background-color: #ff6b00; font-weight: 700; border: none; color: white" onclick="payButton()">Pay Now</button>
                        </div>
                    </div>
                </div>

                <!-- <div class="mt-4" style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                <!-- <div class="row p-5">
                    <div class="col-6 d-flex justify-content-start">
                        <p>Favourite Content</p>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" id="changetomodal" style="color: #f66701" onclick="modalCategory()">Change</a>
                    </div>
                </div> -->

                <div class="row px-5" id="row_category">
                    <?php  
                        // foreach ($conCatJoin as $cj) {
                        //     foreach ($contentCategory as $ct) {
                        //         if ($cj == $ct['ID']) {
                        //          ?>
                                    <!-- <div class="col-6 text-center" id="col_category">
                                        <div style="padding: 8px; padding-left: 15px; padding-right: 15px; border-radius: 15px; width: auto" class="shadow text-secondary m-2">
                                            <img src="../assets/img/<?= $ct['ICON'] ?>" style="width: 15px; height: auto; margin-right: 10px"><?= $ct['CONTENT_CATEGORY'] ?>
                                        </div>
                                    </div> -->
                                    <?php
                        //         }
                        //     }
                        // }
                    ?>
                </div>

                <div class="mt-4" style="width: 100%; height: 250px; background-color: white"></div>

            </form>

        </div>

    </section>
    <!-- END OF CHECKOUT -->

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

    <div id="modal-vehicle" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-vehicle">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="modal-title" style="font-weight: 600">Add Vehicle</h5>
                        </div>
                        <div class="col-12">
                            <p style="font-size: 12px; color: #555555">Insert vehicle information for the E.R.A. add-on.</p>
                        </div>
                    </div>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">

                    <!-- <p style="font-size: 12px">Vehicle Category</p> -->
                    <div class="row">
                        <div class="col-12">
                            <p style="font-size: 12px">Vehicle Category</p>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="automobile" name="vehicle_category" class="radio" value="1" checked>
                            <label for="automobile" style="font-size: 12px">&nbsp;&nbsp;Automobile</label><br>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="motorcycle" name="vehicle_category" class="radio" value="2">
                            <label for="motorcycle" style="font-size: 12px">&nbsp;&nbsp;Motorcycle</label>
                        </div>
                    </div>

                    <select class="mt-3 mb-2" id="vehicle-island" name="vehicle-island" aria-label="" style="font-size: 16px">
                        <option value="" selected>Select Island</option>

                        <?php foreach($province as $p): ?>
                            <option style="font-size: 12px" value="<?= $p['PROV_ID'] ?>"><?= $p['PROV_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span id="island-error" style="font-size: 12px; color: red">This field is required.</span>

                    <!-- <div class="mb-3">
                        <input type="file" onchange="loadFile(event)" class="form-control d-none" id="vehicle-photo-hidden" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px">

                        <label for="vehicle-photo-hidden" class="form-label" style="font-size: 12px">Upload Vehicle Photo</label>
                        <input class="form-control" type="text" id="photo-name" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px" readonly>
                    </div> -->

                    <div class="mb-3">
                        <input type="file" onchange="loadFile(event)" class="form-control d-none" id="vehicle-photo-hidden" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px">

                        <div class="row">
                            <div class="col-10">
                                <input class="form-control" type="text" id="photo-name" style="border: none; border-bottom: 2px solid #ebebeb; font-size: 12px; background-color: white" placeholder="Upload Vehicle Photo" readonly>
                            </div>
                            <div class="col-2 text-center">
                                <label for="vehicle-photo-hidden" class="form-label">
                                    <img id="choose-button" src="../assets/img/choose-image.svg" alt="" style="width: 25px; height: 25px; margin-left: -10px">
                                </label>
                                <img id="revert-button" src="../assets/img/switch-icon.svg" alt="" style="width: 25px; height: 25px; margin-left: -10px" onclick="revertButton()">
                            </div>
                        </div>
                    </div>

                    <span id="photo-error" style="font-size: 12px; color: red">This field is required.</span>


                    <!-- PAIR OF QUERY -->
                    <select class="mt-3 mb-2" id="vehicle-brand" name="vehicle-brand" aria-label="" style="font-size: 16px">
                        <option value="" selected>Vehicle Brand</option>

                        <?php foreach($vehicleBrand as $vb): ?>
                            <option value="<?= $vb['ID'] ?>"><?= $vb['BRAND'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span id="brand-error" style="font-size: 12px; color: red">This field is required.</span>

                    <select class="mt-3 mb-2" id="vehicle-type" name="vehicle-type" aria-label="" style="font-size: 16px">
                        <option value="" selected>Type</option>
                    </select>

                    <span id="type-error" style="font-size: 12px; color: red">This field is required.</span>
                    <!-- END -->

                    <input id="vehicle-year" type="text" style="font-size: 12px" placeholder="Year">
                    <span id="year-error" style="font-size: 12px; color: red">This field is required.</span>

                    <input id="vehicle-license" type="text" style="font-size: 12px" placeholder="License Plate">
                    <span id="license-error" style="font-size: 12px; color: red">This field is required.</span>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 45%">Close</button> -->
                    <button type="button" class="btn btn-dark" style="width: 100%; background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701; border-radius: 20px" onclick="submitButton()">Submit</button>
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

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 90vh">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalMembership">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KTA Mobility Registration Success!</h1>
                    <p class="mt-2">Please download Gaspol Apps to see your KTA card. And check your email to get login information in Gaspol Apps!</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-dark mt-3" id="modalMembership-mainMenu" style="background-color: #f66701; border: 1px solid #f66701">Main Menu</button>
                        </div>
                    </div>
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
        var REG_TYPE = 11;

        var price = 1;
        var raPrice = <?= $upgradeFee ?>;

        var title = 'IMI Roadside Assistance';
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = 0;
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

    $("#contact").show();
    $("#vehicle").hide();
    $("#price").hide();

    $(document).ready(function(e) {
        
        $('#vehicle-island').selectize();

        $("#vehicle-island-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-island-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $('#vehicle-brand').selectize();

        $("#vehicle-brand-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-brand-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $('#vehicle-type').selectize();

        $("#vehicle-type-selectized").bind("change paste keyup", function() {

            if ($('#vehicle-type-selectized').val()) {
                $('.provinsiklub').hide();
            } else {
                $('.provinsiklub').show();
            }
        });

        $("#vehicle-brand").bind("change", function() {

            var $select = $(document.getElementById('vehicle-type'));
            var selectize = $select[0].selectize;

            // CLEAR CITY
            selectize.clear(); 
            selectize.clearOptions();

            var vehicleBrand = $(this).val();
            localStorage.setItem("vehicle-brand", vehicleBrand);

            var formData = new FormData();

            formData.append('vehicle-brand', vehicleBrand);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);
                            
                            selectize.addOption({value: obj[item]['ID'], text: obj[item]['VEHICLE_TYPE']});

                        });
                }
            }
            xmlHttp.open("post", "../logics/get_vehicle_type");
            xmlHttp.send(formData);
            
        });

    });

</script>

<script>

    $("#revert-button").hide();
    var file;

    var loadFile = function(event) {

        var reader = new FileReader();
        reader.onload = function() {

        console.log(reader.result);
        $("#photo-error").hide();
        $("#choose-button").hide();
        $("#revert-button").show();
        file = reader.result;

        event.target.value = '';

        }
        reader.readAsDataURL(event.target.files[0]);

    }

        // var dataURLToBlob = function(dataURL) {
        //     var BASE64_MARKER = ';base64,';
        //     if (dataURL.indexOf(BASE64_MARKER) == -1) {
        //         var parts = dataURL.split(',');
        //         var contentType = parts[0].split(':')[1];
        //         var raw = parts[1];

        //         return new Blob([raw], {
        //             type: contentType
        //         });
        //     }

        //     var parts = dataURL.split(BASE64_MARKER);
        //     var contentType = parts[0].split(':')[1];
        //     var raw = window.atob(parts[1]);
        //     var rawLength = raw.length;

        //     var uInt8Array = new Uint8Array(rawLength);

        //     for (var i = 0; i < rawLength; ++i) {
        //         uInt8Array[i] = raw.charCodeAt(i);
        //     }

        //     return new Blob([uInt8Array], {
        //         type: contentType
        //     });
        // }

</script>

<script>
    if (!window.Android) {
        $("#photo-method").hide();
    } else {
        $("#photo-method").show();
    }
</script>

<script>

    $('#vehicle-photo-hidden').change(function (e) {
        e.preventDefault();
        $('#photo-name').val(this.files[0].name)
    });

    $("#buy-add-on").hide();

    function revertButton() {
        $("#photo-name").val("");
        $("#choose-button").show();
        $("#revert-button").hide();
    }

    function purchaseCategory() {
        $("#modal-vehicle").modal('show');
        $("html").attr("style", "overflow-y: hidden");
    }

    function backButton() {
        $("#imi-checkout").hide();
        $("#imi-roadside-assistance").show();
    }

    $('#modal-vehicle').on('hidden.bs.modal', function (e) {
        $("html").attr("style", "");
    });

    var cars = [];
    var cars_temp;
    var number = 0;
    var count_vehicle = 0;

    $("#island-error").hide();
    $("#photo-error").hide();
    $("#brand-error").hide();
    $("#type-error").hide();
    $("#year-error").hide();
    $("#license-error").hide();

    function submitButton() {

        // $("#modal-vehicle").modal('hide');
        // $("#vehicle").show();
        // $("#contact").hide();

        var category = document.querySelector('input[name="vehicle_category"]:checked').value;
        var island = $('#vehicle-island').val();
        var photo = file;
        var brand = $('#vehicle-brand').val();
        var type = $('#vehicle-type').val();
        var year = $('#vehicle-year').val();
        var license = $('#vehicle-license').val();
        var photo_name = $("#photo-name").val();
        var brand_name = $('#vehicle-brand').text();
        var type_name = $('#vehicle-type').text();

        if (!island) {
            $("#island-error").show();
        }

        if (!photo) {
            $("#photo-error").show();
        }
        else {
            $("#photo-error").hide();
        }

        if (!photo_name) {
            $("#photo-error").show();
        }
        else {
            $("#photo-error").hide();
        }

        if (!brand) {
            $("#brand-error").show();
        }

        if (!type) {
            $("#type-error").show();
        }

        if (!year) {
            $("#year-error").show();
        }

        if (!license) {
            $("#license-error").show();
        }

        $("#vehicle-island").change(function() {
            $("#island-error").hide();
        });

        $("#vehicle-brand").change(function() {
            $("#brand-error").hide();
        });

        $("#vehicle-type").change(function() {
            $("#type-error").hide();
        });

        $("#vehicle-year").bind("change paste keyup", function() {
            $("#year-error").hide();
        });

        $("#vehicle-license").bind("change paste keyup", function() {
            $("#license-error").hide();
        });

        var $select = $(document.getElementById('vehicle-island'));
        var selectize = $select[0].selectize;
        selectize.clear(); 

        var $select = $(document.getElementById('vehicle-brand'));
        var selectize = $select[0].selectize;
        selectize.clear(); 

        var $select = $(document.getElementById('vehicle-type'));
        var selectize = $select[0].selectize;
        selectize.clear(); 
        selectize.clearOptions();

        $('#vehicle-photo').val("");
        $('#photo-name').val("");
        $('#vehicle-year').val("");
        $('#vehicle-license').val("");

        // var format = photo.split(";");

        // SORT JPEG

        // if (format[0].slice(-4) == "jpeg" || format[0].slice(-4) == "webp"){
        //     var ext = format[0].slice(-4);
        // }else{
        //     var ext = format[0].slice(-3);
        // } 

        var converted_link = dataURLtoFile(photo, ".webp");

        if (island && photo && brand && type && year && license) {

            $("#vehicle").show();
            $("#contact").hide();
            // $("#photo-name").val("");

            var html = `<div class="row mt-3" id="vehicle-`+number+`">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/accept.svg" alt="" style="width: 23px; height: 23px">
                            </div>
                            <div class="col-8">
                                <p style="font-size: 13px"><span id="vehicle-name">`+brand_name+`</span>, <span id="vehicle-series">`+type_name+`</span>, <span id="vehicle-production">`+year+`</span></p>
                            </div>
                            <div class="col-2 d-flex justify-content-center">
                                <img src="../assets/img/Back-(White).png" alt="" style="width: 23px; height: 23px; transform: rotate(180deg)">
                            </div>
                        </div>`;

            $('#vehicle').append(html);

            $('#add-vehicle').insertAfter($('#vehicle-'+number));
            number = number + 1;

            if (cars.length > 0){

                cars_temp = [

                    {
                        "category" : category,
                        "island" : island,
                        "photo" : converted_link,
                        "brand" : brand,
                        "type" : type,
                        "year" : year,
                        "license" : license 
                    }

                ];

                cars = cars.concat(cars_temp);

            }else{

                cars = [

                    {
                        "category" : category,
                        "island" : island,
                        "photo" : converted_link,
                        "brand" : brand,
                        "type" : type,
                        "year" : year,
                        "license" : license 
                    }

                ];

            }

            console.log(cars);

            count_vehicle = count_vehicle + 1;
            total_price = count_vehicle*30000;
            const format = total_price.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            const rupiah = 'Rp. ' + convert.join('.').split('').reverse().join('');
            $("#total-price").text(rupiah);
            $("#price-detail").text(count_vehicle+" x "+"Rp. 30.000");
            $("#total-payment-price").text(rupiah);

        }
        else {

            $("#vehicle").hide();
            $("#contact").show();

        }

        if (cars || cars_temp != "") {
            $("#price").show();
            $("#buy-add-on").show();
            $("#submit-orange").hide();
        }

        if (island && photo && brand && type && year && license) {
            $("#modal-vehicle").modal('hide');
        }

    }

    function payButton() {

        // submitForm(11);
        // $("#modalSuccess").modal('show');

        var totalPrice = count_vehicle*raPrice;
        const format = totalPrice.toString().split('').reverse().join('');
        const convert = format.match(/\d{1,3}/g);
        const price_new = 'Rp. ' + convert.join('.').split('').reverse().join('');
        
        $('#price-second').text(price_new);
        $('#total-slot').text(price_new);
        palioPay();

    }

    $("#add-vehicle").on('click', function() {
        $("#modal-vehicle").modal('show');
    });

    // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    $("#imi-checkout").hide();
    // $("#imi-roadside-assistance").show();

    function buyCategory() {

        // window.location.href = '/gaspol_web/pages/imi-checkout?f_pin='+f_pin;

        $("#imi-checkout").show();
        $("#imi-roadside-assistance").hide();

    }

    var brand_name = $('#vehicle-brand').text();
    var type_name = $('#vehicle-type').text();

    
    // $("#popup-success").hide();

    // function saveData(){

    //     var myform = $("#change-user")[0];
    //     var fd = new FormData(myform);

    //     fd.append("f_pin", F_PIN);

    //     $.ajax({
    //         type: "POST",
    //         url: "/gaspol_web/logics/register-user",
    //         data: fd,
    //         enctype: 'multipart/form-data',
    //         cache: false,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
                
    //             $("#popup-success").show();
    //             setInterval(hideMsg, 8000);

    //         },
    //         error: function (response) {
                
    //             alert("Uplod Gagal.");

    //         }
    //     });
    // }

    function hideMsg() {

        $("#popup-success").hide();

    }

    // SCRIPT CONVERT BASE64 TO OBJECT

    function dataURLtoFile(dataurl, filename){
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {type:mime});
    }

</script>

<script>

    <?php

        if (mysqli_num_rows($roadsideAssistance) > 0) {
            ?>
            $("#vehicle").show();
            $("#contact").hide();
            $("#submit-orange").hide();
            $("#buy-add-on").show();
            $("#price").show();
            <?php
        }
        else {
            ?>
            $("#vehicle").hide();
            $("#contact").show();
            $("#submit-orange").show();
            $("#buy-add-on").hide();
            $("#price").hide();
            <?php
        }
        
    ?>

</script>

<script>
    $("#other-method").hide();
    $("#add-on-container").hide();

    if (window.Android) {
        window.Android.tabShowHide(false);
    }
</script>



